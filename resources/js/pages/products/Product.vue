<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <router-link :to="{ name: 'product.add' }" class="btn btn-primary btn-sm">Tambah</router-link>
            </div>
            <div class="p-2">
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>

        <div>
            <!-- :items="products.data" mengambil dari computed mapState -->
            <!-- items berisi data user yg akan di render ke table, sedangakn fields akan menjadi nama header pd table -->
            <b-table striped hover :items="products.data" :fields="fieldss">
                <!-- template hanyalah optional saja untuk menginginkan tampilan sesuai yg diharapkan -->
                <!-- :fields="fields" menampilkan label, sedangkan v-slot:cell(type)="row" menampilkan key  -->
                <template v-slot:cell(type)="row">
                    {{ row.item.type[0].name }}
                </template>
                <template v-slot:cell(size)="row">
                    {{ row.item.type[0].pivot.size }}
                </template>
                <template v-slot:cell(price)="row">
                    {{ row.item.price | currency }}
                </template>
                <template v-slot:cell(user_id)="row">
                    <!-- row.item harusnya kan menampilkan field product, namun utk code ini karena backend mengirimkan field user, shg outlet bisa diambil. di backend product memiliki relasi ke user-->
                    {{row.item.user.name}}
                </template>
                <template v-slot:cell(actions)="row">
                    <!-- utk edit pakai a href sesuai link dari router yg diberikan -->
                    <router-link :to="{ name: 'product.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></router-link>
                    <!-- utk hapus pakai button -->
                    <button class="btn btn-danger btn-sm" @click="deleteProduct(row.item.id)"><i class="fa fa-trash"></i></button>
                </template>
            </b-table>
            <!-- pagination -->
            <div class="row">
                <div class="col-md-6">
                    <!-- "products.data" mengambil dari computed mapState -->
                    <p v-if="products.data">
                        <i class="fa fa-list-ul"></i> 
                        <!-- meta berasal dari backend, bagian pagination -->
                        {{ products.data.length }} item dari {{ products.meta.total }} total data
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <b-pagination
                        v-model="page"
                        :total-rows="products.meta.total"
                        :per-page="products.meta.per_page"
                        aria-controls="products"
                        v-if="products.data && products.data.length > 0"
                        ></b-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {mapActions, mapState} from 'vuex'
export default {
    name: 'DataProduct',
    created() {
        //SEBELUM COMPONENT DI-LOAD, REQUEST DATA DARI SERVER
        // this.$store.dispatch('product/getProducts') //jika tidak pakai helper mapActions, pakainya ini
        // fungsi ini didapat dari methods mapActions('product', ['getProducts', 'removeProducts'])
        this.getProducts()
    },
    data() {
        return {
            fieldss: [
                { key: 'name', label: 'Nama Produk'},
                { key: 'type', label: 'Tipe'},
                { key: 'size', label: 'Size'},
                { key: 'description', label: 'Deskripsi'},
                { key: 'price', label: 'Harga'},
                { key: 'user_id', label: 'Admin'},
                { key: 'actions', label: 'Aksi'}
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('product', { //memanggil state products di product.js untuk menampilkan list products
            products: state => state.products
        }),
        page: {//code page yg ini untuk mengubah nilai page yg diiinputkan oleh user
            get() {
                //mengambil value page di state product.js
                return this.$store.state.product.page //$store.state.namamodul.namastate
            },
            set(val) {
                //mengubah value page di mutation product.js apabila ada perubahan page di state product.js
                this.$store.commit('product/SET_PAGE', val)
            }
        }
    },
    watch: {
        page () { //code page yg ini untuk menampilkan ke user
            //jika value berubah maka akan meminta data dari server
            this.getProducts()
        },
        search() {
            this.getProducts(this.search)
        }
    },
    methods: {
        ...mapActions('product', ['getProducts', 'removeProduct']),

        //ketika tombol hapus di klik, akan menjalankan perintah berikut
        deleteProduct(id){
            //menampilkan jendela konfirmasi
            this.$swal({
                title: 'Kamu Yakin?',
                text: "Tindakan ini akan menghapus secara permanent!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                //jika disetujui akan ada value: true. jika di CANCEL akan menghasilkan dismiss: "cancel
                if(result.value){
                    //this.$store.dispatch('outlet/removeProduct', id) //jika tidak pakai helper mapActions, pakainya ini. dispatch berarti mengakses ke action
                    this.removeProduct(id)
                }
            })
        }
    }
}
</script>
