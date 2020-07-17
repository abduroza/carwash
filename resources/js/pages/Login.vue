<!-- HTML SECTION -->
<template>
<div class="jumbotron text-center">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-header">
                        <h5 class="text-center">
                            <span class="font-weight-bold text-primary">LOGIN</span>
                        </h5>
                    </div>
                    <div class="card-body ">
                        <form action="">
                            <div class="form-group has-feedback" :class="{'has-error': errors.email}">
                                <input type="email" class="form-control" placeholder="Email" v-model="data.email">
                                <p class="text-danger pull-left" v-if="errors.email">{{ errors.email[0] }}</p>
                            </div>
                            <div class="form-group has-feedback" :class="{'has-error': errors.password}">
                                <input type="password" class="form-control" placeholder="Password" v-model="data.password">
                                <p class="text-danger pull-left" v-if="errors.password">{{ errors.password[0] }}</p>
                                <!-- <div class="pull-right">
                                    <small><a href="#">Lupa password?</a></small>
                                </div> -->
                            </div>
                            <div class="alert alert-danger" role="alert" v-if="errors.invalid">   
                                <small>{{ errors.invalid }}.<a href="#" class="alert-link"> Lupa password?</a></small>
                            </div>
                            <div class="pull-left">
                                <input type="checkbox" id="remember" v-model="data.remember_me">
                                <label for="remember" >Remember me</label>
                            </div>
                            <div class="form-group">
                                <input type="submit" value="Login" class="btn btn-primary btn-block" @click.prevent="postLogin">
                                <div class="pull-left">
                                <small><a href="#">Belum terdaftar?</a></small>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<!-- Pada bagian HTML section hanya berisi tag html dimana terdapat dua inputan yakni email dan password dimana masing-masing form input-an tersebut memiliki v-model yang merujuk ke property data() dari Vue.js. -->

<!-- JAVASCRIPT SECTION -->
<script>
import { mapActions, mapMutations, mapGetters, mapState } from 'vuex';
export default {
    data() {
        return {
            data: {
                email: '',
                password: '',
                remember_me: false
            }
        }
    },
    //SEBELUM COMPONENT DI-RENDER
    created() {
        //KITA MELAKUKAN PENGECEKAN JIKA SUDAH LOGIN DIMANA VALUE isAuth BERNILAI TRUE
        if (this.isAuth) {
            //MAKA DI-DIRECT KE ROUTE DENGAN NAME home
            this.$router.push({ name: 'home' })
        }
    },
    computed: {
        ...mapGetters(['isAuth']), //MENGAMBIL GETTERS isAuth DARI VUEX. didepan ['isAuth'] tidak diberi nama module (store) karena targetnya terletak di root yaitu store.js
      	...mapState(['errors']) //megambil state dari store.js
    },
    methods: {
        // this.$store.dispatch('auth/submit'); //awalnya pakai ini. ini native. trus diganti dibawah ini. dibawah ini adalah helpernya vue
        ...mapActions('auth', ['submit']), //MENGINISIASI FUNGSI submit() DARI VUEX AGAR DAPAT DIGUNAKAN PADA COMPONENT TERKAIT. submit() BERASAL DARI ACTION PADA FOLDER STORES/auth.js. karena ini mengambil bukan dari root, maka perlu menambahkan nama modulenya yaitu auth.
        ...mapActions('user', ['getUserLogin']),
        ...mapMutations(['CLEAR_ERRORS']),
      
      	//KETIKA TOMBOL LOGIN DITEKAN, MAKA AKAN MEMICU METHODS postLogin()
        postLogin() {
            //DIMANA TOMBOL INI AKAN MENJALANKAN FUNGSI submit() DENGAN MENGIRIMKAN DATA YANG DIBUTUHKAN. submit ini merujuk pada ...mapActions('auth', ['submit']).
            // console.log(this.data)
            this.submit(this.data).then(() => {
                //KEMUDIAN DI CEK VALUE DARI isAuth
                //APABILA BERNILAI TRUE
                if (this.isAuth) {
                    this.CLEAR_ERRORS()
                    //MAKA AKAN DI-DIRECT KE ROUTE DENGAN NAME home
                    this.$router.push({ name: 'home' })
                }
            })
        },

        destroyed(){
            this.getUserLogin() //getUserLogin() akan berjalankan ketika component Login.vue di-destroy (ditinggalkan).
        }
    }
}
</script>


