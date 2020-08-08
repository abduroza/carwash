import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    customers: [], //menampunng data customer yg di request
    type: [], //menampung data type yg di request
    // products: [], //menampung data product yg direquest
    order: [],
    page: 1,
    transactions: [], //menampung semua data transaksi
    isLoading: false
})

const mutations = {
    DATA_CUSTOMER(state, payload){
        state.customers = payload
    },
    DATA_TYPE(state, payload){
        state.type = payload
    },
    // DATA_PRODUCT(state, payload){
    //     state.products = payload
    // },
    SET_PAGE(state, payload){
        state.page = payload
    },
    ASSIGN_ORDER(state, payload){
        state.order = payload
    },
    ASSIGN_DATA_TRANSACTION(state, payload){
        state.transactions = payload
    },
    SET_LOADING(state, payload){
        state.isLoading = payload
    }
}

const actions = {
    getCustomers({commit, state}, payload){
        let search = payload.search
        payload.loading(true)
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
    // getProducts({commit, state}, payload){
    //     let search = payload.search
    //     payload.loading(true)
    //     return new Promise((resolve, reject) => {
    //         $axios.get(`/product?page=${state.page}&q=${search}`)
    //         .then((res) => {
    //             commit('DATA_PRODUCT', res.data)
    //             payload.loading(false)
    //             resolve(res.data)
    //         })
    //     })
    // },
    //membuat transaksi
    createTransaction({commit}, payload){
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            $axios.post(`/transaction`, payload)
            .then((res) => {
                commit('SET_LOADING', false)
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
    //mengambil data transaksi
    viewTransaction({ commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.get(`/transaction/${payload}/view`)
            .then((res) => {
                commit('ASSIGN_ORDER', res.data.data)
                resolve(res.data)
            })
        })
    },
    //add payment
    payment({ commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.post(`/transaction/payment`, payload)
            .then((res) => {
                resolve(res.data)
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
    getTransactions({commit, state}, payload){
        let search = typeof payload.search != 'undefined' ? payload.search : ''
        let isPaid = typeof payload.isPaid != 'undefined' ? payload.isPaid : ''
        return new Promise((resolve, reject) => {
            console.log(isPaid)
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
