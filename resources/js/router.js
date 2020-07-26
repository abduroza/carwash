import Vue from 'vue'
import Router from 'vue-router'
import Home from './pages/Home.vue'
import Login from './pages/Login.vue'
import store from './store.js'

import IndexOutlet from './pages/outlets/Index.vue'
import AddOutlet from './pages/outlets/Add.vue'
import DataOutlet from './pages/outlets/Outlet.vue'
import EditOutlet from './pages/outlets/Edit.vue'

import IndexOperator from './pages/operators/Index.vue'
import DataOperator from './pages/operators/Operator.vue'
import AddOperator from './pages/operators/Add.vue'
import EditOperator from './pages/operators/Edit.vue'

import IndexUser from './pages/users/Index.vue'
import DataUser from './pages/users/User.vue'
import AddUser from './pages/users/Add.vue'
import EditUser from './pages/users/Edit.vue'

import IndexProduct from './pages/products/Index.vue'
import DataProduct from './pages/products/Product.vue'
import AddProduct from './pages/products/Add.vue'
import EditProduct from './pages/products/Edit.vue'

import IndexExpense from './pages/expenses/Index.vue'
import DataExpense from './pages/expenses/Expense.vue'
import AddExpense from './pages/expenses/Add.vue'
import EditExpense from './pages/expenses/Edit.vue'
import ViewExpense from './pages/expenses/View.vue'

import IndexCustomer from './pages/customers/Index.vue'
import DataCustomer from './pages/customers/Customer.vue'
import AddCustomer from './pages/customers/Add.vue'
import EditCustomer from './pages/customers/Edit.vue'

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
            component: IndexOutlet, //IndexOutlet nama component di Outlet/Index.vue
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'outlets.data',
                    component: DataOutlet,
                    meta: { title: 'Manage Outlets'}
                },
                {
                    path: 'add',
                    name: 'outlets.add',
                    component: AddOutlet,
                    meta: { title: 'Add New Outlet'}
                },
                {
                    path: 'edit/:id',
                    name: 'outlets.edit',
                    component: EditOutlet,
                    meta: { title: 'Edit Outlet'}
                }
            ]
        },
        {
            path: '/operator',
            component: IndexOperator,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'operators.data',
                    component: DataOperator,
                    meta: { title: 'Manage Operator'}
                },
                {
                    path: 'add',
                    name: 'operator.add',
                    component: AddOperator,
                    meta: { title: 'Add Operator'}
                },
                {
                    path: 'edit/:id',
                    name: 'operator.edit',
                    component: EditOperator,
                    meta: { title: 'Edit Operator'}
                }
            ]
        },
        {
            path: '/user',
            component: IndexUser,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'users.data',
                    component: DataUser,
                    meta: { title: 'Manage User'}
                },
                {
                    path: 'add',
                    name: 'user.add',
                    component: AddUser,
                    meta: { title: 'Add User'}
                },
                {
                    path: 'edit/:id',
                    name: 'user.edit',
                    component: EditUser,
                    meta: { title: 'Edit User'}
                }
            ]
        },
        {
            path: '/product',
            component: IndexProduct,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'products.data',
                    component: DataProduct,
                    meta: { title: 'Manage Product'},
                },
                {
                    path: 'add',
                    name: 'product.add',
                    component: AddProduct,
                    meta: { title: 'Add Product'}
                },
                {
                    path: 'edit/:id',
                    name: 'product.edit',
                    component: EditProduct,
                    meta: { title: 'Edit Product'}
                }
            ]
        },
        {
            path: '/expense',
            component: IndexExpense,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'expenses.data',
                    component: DataExpense,
                    meta: { title: 'Manage Expense'}
                },
                {
                    path: 'add',
                    name: 'expense.add',
                    component: AddExpense,
                    meta: { title: 'Add New Expense' }
                },
                {
                    path: 'edit/:id',
                    name: 'expense.edit',
                    component: EditExpense,
                    meta: { title: 'Edit Expense' }
                },
                {
                    path: 'view/:id',
                    name: 'expense.view',
                    component: ViewExpense,
                    meta: { title: 'View Expense'}
                }
            ]
        },
        {
            path: '/customer',
            component: IndexCustomer,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'customers.data',
                    component: DataCustomer,
                    meta: { title: 'Manage Customer'},
                },
                {
                    path: 'add',
                    name: 'customer.add',
                    component: AddCustomer,
                    meta: { title: 'Add Customer'}
                },
                {
                    path: 'edit/:id',
                    name: 'customer.edit',
                    component: EditCustomer,
                    meta: { title: 'Edit Customer'}
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
