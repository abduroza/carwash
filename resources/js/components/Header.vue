<template>
<nav class="navbar navbar-expand-lg navbar-dark bg-info">
    <div class="container-fluid px-5">
        <a class="navbar-brand" href="#"><strong>CAR</strong>Wash</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav mr-auto">
                <!-- <ul class="nav nav-tabs "> -->
                    <li class="nav-item" :class="{ 'active' : $route.path == '/'}">
                        <router-link class="nav-link" to="/">Home <span class="sr-only">(current)</span></router-link>
                    </li>
                    <li class="nav-item" :class="{ 'active' : $route.path == '/outlets/'}">
                        <router-link class="nav-link" :to="{ name: 'outlets.data' }">Outlets</router-link>
                    </li>
                    <li class="nav-item" :class="{ 'active' : $route.path == '/operator/'}">
                        <router-link class="nav-link" :to="{ name: 'operators.data' }">Operators</router-link>
                    </li>
                    <li class="nav-item" :class="{ 'active' : $route.path == '/user/'}">
                        <router-link class="nav-link" :to="{ name: 'users.data' }">Users</router-link>
                    </li>
                    <li class="nav-item" :class="{ 'active' : $route.path == '/product/'}">
                        <router-link class="nav-link" :to="{ name: 'products.data' }">Products</router-link>
                    </li>
                    <li class="nav-item" :class="{ 'active' : $route.path == '/expense/'}">
                        <router-link class="nav-link" :to="{ name: 'expenses.data' }">Expenses</router-link>
                    </li>
                    <li class="nav-item" :class="{ 'active' : $route.path == '/customer/'}">
                        <router-link class="nav-link" :to="{ name: 'customers.data' }">Customers</router-link>
                    </li>
                    <li class="nav-item dropdown" :class="{ 'active' : $route.path == '/transaction/'}">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Transactions
                        </a>
                        <div class="dropdown-menu dropdown-menu-left">
                            <router-link class="dropdown-item" :to="{ name: 'transactions.data' }">List</router-link>
                            <router-link class="dropdown-item" :to="{ name: 'transaction.add' }">Add New</router-link>
                        </div>
                    </li>
                <!-- </ul> -->
            </ul>

            <ul class="navbar-nav flex-row ml-auto d-flex">
                <!-- start dropdown notifikasi -->
                <li class="nav-item dropdown mx-3">
                    <a class="nav-link dropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell-o d-inline-flex">
                            <small class="">
                                <!-- menghitung jumlah notifikasi yg ada -->
                                <sup class="d-inline-flex m-n2 badge badge-success">{{ notifications.length }}</sup>
                            </small>
                        </i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right clearfix bg-info" style="width: 250px;">
                        <li class="m-1 d-flex justify-content-start">
                            <small class="text-white">You have {{ notifications.length }} messages</small>
                        </li>
                        <div style="overflow-y: scroll; max-height: 230px;">
                            <b-list-group style="" v-if="notifications.length > 0">
                                <b-list-group-item v-for="(row, index) in notifications" :key="index" class="p-1">
                                    <a href="javascript:void(0)" @click="readNotif(row)" class="text-decoration-none text-dark">
                                        <div class="pull-left">
                                            <!-- menampilkan foto pengirim -->
                                            <i v-if="row.data.sender_photo">
                                                <img :src="'/storage/users/' + row.data.sender_photo" :width="50" :height="50" :alt="row.data.sender_name" class="rounded-circle">
                                            </i>
                                            <i v-else>
                                                <img :src="'/storage/users/' + 'avatarDefault.png'" :width="50" :height="50" :alt="row.data.sender_name">
                                            </i>
                                        </div>
                                        <div>
                                            <h6 class="my-0 text-info">
                                                <!-- menampilkan nama pengirim -->
                                                {{ row.data.sender_name }}
                                            </h6>
                                            <!-- menampilkan waktu pesan dikirim dari sekarang -->
                                            <small class="text-secondary"><i class="fa fa-clock-o"></i> {{ row.created_at | formatDate }} </small>
                                            <p class="my-0" >{{ row.data.expense.title.substr(0, 30) }}</p>
                                        </div>
                                    </a>
                                </b-list-group-item >
                            </b-list-group>
                        </div>
                    </div>
                </li>
                <!-- end dropdown notifikasi -->
                <!-- start dropdown user -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span v-if="authenticated.photo">
                            <img :src="'/storage/users/' + authenticated.photo" :width="23" :height="23" :alt="authenticated.name" class="rounded-circle">
                        </span>
                        <span v-else>
                            <img :src="'/storage/users/' + 'avatarDefault.png'" :width="23" :height="23" :alt="authenticated.name">
                        </span>
                        <span class="hidden-xs">{{ authenticated.name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right clearfix" style="width: 230px;" aria-labelledby="navbarDropdown">
                        <div class="px-2 mt-n2 bg-info d-flex justify-content-center">
                            <div class="mt-2">
                                <h6 class="d-flex justify-content-center">
                                    <i v-if="authenticated.photo">
                                        <img :src="'/storage/users/' + authenticated.photo" :width="80" :height="80" :alt="authenticated.name" class="rounded-circle">
                                    </i>
                                    <i v-else>
                                        <img :src="'/storage/users/' + 'avatarDefault.png'" :width="80" :height="80" :alt="authenticated.name">
                                    </i>
                                </h6>
                                <h6 class="text-center text-white">
                                    {{ authenticated.name }}
                                </h6>
                            </div>
                        </div>
                        <div class="dropdown-divider mt-0"></div>
                        <div class="px-2">
                            <a class="btn btn-outline-info btn-sm float-left" href="#">Profile</a>
                            <a class="btn btn-outline-info btn-sm float-right" href="javascript:void(0)" @click="logout" >Logout</a>
                        </div>
                    </div>
                </li>
                <!-- start end dropdown user -->
            </ul>
        </div>
    </div>
</nav>
</template>

<script>
import {mapState, mapActions} from 'vuex'
import moment from 'moment'
export default {
    computed: {
        ...mapState('user', {
            authenticated: state => state.authenticated
        }),
        ...mapState('notification', {
            notifications: state => state.notifications
        }),
    },
    filters: {
        formatDate(val){
            //mengubah waktu menjadi time ago
            return moment(new Date(val)).fromNow()
        }
    },
    methods: {
        ...mapActions('notification', ['readNotification']),
        readNotif(row){
            this.readNotification({ id: row.id })
            .then(() => this.$router.push({ name: 'expense.view', params: { id: row.data.expense.id }}))
        },
        logout() {
            return new Promise((resolve, reject) => {
                localStorage.removeItem('token') //MENGHAPUS TOKEN DARI LOCALSTORAGE
                resolve()
            }).then(()=> {
                //Jika berhasil MEMPERBAHARUI STATE TOKEN
                this.$store.state.token = localStorage.getItem('token') //mengakses store.js untuk mengganti  token local storage menjadi sesuai dg localstorage browser(saat logout) yaitu value ''
                this.$router.push('/login') //redirect ke page login
            })
        }
    }
}
</script>
