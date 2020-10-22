<template>
    <div>
        <div v-if="errors.message">
            <!-- :show="dismissCount" untuk mendefinisikan berapa detik waktu tampil. di data harus di set waktunya. misal dismissCount: 5 -->
            <b-alert dismissible variant="danger" show>
                {{ errors.message }}
            </b-alert>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- KETIKA TOMBOL ADD NEW DITEKAN -->
                <div class="form-group">
                    <label for="">Customer 
                        <sup>
                            <a @click="newCustomer" href="javascript:void(0)" v-if="!isForm" class="text-decoration-none">New customer</a>
                        </sup>
                    </label>
                    <!-- alurnya. ketika kata kunci dimasukkan, akan memanggil fungsi onSearchCustomer(). onSearchCustomer() memanggil getCustomer() utk mendapatkan data customer sesuai kata kunci yg diketikkan. -->
                    <!-- data customer tsb dikirmkan melalui transaction.js kemudian diambil oleh mapState customers. data mapState customers diambil oleh :options="customers.data" utk ditampilkan-->
                    <!-- oleh v-model, data yg dipilih kemudian dimasukkan ke data() order.customer -->
                    <v-select @search="onSearchCustomer" :options="customers.data" v-model="order.customer" label="name" placeholder="Masukkan Kata Kunci" :filterable="false">
                        <template slot="no-options">
                            Masukkan Kata Kunci
                        </template>
                        <template slot="option" slot-scope="option">
                            {{ option.name }}
                        </template>
                    </v-select>
                    <p class="text-danger" v-if="errors.customer">{{ errors.customer[0] }}</p>
                </div>
            </div>
            <!-- menampilkan data customer yg dipilih -->
            <div class="col-md-6" v-if="order.customer != null && !isForm">
                <!-- ditambahkan [], karena :items merender array. sedangkan data dari order.customer bentuknya object -->
                <b-table stacked small responsive="sm" :items="[order.customer]" :fields="fieldss">
                    <template v-slot:cell(deposite)="row" v-if="order.customer.deposite">
                        {{ row.item.deposite.amount | currency }}
                    </template>
                </b-table>
            </div>
            <!-- start add new customer. mengambil form dari customers/Form.vue -->
            <!-- jika isForm true maka akan menampilkan form add new customer -->
            <div class="col-md-6" v-if="isForm">
                <h4>Add New Customer</h4>
                <customer-form />
                <button class="btn btn-primary btn-sm" @click="addCustomer" :disabled="isLoading">Save</button>
                <button class="btn btn-secondary btn-sm" @click="isForm = false" :disabled="isLoading">Batal</button>
            </div>
            <!-- end add new customer -->
            <!-- start add new item transaction -->
            <div class="col-md-12">
                <hr>
                <button class="btn btn-warning btn-sm" v-if="filterEmptyProduct.length == 0" @click="addItem" data-toggle="tooltip" data-placement="top" title="Tambah item">Tambah</button>
                <div class="pt-2">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Tipe</th>
                                <th>Size</th>
                                <th>Product</th>
                                <th width="10%">Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in order.transactions" :key="index">
                                <td>
                                    <!-- type -->
                                    <select v-model="row.type_id" @change="onChangeType({type_id: row.type_id, index: index})" class="form-control">
                                        <option value="">-- Pilih Type --</option>
                                        <option v-for="(data, index) in types" :value="data.id" :key="index">{{ data.name }}</option>
                                    </select>
                                </td>
                                
                                <td>
                                    <!-- size -->
                                    <!-- table size ini hanya utk bantu menampilkan product apa saja yg memiliki size tertentu. sehingga memudahkan user memilih produk berdasarkan size tertentu. Aslinya pengambilan value size ada di product(dibawah ini) -->
                                    <select v-model="row.size" @change="onChangeType({type_id: row.type_id, index: index})" class="form-control">
                                        <option value="">-- Pilih Size --</option>
                                        <option v-for="(data, index) in type.product" :value="data.pivot.size" :key="index">{{ data.pivot.size }}</option>
                                    </select>
                                </td>
                                <td>
                                    <!-- product -->
                                    <select v-model="row.product" class="form-control">
                                        <option value="">-- Pilih Product --</option>
                                        <option v-for="(data, index) in type" :value="data" :key="index">{{ data.name }}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" min="1" v-model="row.quantity" @change="calculate(index)" class="form-control">
                                </td>
                                <td> {{ row.price | currency }} </td>
                                <td> {{ row.subtotal | currency }} </td>
                                <td>
                                    <button class="btn btn-danger btn-sm" @click="removeItem(index)" data-toggle="tooltip" data-placement="top" title="Hapus item"><i class="fa fa-trash"> Hapus</i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end add new item -->
            <div class="col-md-12" v-if="isSuccess">
                <div class="alert alert-success">
                    Transaksi Berhasil, Total Tagihan: {{ total | currency }}
                    <strong><router-link :to="{ name: 'transaction.view', params: {id: order_id} }" class="text-decoration-none">Lihat Detail</router-link></strong>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import _ from 'lodash'
