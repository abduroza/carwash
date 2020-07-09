<template>
    <div class="wrapper">
        <app-header v-if="isAuth" />

        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        <app-footer v-if="isAuth" />
    </div>
</template>
<script>
import { mapGetters, mapState } from 'vuex'
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
export default {
    computed: {
        ...mapGetters(['isAuth']),
        ...mapState(['token'])
    },
    components: {
        'app-header' : Header,
        'app-footer' : Footer
    }
}
// app-header dan app-footer adalah custom tag dari component Header.vue dan Footer.vue, dimana kedua tag ini akan di-render apabila isAuth bernilai true. Adapun tag router-view adalah tag yang berasal dari vue router dimana html yang akan di-render berdasarkan route yang sedang diakses.
</script>
