<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <router-link :to="{ name: 'outlets.add' }" class="btn btn-primary btn-sm btn-flat">Tambah</router-link>
            </div>
            <div class="p-2">
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>

        <div>
            <!-- :items="outlets.data" mengambil dari computed mapState -->
            <!-- items berisi data outlet yg akan di render ke table, sedangakn fields yg akan menjadi nama header pd table -->
            <b-table striped hover :items="outlets.data" :fields="fieldss">
                <!-- template hanyalah optional saja untuk menginginkan tampilan sesuai yg diharapkan -->
                <template v-slot:cell(status)="row">
                    <!-- template status hanya untuk menampilkan tulian active atau inactive. jika tidak diberi template akan menampilkan nilai sesuai field yg dikirimkan yaitu 1 atau 0 -->
                    <span class="badge badge-success" v-if="row.item.status == 1">Active</span>
                    <span class="badge badge-secondary" v-else>Inactive</span>
                </template>
                <!-- cell(actions). actions sesuai key  -->
                <template v-slot:cell(actions)="row">
                    <!-- utk edit pakai a href sesuai link dari router yg diberikan -->
                    <router-link :to="{ name: 'outlets.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Edit outlet"><i class="fa fa-pencil"></i></router-link>
                    <!-- utk hapus pakai button -->
                    <button class="btn btn-danger btn-sm" @click="deleteOutlet(row.item.id)" data-toggle="tooltip" data-placement="top" title="Hapus outlet"><i class="fa fa-trash"></i></button>
                </template>
            </b-table>
            <!-- pagination -->
            <div class="row">
                <div class="col-md-6">
                    <!-- v-if outlets.meta.total supaya ketika meload API yg tidak dipagination tidak ada error -->
                    <p v-if="outlets.data && outlets.meta.total">
                        <i class="fa fa-list-ul"></i> 
                        {{ outlets.data.length }} item dari {{ outlets.meta.total }} total data
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <b-pagination
                        v-model="page"
                        :total-rows="outlets.meta.total"
                        :per-page="outlets.meta.per_page"
                        aria-controls="outlets"
                        v-if="outlets.data && outlets.data.length && outlets.meta.total> 0"
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
    name: 'DataOutlet',
    created(){
        //SEBELUM COMPONENT DI-LOAD, REQUEST DATA DARI SERVER
        // this.$store.dispatch('outlet/getOutlets') //jika tidak pakai helper mapActions, pakainya ini
        // fungsi ini didapat dari methods mapActions('outlet', ['getOutlets', 'removeOutlets'])
        this.getOutlets()
    },
    data() {
        return {
            fieldss: [
                //FIELD UNTUK B-TABLE, PASTIKAN KEY NYA SESUAI DENGAN FIELD DATABASE AGAR OTOMATIS DI-RENDER
                {key: 'code', label: 'Kode Outlet'},
                {key: 'name', label: 'Nama Outlet'},
                {key: 'address', label: 'Alamat'},
                {key: 'phone', label: 'No. Telp'},
                {key: 'status', label: 'Status'},
                {key: 'actions', label: 'Aksi'},
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('outlet', {
            outlets: state => state.outlets
        }),
        page: { //code page yg ini untuk mengubah nilai page yg diiinputkan oleh user
            get(){
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet.js
                return this.$store.state.outlet.page //$store.state.namamodul.namastate
            },
            set(val){
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('outlet/SET_PAGE', val)
            }
        },
    },
    watch: {
        page(){ //code page yg ini untuk menampilkan ke user
            //APABILA VALUE DARI PAGE BERUBAH, MAKA AKAN MEMINTA DATA DARI SERVER
            this.getOutlets()
        },
        search() {
            //APABILA VALUE DARI SEARCH BERUBAH MAKA AKAN MEMINTA DATA SESUAI DENGAN DATA YANG SEDANG DICARI
            this.getOutlets(this.search)
        }
    },
    methods: { //kenapa methods bukan actions
        //MENGAMBIL FUNGSI DARI VUEX MODULE outlet. ini sebagai ganti this.$store.dispatch
        ...mapActions('outlet', ['getOutlets', 'removeOutlet']),

        //KETIKA TOMBOL HAPUS DICLICK, MAKA AKAN MENJALANKAN METHOD INI
        deleteOutlet(id){
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
                    // this.$store.dispatch('outlet/removeOutlet', id) //jika tidak pakai helper mapActions, pakainya ini. dispatch berarti mengakses ke action
                    this.removeOutlet(id)
                }
            })
        }
    }

}

</script>
<!-- Custom tag <b-table> merupakan component yang telah disediakan oleh Bootstrap Vue. Dimana component ini membutuhkan dua props yakni items dan fields. Items berisi data outlets yang akan di-render ke dalam table, sedangkan fields akan menjadi header dari table tersebut. Karena response yang akan didapatkan dari database untuk status hanyalah angka 0 dan 1, maka kita membuat custom rendering dari component b-table menggunakan tag <template>. Adapun pagination akan ditampilkan apabila total data lebih besar dari 0.-->
