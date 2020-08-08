<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <router-link :to="{ name: 'transaction.add' }" class="btn btn-primary btn-sm">Tambah</router-link>
            </div>
            <!-- kolom filter status pembayaran -->
            <div class="p-2">
                <select v-model="filter_isPaid" class="form-control">
                    <option value="2">All</option>
                    <option value="1">Selesai</option>
                    <option value="0">Proses</option>
                </select>
            </div>
            <!-- kolom search -->
            <div class="p-2">
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>

        <div>
            <!-- :items="products.data" mengambil dari computed mapState -->
            <!-- items berisi data user yg akan di render ke table, sedangakn fields akan menjadi nama header pd table -->
            <b-table striped hover :items="transactions.data" :fields="fieldss" :tbody-transition-props="transProps" primary-key="index">
                <!-- template hanyalah optional saja untuk menginginkan tampilan sesuai yg diharapkan -->
                <!-- :fields="fields" menampilkan label, sedangkan v-slot:cell(customer)="row" menampilkan key  -->
                <template v-slot:cell(index)="row">
                    {{ row.index + 1 }}
                </template>
                <template v-slot:cell(customer)="row">
                    <p><strong>{{ row.item.customer ? row.item.customer.name:'' }}</strong></p>
                    <p>Telp: {{ row.item.customer.phone }}</p>
                    <p>Alamat: {{ row.item.customer.address }}</p>
                </template>
                <template v-slot:cell(user_id)="row">
                    <p>{{ row.item.user.name }}</p>
                </template>
                <template v-slot:cell(service)="row">
                    <p>{{ row.item.transaction.length }} item </p>
                </template>
                <template v-slot:cell(amount)="row">
                    <p>{{ row.item.amount | currency }}</p>
                </template>
                <template v-slot:cell(created_at)="row">
                    <p>{{ row.item.created_at | formatDate }}</p>
                </template>
                <template v-slot:cell(isPaid)="row">
                    <!-- status_badge adalah field virtual yg dibuat di model Order.php -->
                    <p v-html="row.item.status_badge"></p>
                </template>
                <template v-slot:cell(actions)="row">
                    <!-- utk edit pakai a href sesuai link dari router yg diberikan -->
                    <router-link :to="{ name: 'transaction.view', params: {id: row.item.id} }" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="View transaction"><i class="fa fa-eye"></i></router-link>
                </template>
            </b-table>
            <!-- pagination -->
            <div class="row">
                <div class="col-md-6">
                    <!-- "transactions.data" mengambil dari computed mapState -->
                    <p v-if="transactions.data">
                        <i class="fa fa-list-ul"></i> 
                        <!-- meta berasal dari backend, bagian pagination -->
                        {{ transactions.data.length }} item dari {{ transactions.meta.total }} total data
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <b-pagination
                        v-model="page"
                        :total-rows="transactions.meta.total"
                        :per-page="transactions.meta.per_page"
                        aria-controls="transactions"
                        v-if="transactions.data && transactions.data.length > 0"
                        ></b-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
import moment from 'moment'
export default {
    name: 'DataTransaction',
    created(){
        this.getTransactions({
            search: this.search,
            isPaid: this.filter_isPaid
        })
    },
    data() {
        return {
            fieldss: [
                { key: 'index', label: 'No' },
                // { key: 'id', label: 'Order Id', sortable: true },
                { key: 'customer', label: 'Customer', sortable: true },
                { key: 'user_id', label: 'Admin', sortable: true },
                { key: 'service', label: 'Item Jasa'},
                { key: 'amount', label: 'Total', sortable: true },
                { key: 'created_at', label: 'Tanggal Transaksi', sortable: true },
                { key: 'isPaid', label: 'Status' },
                { key: 'actions', label: 'Aksi' } 
            ],
            transProps: { name: 'flip-list' }, //nama transisi. memanggil class css di style.css
            search: '',
            filter_isPaid: 2 //default di set 2. ambil semua data entah itu sudah dibayar atau belum
        }
    },
    computed: {
        ...mapState('transaction', {
            transactions: state => state.transactions
        }),
        //ambil dan set data page di transaction.js. data ini akan diambil oleh html v-model="page"
        page: {
            get(){
                //mengambil value page di state customer.js
                return this.$store.state.transaction.page //$store.state.namamodul.namastate
            },
            set(val){
                //set page
                this.$store.commit('transaction/SET_PAGE', val)
            }
        }
    },
    watch: {
        //jika page di computed() berubah, jalankan getTransactions()
        page(){
            this.getTransactions({
                search: this.search,
                isPaid: this.filter_isPaid
            })
        },
        //jika value search di data() berubah
        search(){
            this.getTransactions({
                search: this.search,
                isPaid: this.filter_isPaid
            })
        },
        //jika filter_isPaid di data() berubah
        filter_isPaid(){
            this.getTransactions({
                search: this.search,
                isPaid: this.filter_isPaid
            })
        }
    },
    methods: {
        ...mapActions('transaction', ['getTransactions'])
    },
    filters: {
        //nilai ini akan dikirmkan ke html yg memuat method formatDate
        formatDate(val){
            //memformat tanggal sesuai tanggal indonesia
            return moment(new Date(val)).format('DD-MM-YYYY hh:mm')
        }
    }
}
</script>
