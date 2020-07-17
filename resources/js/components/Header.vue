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
                    <!-- <li class="active">{{ $route.meta.title }}</li> -->
                    <!--  :class="{ 'active' : $route.name == 'outlets*'}" -->
                    <li class="nav-item" :class="{ 'active' : $route.path == '/outlets/*'}">
                        <router-link class="nav-link" :to="{ name: 'outlets.data' }">Outlets</router-link>
                    </li>
                    <li class="nav-item" :class="{ 'active' : $route.path == '/operator/*'}">
                        <router-link class="nav-link" :to="{ name: 'operators.data' }">Operators</router-link>
                    </li>
                <!-- </ul> -->
            </ul>

            <ul class="navbar-nav flex-row ml-auto d-flex">
                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user-circle-o"></i>
                        <!-- <img src="https://via.placeholder.com/80" class="user-image" alt="User Image"> -->
                        <!-- <span class="hidden-xs">{{ authenticated.name }}</span> -->
                        User
                    </a>
                    <div class="dropdown-menu float-left clearfix bg-white border-primary" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Nama User</a>
                        <!-- <span class="hidden-xs">{{ authenticated.name }}</span> -->
                        <div class="dropdown-divider"></div>
                        <div class="px-2">
                            <a class="btn btn-outline-primary btn-sm float-left" href="#">Profile</a>
                            <a class="btn btn-outline-primary btn-sm float-right" href="javascript:void(0)" @click="logout">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
</template>

<script>
import {mapState} from 'vuex'
export default {
    methods: {
        logout() {
            return new Promise((resolve, reject) => {
                localStorage.removeItem('token') //MENGHAPUS TOKEN DARI LOCALSTORAGE
                resolve()
            }).then(()=> {
                //Jika berhasil MEMPERBAHARUI STATE TOKEN
                this.$store.state.token = localStorage.getItem('token') //mengakses store.js untuk mengganti  token local storage menjadi sesuai dg localstorage browser(saat logout)
                this.$router.push('/login') //redirect ke page login
            })
        }
    }
}
</script>
