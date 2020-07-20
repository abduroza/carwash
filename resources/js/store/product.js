import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    products: [], //menampung list data product dari database. controller index. bentuknya array sehingga banyaknya data dapat dihitung dengan length

    //menampung value dari inputan. datanya dari edit untuk store dan update. controllr store n update
    product: {
        name: '',
        type_id: '',
        size: '',
        description: '',
        price: '',
        user_id: ''
    },
    page: 1 //mencatat paginate yg diakses, dg default page 1
})

const mutations = {
    //memasukkan ke state products untuk ditampilkan (index)
    ASSIGN_DATA(state, payload){
        state.products = payload
    },

    SET_PAGE(state, payload){
        state.page = payload
    },

    //mengubah data state product yg datanya dari edit untuk store n update
    ASSIGN_FORM(state, payload){
        state.product = {
            name: payload.name,
            type_id: payload.type[0].id,
            size: payload.type[0].pivot.size,
            description: payload.description,
            price: payload.price,
            // user_id: payload.user.id //user_id tidak diupdate, sehingga dihilangkan saja formnya
        }
    },

    //mengosongkan form
    CLEAR_FORM(state, payload){
        state.product = {
            name: '',
            type_id: '',
            size: '',
            description: '',
            price: '',
            user_id: ''
        }
    }
}

const actions = {
    //request list product
    getProducts({ commit, state }, payload){
        //cek apakah di payload ada value search yg dikirmkam
        let search = typeof payload != 'undefined' ? payload : ''
        return new Promise((resolve, reject) => {
            //fetch data dari apai dengan mengirimkan page dan search
            $axios.get(`/product?page=${state.page}&q=${search}`)
            .then((res) => {
                //simpan data ke state melalui mutations
                commit('ASSIGN_DATA', res.data)
                resolve(res.data)
            })
        })
    },
    //create a new data
    submitProduct({ commit, dispatch, state }){
        return new Promise((resolve, reject) => {
            $axios.post(`/product`, state.product)
            .then((res) => {
                dispatch('getProducts').then(() => resolve(res.data))
            })
            .catch((err) => {
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, {root: true})
                }
            })
        })
    },
    //show a data product
    editProduct({commit, state}, payload){
        return new Promise((resolve, reject) => {
            $axios.get(`/product/${payload}/edit`)
            .then((res) => {
                //jika berhasil data yg diperoleh di assign melalui mutation
                commit('ASSIGN_FORM', res.data.data)
                resolve(res.data)
            })
        })
    },
    //update product by id. payload passing the id product
    updateProduct({ state, commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.put(`/product/${payload}`, state.product)
            .then((res) => {
                commit('CLEAR_FORM')
                resolve(res.data)
            })
            .catch((err) => {
                commit('SET_ERRORS', err.response.data.errors, { root: true })
            })
        })
    },
    //delete a product by id
    removeProduct({ dispatch }, payload){
        return new Promise((resolve, reject) => {
            $axios.delete(`/product/${payload}`)
            .then((res) => {
                //res kayaknya tidak berisi data, sehingga tidak perlu dikirm di resolve
                //jika berhasil fecth data terbaru
                dispatch('getProducts').then(() => resolve())
            })
            .catch((err) => {
                //kirim value error ke store.js
                commit('SET_ERRORS', err.response.data.errors, { root: true })
            })
        })
    }
}
export default {
    namespaced: true,
    state,
    mutations,
    actions
}
