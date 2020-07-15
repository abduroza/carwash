import Vue from 'vue'
import Router from 'vue-router'
import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import store from './store.js'

import IndexOutlet from './pages/outlets/Index.vue'
import AddOutlet from './pages/outlets/Add.vue'
import DataOutlet from './pages/outlets/Outlet.vue'
import EditOutlet from './pages/outlets/Edit.vue'

Vue.use(Router)

//DEFINE ROUTE
const router = new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            meta: { requiresAuth: true }
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        },
        {
            path: '/outlets',
            component: IndexOutlet,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'outlets.data',
                    component: DataOutlet,
                    meta: { title: 'Manage Outlets'}
                },
                {
                    path: '/add',
                    name: 'outlets.add',
                    component: AddOutlet,
                    meta: { title: 'Add New Outlet'}
                },
                {
                    path: '/edit/:id',
                    name: 'outlets.edit',
                    component: EditOutlet,
                    meta: { title: 'Edit Outlet'}
                }
            ]
        }
    ]
});

//Navigation Guards. berfungsi untuk mengecek jika route tersebut membutuhkan proses otentikasi untuk mengakses page-nya, maka dibutuhkan pengecekan lebih lanjut apakah user sudah login atau belum. jika belum secara otomatis akan di-redirect ke route dengan name login, apabila sebaliknya maka akan diteruskan kehalaman yang diinginkan.
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        store.commit('CLEAR_ERRORS') //Membersihkan state errors setiap kali halaman diload
        // memanggil dari getter isAuth di store.js
        let auth = store.getters.isAuth
        if (!auth) {
            // jika nilai auth dari store.js bernilai null
            next({ name: 'login' })
        } else {
            next()
        }
    } else {
        next()
    }
})
export default router
