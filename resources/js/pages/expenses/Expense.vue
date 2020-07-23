<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <router-link :to="{ name: 'expense.add' }" class="btn btn-primary btn-sm btn-flat">Tambah</router-link>
            </div>
            <div class="p-2">
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>

        <div>
            <!-- :items="expenses.data" mengambil dari computed mapState -->
            <!-- items berisi data outlet yg akan di render ke table, sedangakn fields yg akan menjadi nama header pd table -->
            <b-table striped hover :items="expenses.data" :fields="fieldss">
                <!-- template hanyalah optional saja untuk menginginkan tampilan sesuai yg diharapkan -->
                <template v-slot:cell(status)="row">
                    <!-- template status hanya untuk menampilkan tulian proses, diterima, ditolak. jika tidak diberi template akan menampilkan nilai sesuai field yg dikirimkan yaitu 0, 1 atau 2 -->
                    <span class="badge badge-warning" v-if="row.item.status == 0">Diproses</span>
                    <span class="badge badge-success" v-else-if="row.item.status == 1">Diterima</span>
                    <span class="badge badge-secondary" v-else>Ditolak</span>
                </template>
                <template v-slot:cell(user)="row">
                    {{ row.item.user.name }}
                </template>
                <!-- cell(actions). actions sesuai key  -->
                <template v-slot:cell(actions)="row">
                    <!-- utk edit pakai a href sesuai link dari router yg diberikan -->
                    <router-link :to="{ name: 'expense.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm" v-if="row.item.status == 0" data-toggle="tooltip" data-placement="top" title="Edit expense"><i class="fa fa-pencil"></i></router-link>
                    <router-link :to="{ name: 'expense.view', params: {id: row.item.id} }" class="btn btn-info btn-sm"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="View expense"></i></router-link>
                    <!-- utk hapus pakai button -->
                    <button class="btn btn-danger btn-sm" @click="deleteExpense(row.item.id)" v-if="row.item.status == 0" data-toggle="tooltip" data-placement="top" title="Hapus expense"><i class="fa fa-trash"></i></button>
                </template>
            </b-table>
            <!-- pagination -->
            <div class="row">
                <div class="col-md-6">
                    <!-- v-if outlets.meta.total supaya ketika meload API yg tidak dipagination tidak ada error -->
                    <p v-if="expenses.data && expenses.meta.total">
                        <i class="fa fa-list-ul"></i> 
                        {{ expenses.data.length }} item dari {{ expenses.meta.total }} total data
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <b-pagination
                        v-model="page"
                        :total-rows="expenses.meta.total"
                        :per-page="expenses.meta.per_page"
                        aria-controls="expenses"
                        v-if="expenses.data && expenses.data.length && expenses.meta.total> 0"
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
    name: 'DataExpense',
    created(){
        this.getExpenses()
    },
    data() {
        return {
            fieldss: [
                { key: 'title', label: 'Permintaan'},
                { key: 'note', label: 'Catatan'},
                { key: 'price', label: 'Biaya'},
                { key: 'user', label: 'Operator/Admin'},
                { key: 'status', label: 'Status'},
                { key: 'reason', label: 'Alasan'},
                { key: 'actions', label: 'Aksi'},
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('expense', {
            expenses: state => state.expenses
        }),
        page: {
            get(){
                //mengambil value page di state expense.js tanpa menggunakan mapState
                return this.$store.state.expense.page
            },
            set(val){
                //mengubah value SET_PAGE di muatation expense.js tanpa menggunakan mapMutation
                this.$store.commit('expense/SET_PAGE', val)
            }
        }
    },
    watch: {
        //jika value page berubah langsung memanggil getExpense
        page() {
            this.getExpenses()
        },
        //jika kolom search ada yg diinputkan, langsung panggil getExpenses dg memberi value search
        search() {
            this.getExpenses(this.search)
        }
    },
    methods: {
        ...mapActions('expense', ['getExpenses', 'removeExpense']),
        deleteExpense(id){
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
                //JIKA DISETUJUI. akan ada value: true. jika di CANCEL akan menghasilkan dismiss: "cancel
                if(result.value){
                    //MAKA FUNGSI removeOutlet AKAN DIJALANKAN
                    // this.$store.dispatch('expense/removeExpense', id) //jika tidak pakai helper mapActions, pakainya ini. dispatch berarti mengakses ke action
                    this.removeExpense(id)
                }
            })
        }
    }

}
</script>
