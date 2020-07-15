<template>
    <nav aria-label="breadcrumb" class="float-right mb-lg-2">
        <ol class="breadcrumb d-inline-flex my-0 px-2 py-1 bg-transparent">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'home' }">
                    <i class="fa fa-home"></i> 
                    Home
                </router-link>
            <!-- $route.meta.title akan mengakses attribute meta.title dari route yang telah didefinisikan pada file router.js, sehingga value yang akan ditampilkan tergantung route yang sedang diakses. -->
            <li class="breadcrumb-item active">{{ $route.meta.title }}</li>
        </ol>
    </nav>
</template>

<script>
export default {
    
}
</script>
