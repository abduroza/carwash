import Vue from 'vue'
import router from './router.js'
import store from './store.js'
import App from './App.vue'

import BootstrapVue from 'bootstrap-vue'
import VueSweetalert2 from 'vue-sweetalert2'
import VueCurrencyFilter from 'vue-currency-filter' //format mata uang

Vue.use(VueSweetalert2)
Vue.use(BootstrapVue)
Vue.use(VueCurrencyFilter,
    {
        symbol : 'Rp',
        thousandsSeparator: '.',
        fractionCount: 0,
        fractionSeparator: ',',
        symbolPosition: 'front',
        symbolSpacing: true
    })

import 'bootstrap-vue/dist/bootstrap-vue.css'

new Vue({
    el: '#appku',
    router,
    store,
    components: {
        App
    }
})
