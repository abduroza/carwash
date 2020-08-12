import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    operators: [], //UNTUK MENAMPUNG DATA OUTLETS YANG DIDAPATKAN DARI DATABASE. controller index

    //UNTUK MENAMPUNG VALUE DARI FORM INPUTAN NANTINYA
    //STATE INI AKAN DIGUNAKAN PADA FORM ADD OUTLET dan update. controller store dan update
    operator: {
        name: '',
        email: '',
        password: '',
        role: '',
        outlet_id: '',
        photo: ''
    },
    page: 1, //UNTUK MENCATAT PAGE PAGINATE YANG SEDANG DIAKSES
    isLoading: false
})

const mutations = {
    //MEMASUKKAN DATA KE STATE operators untuk ditampilkan (index)
    ASSIGN_DATA(state, payload){
        state.operators = payload
    },

    //MENGUBAH DATA STATE PAGE
    SET_PAGE(state, payload){
        state.page = payload
    },

    //MENGUBAH DATA STATE operator untuk disimpan (update) yg datanya dari edit
    ASSIGN_FORM(state, payload){
        state.operator = {
            name: payload.name,
            email: payload.email,
            password: '',
            role: payload.role,
            outlet_id: payload.outlet_id,
            photo: ''
        }
    },

    //ME-RESET STATE OPERATOR MENJADI KOSONG
    CLEAR_FORM(state, payload){
        state.operator = {
            name: '',
            email: '',
            password: '',
            role: '',
            outlet_id: '',
            photo: ''
        }
    },
    SET_LOADING(state, payload){
        state.isLoading = payload
    }
}

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA OUTLET DARI SERVER
    getOperators({ commit, state }, payload){ //payload berisi kata kunci yg dicari
        //MENGECEK PAYLOAD ADA ATAU TIDAK
        let search = typeof payload != 'undefined' ? payload : ''
        return new Promise((resolve, reject) => {
            //REQUEST DATA DENGAN ENDPOINT /OUTLETS
            $axios.get(`/operators?page=${state.page}&q=${search}`)
            .then((res) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('ASSIGN_DATA', res.data)
                resolve(res.data)
            })
        })
    },
    //FUNGSI UNTUK MENAMBAHKAN DATA BARU
    submitOperator({commit, dispatch, state}){

        let form = new FormData() //DIMANA UNTUK MENGUPLOAD GAMBAR HARUS MENGGUNAKAN FORMDATA
        form.append('name', state.operator.name)
        form.append('email', state.operator.email)
        form.append('password', state.operator.password)
        form.append('role', state.operator.role)
        form.append('outlet_id', state.operator.outlet_id)
        form.append('photo', state.operator.photo)

        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            $axios.post(`/user`, form, {
                //KARENA TERDAPAT FILE FOTO, MAKA HEADERNYA DITAMBAHKAN multipart/form-data
                headers: {
                    'Content-Type' : 'multipart/form-data'
                }
            })
            .then((res) => {
                commit('SET_LOADING', false)
                dispatch('getOperators').then(() => resolve(res.data))
            })
            .catch((err) => {
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, {root: true})
                } else if(err.response.status == 400){
                    commit('SET_ERRORS', err.response.data, { root: true })
                }
                commit('SET_LOADING', false)
            })
        })
    },
    //UNTUK MENGAMBIL SINGLE DATA DARI SERVER BERDASARKAN CODE opeartor/user
    editOperator({ commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.get(`/user/${payload}/edit`)
            .then((res) => {
                //APABIL BERHASIL, DI ASSIGN KE FORM
                commit('ASSIGN_FORM', res.data.data)
                resolve(res.data)
            })
        })
    },
    //UNTUK MENGUPDATE DATA BERDASARKAN CODE YANG SEDANG DIEDIT
    updateOperator({ commit, state }, payload){

        let form = new FormData() //UNTUK MENGUPLOAD GAMBAR HARUS MENGGUNAKAN FORMDATA
        form.append('name', state.operator.name)
        form.append('email', state.operator.email)
        form.append('password', state.operator.password)
        form.append('role', state.operator.role)
        form.append('outlet_id', state.operator.outlet_id)
        form.append('photo', state.operator.photo)

        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            $axios.post(`/operator/${payload}`, form, {
                //KARENA TERDAPAT FILE FOTO, MAKA HEADERNYA DITAMBAHKAN multipart/form-data
                headers: {
                    'Content-Type' : 'multipart/form-data'
                }
            })
            .then((res) => {
                commit('CLEAR_FORM')
                commit('SET_LOADING', false)
                resolve(res.data)
            })
            .catch((err) => {
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, {root: true})
                } else if(err.response.status == 400){
                    commit('SET_ERRORS', err.response.data, { root: true })
                }
                commit('SET_LOADING', false)
            })
        })
    },
    //MENGHAPUS DATA
    removeOperator({ dispatch, commit }, payload){
        return new Promise((resolve, reject) => {
            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENGHAPUS DATA
            //DENGAN METHOD DELETE DAN ID OUTLET DI URL
            $axios.delete(`/user/${payload}`)
            .then((res) => {
                //APABILA BERHASIL, panggil getOperator untuk FETCH DATA TERBARU DARI SERVER
                dispatch('getOperators').then(() => resolve())
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
