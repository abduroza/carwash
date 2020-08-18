<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <router-link :to="{ name: 'operator.add' }" class="btn btn-primary btn-sm">Tambah</router-link>
            </div>
            <div class="p-2">
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>

        <div>
            <!-- :items="operators.data" mengambil dari computed mapState -->
            <!-- items berisi data user yg akan di render ke table, sedangakn fields akan menjadi nama header pd table -->
            <b-table striped hover :items="operators.data" :fields="fieldss">
                <!-- template hanyalah optional saja untuk menginginkan tampilan sesuai yg diharapkan -->
                <!-- :fields="fields" menampilkan label, sedangkan v-slot:cell(photo)="row" menampilkan key  -->
                <template v-slot:cell(photo)="row">
                    <div v-if="row.item.photo">
                        <img :src="'/images/' + row.item.photo" :width="50" :height="50" :alt="row.item.name">
                    </div>
                    <div v-else>
                        <img :src="'/img/' + 'avatarDefault.png'" :width="50" :height="50" :alt="row.item.name">
                    </div>
                </template>
                <template v-slot:cell(outlet_id)="row">
                    <!-- row.item harusnya kan menampilkan field user, namun utk code ini karena backend mengirimkan field outlet, shg outlet bisa diambil. di backend user memiliki relasi ke outlet-->
                    <div v-if="row.item.outlet">
                        {{row.item.outlet.name}}
                    </div>
                </template>
                <template v-slot:cell(actions)="row">
                    <!-- utk edit pakai a href sesuai link dari router yg diberikan -->
                    <router-link :to="{ name: 'operator.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Edit operator"><i class="fa fa-pencil"></i></router-link>
                    <!-- utk hapus pakai button -->
                    <button class="btn btn-danger btn-sm" @click="deleteOperator(row.item.id)" data-toggle="tooltip" data-placement="top" title="Hapus operator"><i class="fa fa-trash"></i></button>
                </template>
            </b-table>
            <!-- pagination -->
            <div class="row">
                <div class="col-md-6">
                    <!-- "operators.data" mengambil dari computed mapState -->
                    <p v-if="operators.data">
                        <i class="fa fa-list-ul"></i> 
                        {{ operators.data.length }} item dari {{ operators.meta.total }} total data
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <b-pagination
                        v-model="page"
                        :total-rows="operators.meta.total"
                        :per-page="operators.meta.per_page"
                        aria-controls="operators"
                        v-if="operators.data && operators.data.length > 0"
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
    name: 'DataOperator',
    created() {
        //SEBELUM COMPONENT DI-LOAD, REQUEST DATA DARI SERVER
        // this.$store.dispatch('operators/getOperators') //jika tidak pakai helper mapActions, pakainya ini
        // fungsi ini didapat dari methods mapActions('operator', ['getOperators', 'removeOperator'])
        this.getOperators()
    },
    data(){
        return {
            fieldss: [
                { key: 'photo', label: '#' },
                { key: 'name', label: 'Nama Operator', sortable: true },
                { key: 'email', label: 'Email' },
                { key: 'outlet_id', label: 'Outlet', sortable: false },
                { key: 'actions', label: 'Aksi' }
            ],
            search: ''
        }
    },
    computed: {
        ...mapState(['success']),
        ...mapState('operator', {
            operators: state => state.operators
        }),
        page: { //code page yg ini untuk mengubah nilai page yg diiinputkan oleh user
            get(){
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE user.js
                return this.$store.state.operator.page //$store.state.namamodul.namastate
            },
            set(val){
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('operator/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() { //code page yg ini untuk menampilkan ke user
            //APABILA VALUE DARI PAGE BERUBAH, MAKA AKAN MEMINTA DATA DARI SERVER
            this.getOperators()
        },
        search() {
            //APABILA VALUE DARI SEARCH BERUBAH MAKA AKAN MEMINTA DATA SESUAI DENGAN DATA YANG SEDANG DICARI
            this.getOperators(this.search)
        }
    },
    mounted(){
        this.makeToast('success') //menangkap notif success dari add dan update
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE operator. ini sebagai ganti this.$store.dispatch
        ...mapActions('operator', ['getOperators', 'removeOperator']),
        makeToast(variantt = null) {
            if(this.success != null){
                this.$bvToast.toast(this.success.message, {
                    title: this.success.status,
                    variant: variantt,
                    solid: true,
                    autoHideDelay: 7000,
                })
            }
        },
        //KETIKA TOMBOL HAPUS DICLICK, MAKA AKAN MENJALANKAN METHOD INI
        deleteOperator(id){
            //AKAN MENAMPILKAN JENDELA KONFIRMASI
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
                //JIKA DISETUJUI. akan ada value: true. jika di CANCEL akan menghasilkan dismiss: "cancel
                if(result.value){
                    //MAKA FUNGSI removeOutlet AKAN DIJALANKAN
                    //this.$store.dispatch('user/removeOperator', id) //jika tidak pakai helper mapActions, pakainya ini. dispatch berarti mengakses ke action
                    this.removeOperator(id).then(() => this.makeToast('warning'))
                }
            })
        }
    },
    destroyed(){
        //menghapus state success di store.js saat form ini ditutup
        this.$store.commit('SET_SUCCESS', null) //mengakses mutations di root module
    }
}
</script>
