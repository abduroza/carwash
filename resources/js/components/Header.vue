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
                    <li class="nav-item" >
                        <router-link class="nav-link" to="/">Home <span class="sr-only">(current)</span></router-link>
                    </li>
                    <!--  :class="{ 'active' : $route.name == 'outlets*'}" -->
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'outlets.data' }">Outlets</router-link>
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
                    <div class="dropdown-menu float-left" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Logout</a>
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

}
</script>
