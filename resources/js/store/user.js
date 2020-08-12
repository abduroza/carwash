import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    authenticated: [], //menampung user yg sedang login
    users: [], //menampumg list user
    user: {
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
    //assign user yg sedang login
    ASSIGN_USER_AUTH(state, payload){
        state.authenticated = payload
    },

    //assign semua user yg ada kecuali user untuk ditampilkan index
    ASSIGN_DATA(state, payload){
        state.users = payload
    },

    //MENGUBAH DATA STATE PAGE
    SET_PAGE(state, payload){
        state.page = payload
    },

    //mengubah state user untuk keperluan update yg datanya dari edit
    ASSIGN_FORM(state, payload){
        state.user = {
            name: payload.name,
            email: payload.email,
            password: '',
            role: payload.role,
            outlet_id: payload.outlet_id,
            photo: ''
        }
    },

    //ME-RESET STATE USER MENJADI KOSONG
    CLEAR_FORM(state, payload){
        state.user = {
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
    getUserLogin({ commit }){
        return new Promise((resolve, reject) => {
            $axios.get(`/user-authenticated`)
            .then((res) => {
                commit('ASSIGN_USER_AUTH', res.data.data)
                resolve(res.data)
            })
        })
    },
    getUsers({ commit, state }, payload){ //ini belum dipakai
        let search = typeof payload != 'undefined' ? payload : ''
        return new Promise((resolve, reject) => {
            $axios.get(`/user-lists?page=${state.page}&q=${search}`)
            .then((res) => {
                commit('ASSIGN_DATA', res.data)
                resolve(res.data)
            })
        })
    },
    submitUser({ commit, dispatch, state }){
        let form = new FormData() //DIMANA UNTUK MENGUPLOAD GAMBAR HARUS MENGGUNAKAN FORMDATA
        form.append('name', state.user.name)
        form.append('email', state.user.email)
        form.append('password', state.user.password)
        form.append('role', state.user.role)
        form.append('outlet_id', state.user.outlet_id)
        form.append('photo', state.user.photo)

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
                dispatch('getUsers').then(() => resolve(res.data))
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
    //UNTUK MENGAMBIL SINGLE DATA DARI SERVER BERDASARKAN CODE user
    editUser({ commit }, payload){
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
    updateUser({ commit, state }, payload){

        let form = new FormData() //UNTUK MENGUPLOAD GAMBAR HARUS MENGGUNAKAN FORMDATA
        form.append('name', state.user.name)
        form.append('email', state.user.email)
        form.append('password', state.user.password)
        form.append('role', state.user.role)
        form.append('outlet_id', state.user.outlet_id)
        form.append('photo', state.user.photo)

        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            $axios.post(`/user/${payload}`, form, {
                //KARENA TERDAPAT FILE FOTO, MAKA HEADERNYA DITAMBAHKAN multipart/form-data
                headers: {
                    'Content-Type' : 'multipart/form-data'
                }
            })
            .then((res) => {
                commit('SET_LOADING', false)
                commit('CLEAR_FORM')
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
    removeUser({ dispatch, commit }, payload){
        return new Promise((resolve, reject) => {
            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENGHAPUS DATA
            //DENGAN METHOD DELETE DAN ID OUTLET DI URL
            $axios.delete(`/user/${payload}`)
            .then((res) => {
                //APABILA BERHASIL, panggil getUsers untuk FETCH DATA TERBARU DARI SERVER
                dispatch('getUsers').then(() => resolve())
            })
            .catch((err) => {
                //kirim value error ke store.js
                commit('SET_ERRORS', err.response.data.errors, { root: true })
            })
        })
    }
}

export default{
    namespaced: true,
    state,
    mutations,
    actions
}
