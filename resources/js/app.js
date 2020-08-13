import Vue from 'vue'
import router from './router.js'
import store from './store.js'
import App from './App.vue'

import BootstrapVue from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import VueSweetalert2 from 'vue-sweetalert2'
import VueCurrencyFilter from 'vue-currency-filter' //format mata uang

import { mapGetters, mapActions, mapState } from 'vuex'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

Vue.use(VueSweetalert2)
Vue.use(BootstrapVue)
Vue.use(VueCurrencyFilter, {
        symbol : 'Rp',
        thousandsSeparator: '.',
        fractionCount: 0,
        fractionSeparator: ',',
        symbolPosition: 'front',
        symbolSpacing: true
    })

new Vue({
    el: '#appku',
    router,
    store,
    components: {
        App
    },
    computed: {
        ...mapGetters(['isAuth']),

        //berikut unutk pusher
        ...mapState(['token']), //get token untuk disubmit ke headers dan utk watch
        ...mapState('user', {
            authenticated: state => state.authenticated //mengambil state user yg sedang login
        })
    },
    methods: {
        ...mapActions('user', ['getUserLogin']),
        ...mapActions('notification', ['getNotifications']), //mengambil notifikasi dari table notifikasi
        ...mapActions('expense', ['getExpenses']),
        //untuk menginisiasi lister menggunakan laravel echo
        initialLister(){
            //jika user sudah login
            if(this.isAuth){
                //inisiasi fungsi broadcaster
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: process.env.MIX_PUSHER_APP_KEY,
                    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                    encrypted: false,
                    auth: {
                        headers: {
                            Authorization: 'Bearer ' + this.token
                        }
                    }
                });
                if(typeof this.authenticated.id != 'undefined'){
                    //mengakses channel broadcast secara private
                    window.Echo.private(`App.User.${this.authenticated.id}`) //channel yang dapat ditemukan pada file routes/channel.php, fungsinya untuk memastikan bahwa user yang login benar karena kita menggunakan private channel dimana hanya user yang memiliki akses yang dapat mengambil data broadcast
                    .notification(() => {
                        //apabila ditemukan, maka akan menjalankan fungsi berikut
                        this.getNotifications()
                        this.getExpenses()
                    })
                }
            }
        }
    },
    watch: {
        //ketika token berubah
        token(){
            //jalankan fungsi ini untuk inisialisasi lagi
            this.initialLister()
        },
        //ketika value user yg sedang login berubah
        authenticated(){
            //jalankan fungsi ini untuk inisialisasi lagi
            this.initialLister()
        }
    },
    created(){ //ketika file ini dibuka langsung mengecek user login atau enggak
        if(this.isAuth){ 
            this.getUserLogin() //jika user login, maka ambil data user yg sedang login
            this.initialLister()
            this.getNotifications()
        }
    }
})
