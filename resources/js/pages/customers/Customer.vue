<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <router-link :to="{ name: 'customer.add' }" class="btn btn-primary btn-sm">Tambah</router-link>
            </div>
            <div class="p-2">
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>

        <div>
            <!-- :items="products.data" mengambil dari computed mapState -->
            <!-- items berisi data user yg akan di render ke table, sedangakn fields akan menjadi nama header pd table -->
            <b-table striped hover :items="customers.data" :fields="fieldss">
                <!-- template hanyalah optional saja untuk menginginkan tampilan sesuai yg diharapkan -->
                <!-- :fields="fields" menampilkan label, sedangkan v-slot:cell(type)="row" menampilkan key  -->
                <!-- row.item harusnya kan menampilkan field product, namun utk code ini karena backend mengirimkan field user, shg outlet bisa diambil. di backend product memiliki relasi ke user-->
                <template v-slot:cell(amount)="row">
                    {{ row.item.deposite.amount | currency }}
                </template>
                <template v-slot:cell(actions)="row">
                    <!-- utk edit pakai a href sesuai link dari router yg diberikan -->
                    <router-link :to="{ name: 'customer.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Edit Customer"><i class="fa fa-pencil"></i></router-link>
                    <!-- utk hapus pakai button -->
                    <button class="btn btn-danger btn-sm" @click="deleteCustomer(row.item.id)" data-toggle="tooltip" data-placement="top" title="Hapus customer"><i class="fa fa-trash"></i></button>
                </template>
            </b-table>
            <!-- pagination -->
            <div class="row">
                <div class="col-md-6">
                    <!-- "customers.data" mengambil dari computed mapState -->
                    <p v-if="customers.data">
                        <i class="fa fa-list-ul"></i> 
                        <!-- meta berasal dari backend, bagian pagination -->
                        {{ customers.data.length }} item dari {{ customers.meta.total }} total data
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <b-pagination
                        v-model="page"
                        :total-rows="customers.meta.total"
                        :per-page="customers.meta.per_page"
                        aria-controls="customers"
                        v-if="customers.data && customers.data.length > 0"
                        ></b-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
export default {
    name: 'DataCustomer',
    created() {
        this.getCustomers()
    },
    data(){
        return {
            fieldss: [
                { key: 'nik', label: 'NIK' },
                { key: 'name', label: 'Nama Customer', sortable: true },
                { key: 'point', label: 'Poin', sortable: true },
                { key: 'amount', label: 'Deposit', sortable: false },
                { key: 'phone', label: 'Phone' },
                { key: 'address', label: 'Alamat', tdClass: 'maxWidth' }, //tdClass utk memberi class sesuai yg diinginkan
                { key: 'actions', label: 'Aksi' },
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('customer', {
            customers: state => state.customers
        }),
        page: {
            get(){
                return this.$store.state.customer.page
            },
            set(val){
                this.$store.commit('customer/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() {
            this.getCustomers()
        },
        search() {
            this.getCustomers(this.search)
        }
    },
    methods: {
        ...mapActions('customer', ['getCustomers', 'removeCustomer']),
        deleteCustomer(id){
            this.$swal({
                title: 'Yakin Mau Dihapus?',
                text: "Tindakan ini akan menghapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if(result.value){
                    this.removeCustomer(id)
                }
            })
        }
    }
}
</script>
