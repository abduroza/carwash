import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    customers: [], //menampunng data customer yg di request
    type: [], //menampung data type yg di request yg juga mengandung product dan size
    page: 1,
    order: { //ini digunakan oleh viewTransaction, dan juga create transaction (di Form.vue)
        customer: null, //untuk ditambahkan customer baru atau customer yg di pilih di pencarian
        //set default qty 0. size, price dan sbtotal hanya untuk keperluan di html saja. bukan di backend
        transactions: [
            { product: '', type_id: '', size: '', quantity: 0, price: 0, subtotal: 0 }
        ]
    },
    transactions: [], //menampung list data transaksi/order
    isSuccess: false, //untuk menampilkan alert success
    order_id: null, //untuk memberikan value order id setelah order di create. digunakan oleh alert success
    isLoading: false
})

const mutations = {
    DATA_CUSTOMER(state, payload){
        state.customers = payload
    },
    DATA_TYPE(state, payload){
        state.type = payload
    },
    SET_PAGE(state, payload){
        state.page = payload
    },
    DATA_ORDER(state, payload){
        state.order = payload
    },
    CLEAR_FORM(state, payload){
        state.order = {
            customer: null,
            transactions: [
                { product: '', type_id: '', size: '', quantity: 0, price: 0, subtotal: 0 }
            ]
        }
    },
    ASSIGN_DATA_TRANSACTION(state, payload){
        state.transactions = payload
    },
    SET_SUCCESS(state, payload){
        state.isSuccess = payload
    },
    SET_ORDER_ID(state, payload){
        state.order_id = payload
    },
    SET_LOADING(state, payload){
        state.isLoading = payload
    }
}

const actions = {
    getCustomers({commit, state}, payload){
        let search = payload.search
        payload.loading(true) //memberikan loading ke form
        return new Promise((resolve, reject) => {
            $axios.get(`/customer?page=${state.page}&q=${search}`)
            .then((res) => { //res ini berisi data yg dikirimkan oleh backend melalui response()-json()
                commit('DATA_CUSTOMER', res.data)
                payload.loading(false)
                resolve(res.data)
            })
        })
    },
    getType({commit, state}, payload){
        return new Promise((resolve, reject) => {
            $axios.get(`/type-product/${payload.id}?size=${payload.size}`)
            .then((res) => {
                commit('DATA_TYPE', res.data.data)
                resolve(res.data)
            })
        })
    },
    //membuat transaksi
    createTransaction({commit, state}, payload){
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            commit('SET_SUCCESS', false)
            $axios.post(`/transaction`, state.order)
            .then((res) => {
                commit('SET_ORDER_ID', res.data.data.id)
                commit('SET_LOADING', false)
                commit('SET_SUCCESS', true)
                resolve(res.data)
            })
            .catch((err) => {
                commit('SET_LOADING', false)
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, {root: true})
                }
            })
        })
    },
    //mengambil data 1 transaksi/order
    viewTransaction({ commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.get(`/transaction/${payload}/view`)
            .then((res) => {
                commit('DATA_ORDER', res.data.data)
                resolve(res.data)
            })
        })
    },
    //add payment
    payment({ commit }, payload){
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            $axios.post(`/transaction/payment`, payload)
            .then((res) => {
                commit('SET_LOADING', false)
                resolve(res.data)
            }).catch((err) => {
                commit('SET_LOADING', false)
                commit('SET_ERRORS', err.response.data.errors, {root: true})
            })
        })
    },
    //change status in table transactions to 1(done)
    completeItem({commit}, payload){
        return new Promise((resolve, reject) => {
            $axios.post(`/transaction/complete-item`, payload)
            .then((res) => {
                resolve(res.data)
            })
        })
    },
    //mengambil semua data transaksi/order
    getTransactions({commit, state}, payload){
        let search = typeof payload.search != 'undefined' ? payload.search : ''
        let isPaid = typeof payload.isPaid != 'undefined' ? payload.isPaid : ''
        return new Promise((resolve, reject) => {
            $axios.get(`/transaction?page=${state.page}&q=${search}&isPaid=${isPaid}`)
            .then((res) => {
                commit('ASSIGN_DATA_TRANSACTION', res.data)
                resolve(res.data)
            })
        })
    }
}

export default{
    namespaced: true,
    state,
    actions,
    mutations
}