import { mapState, mapActions, mapMutations } from 'vuex'
import FormCustomer from '../customers/Form.vue'

export default {
    name: 'FormTransaction',
    created() {
        this.getTypes()
    },
    data(){
        return {
            isForm: false,
            //menampilkan customer tabel
            fieldss: [
                { key: 'nik', label: 'NIK', },
                { key: 'address', label: 'Alamat' },
                { key: 'phone', label: 'No. Telp' },
                { key: 'point', label: 'Point' },
                { key: 'deposite', label: 'Deposite' }
            ],
        }
    },
    computed: {
        ...mapState(['errors']),
        ...mapState('transaction', {
            customers: state => state.customers,
            type: state => state.type,
            products: state => state.products,
            order: state => state.order,
            isSuccess: state => state.isSuccess,
            order_id: state => state.order_id,
            isLoading: state => state.isLoading
        }),
        ...mapState('type', {
            types: state => state.types, //mengambil state types di type.js utk ditampilkan pada select types
        }),
        total(){
            //menjumlah subtotal
            return _.sumBy(this.order.transactions, function(o){
                return parseFloat(o.subtotal) //parseFloat untuk memastikan value yg di sum bukan string
            })
        },
        //melakukan filter transaksi yg masih kosong
        filterEmptyProduct(){
            return _.filter(this.order.transactions, function(item){
                return item.subtotal == 0
            })
        }
    },
    methods: {
        ...mapActions('transaction', ['getProducts', 'getType', 'getCustomers', 'createTransaction']),
        ...mapActions('customer', ['submitCustomer']),
        ...mapActions('type', ['getTypes']),
        ...mapMutations('transaction', ['CLEAR_FORM', 'SET_SUCCESS']),
        //method ini berjalan ketika pencarian data customer pada v-select
        onSearchCustomer(search, loading){
            //request data berdasarkan keyword yg diminta
            this.getCustomers({
                search: search,
                loading: loading
            })
        },
        onChangeType(e){
            //request data berdasarkan keyword yg diminta
            this.getType({id: e.type_id, size: this.order.transactions[e.index].size})
        },
        //ketika tombol tambah ditekan, akan menambhakn item baru
        addItem(){
            //jika filter transaksi yg masih kosong = 0 (tidak ada) alias tidak ada transaksi yg kosong
            if(this.filterEmptyProduct.length == 0){
                this.order.transactions.push(
                    { product: '', type_id: '', size: '', quantity: 0, price: 0, subtotal: 0 }
                )
            }
        },
        //menghapus item berdasarkan index data
        removeItem(index){
            if(this.order.transactions.length > 1){
                this.order.transactions.splice(index, 1)
            }
        },
        //menghitung subtotal. menggenerate value transactions.price dan transactions.subtotal
        calculate(index){
            //index mengambil value index pd inputan yang sedang dimasukkan/mau dihitung. index menunjukkan value array ke-x
            let dataa = this.order.transactions[index]
            if(dataa.product != null){
                //mengisi price untuk setiap itemnya yg didapat dari product. price ini hanya untuk ditampilkan di client, bukan utk backend. backend ngambil price dari transactions.product.price
                dataa.price = dataa.product.price
                dataa.subtotal = parseInt(dataa.product.price) * parseInt(dataa.quantity)
            }
        },
        //ketika tombol create transaction ditekan. tombol ini ada di Add.vue
        submit(){
            //filter price != 0. jika 0 berarti produk belum diinputkan
            let filter = _.filter(this.order.transactions, function(item){
                return item.price != 0
            })

            //jika data transaksi tidak kosong
            if(filter.length > 0){
                this.createTransaction()
            }
        },
        newCustomer(){
            this.isForm = true
        },
        addCustomer(){
            this.submitCustomer()
            .then((res) => {
                //kolom pencarian customer otomatis di set data customer baru
                this.order.customer = res.data //berisi data user baru complite. ini ada di transaction.js state order
                this.isForm = false
            })
        },
        resetForm(){
            this.CLEAR_FORM()
            this.SET_SUCCESS(false)
        }
    },
    //ketika halaman form ditinggalkan
    destroyed(){
        //form dibersihkan
        this.CLEAR_FORM()
        this.SET_SUCCESS(false) //set alert success jadi false
    },
    components: {
        'v-select': vSelect,
        'customer-form': FormCustomer
    }
}
</script>
