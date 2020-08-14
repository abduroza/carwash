<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <router-link :to="{ name: 'user.add' }" class="btn btn-primary btn-sm">Tambah</router-link>
            </div>
            <div class="p-2">
                <input type="text" class="form-control" placeholder="Cari..." v-model="search">
            </div>
        </div>

        <div>
            <!-- :items="users.data" mengambil dari computed mapState -->
            <!-- items berisi data user yg akan di render ke table, sedangakn fields akan menjadi nama header pd table -->
            <b-table striped hover :items="users.data" :fields="fieldss">
                <!-- template hanyalah optional saja untuk menginginkan tampilan sesuai yg diharapkan -->
                <!-- :fields="fields" menampilkan label, sedangkan v-slot:cell(photo)="row" menampilkan key  -->
                <template v-slot:cell(photo)="row">
                    <div v-if="row.item.photo">
                        <img :src="'/storage/users/' + row.item.photo" :width="50" :height="50" :alt="row.item.name">
                    </div>
                    <div v-else>
                        <img :src="'/images/' + 'avatarDefault.png'" :width="50" :height="50" :alt="row.item.name">
                    </div>
                </template>
                <template v-slot:cell(role)="row">
                    <span class="badge badge-success" v-if="row.item.role == 0">Superadmin</span>
                    <span class="badge badge-primary" v-else-if="row.item.role == 1">Admin</span>
                    <span class="badge badge-danger" v-else-if="row.item.role == 2">Finance</span>
                </template>
                <template v-slot:cell(outlet_id)="row">
                    <!-- row.item harusnya kan menampilkan field user, namun utk code ini karena backend mengirimkan field outlet, shg outlet bisa diambil. di backend user memiliki relasi ke outlet-->
                    <div v-if="row.item.outlet">
                        {{row.item.outlet.name}}
                    </div>
                </template>
                <template v-slot:cell(actions)="row">
                    <!-- utk edit pakai a href sesuai link dari router yg diberikan -->
                    <router-link :to="{ name: 'user.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Edit user"><i class="fa fa-pencil"></i></router-link>
                    <!-- utk hapus pakai button -->
                    <button class="btn btn-danger btn-sm" @click="deleteUser(row.item.id)" data-toggle="tooltip" data-placement="top" title="Hapus user"><i class="fa fa-trash"></i></button>
                </template>
            </b-table>
            <!-- pagination -->
            <div class="row">
                <div class="col-md-6">
                    <!-- "users.data" mengambil dari computed mapState -->
                    <p v-if="users.data">
                        <i class="fa fa-list-ul"></i> 
                        {{ users.data.length }} item dari {{ users.meta.total }} total data
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <b-pagination
                        v-model="page"
                        :total-rows="users.meta.total"
                        :per-page="users.meta.per_page"
                        aria-controls="users"
                        v-if="users.data && users.data.length > 0"
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
    name: 'DataUser',
    created() {
        //SEBELUM COMPONENT DI-LOAD, REQUEST DATA DARI SERVER
        // this.$store.dispatch('user/getUsers') //jika tidak pakai helper mapActions, pakainya ini
        // fungsi ini didapat dari methods mapActions('user', ['getUsers', 'removeUser'])
        this.getUsers()
    },
    data(){
        return {
            fieldss: [
                { key: 'photo', label: '#' },
                { key: 'name', label: 'Nama User', sortable: true },
                { key: 'email', label: 'Email' },
                { key: 'role', label: 'Role', sortable: true },
                { key: 'outlet_id', label: 'Outlet' },
                { key: 'actions', label: 'Aksi' }
            ],
            search: ''
        }
    },
    computed: {
        ...mapState(['success']),
        ...mapState('user', {
            users: state => state.users
        }),
        page: { //code page yg ini untuk mengubah nilai page yg diiinputkan oleh user
            get(){
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE user.js
                return this.$store.state.user.page //$store.state.namamodul.namastate
            },
            set(val){
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('user/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() { //code page yg ini untuk menampilkan ke user
            //APABILA VALUE DARI PAGE BERUBAH, MAKA AKAN MEMINTA DATA DARI SERVER
            this.getUsers()
        },
        search() {
            //APABILA VALUE DARI SEARCH BERUBAH MAKA AKAN MEMINTA DATA SESUAI DENGAN DATA YANG SEDANG DICARI
            this.getUsers(this.search)
        }
    },
    mounted(){
        this.makeToast('success') //menangkap notif success dari add dan update
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE operator. ini sebagai ganti this.$store.dispatch
        ...mapActions('user', ['getUsers', 'removeUser']),
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
        deleteUser(id){
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
                    //this.$store.dispatch('user/removeUser', id) //jika tidak pakai helper mapActions, pakainya ini. dispatch berarti mengakses ke action
                    this.removeUser(id).then(() => this.makeToast('warning'))
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
