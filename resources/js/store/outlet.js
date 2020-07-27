import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    outlets: [], //UNTUK MENAMPUNG DATA OUTLETS YANG DIDAPATKAN DARI DATABASE untuk ditampilkan ke user. controller index dan edit

    //UNTUK MENAMPUNG VALUE DARI FORM INPUTAN NANTINYA
    //STATE INI AKAN DIGUNAKAN PADA FORM ADD OUTLET dan update. controller store dan update
    outlet: {
        code: '',
        name: '',
        status: false,
        address: '',
        phone: ''
    },
    page: 1, //UNTUK MENCATAT PAGE PAGINATE YANG SEDANG DIAKSES
    isLoading: false
})

const mutations = {
    //MEMASUKKAN DATA KE STATE OUTLETS untuk ditampilkan (index dan edit)
    ASSIGN_DATA(state, payload){
        state.outlets = payload
    },

    //MENGUBAH DATA STATE PAGE
    SET_PAGE(state, payload){
        state.page = payload
    },

    //MENGUBAH DATA STATE OUTLET untuk disimpan (store dan update)
    ASSIGN_FORM(state, payload){
        state.outlet = {
            code: payload.code,
            name: payload.name,
            status: payload.status,
            address: payload.address,
            phone: payload.phone
        }
    },

    //ME-RESET STATE OUTLET MENJADI KOSONG
    CLEAR_FORM(state, payload){
        state.outlet = {
            code: '',
            name: '',
            status: '',
            address: '',
            phone: ''
        }
    },
    SET_LOADING(state, payload){
        state.isLoading = payload
    }
}

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA OUTLET DARI SERVER
    getOutlets({ commit, state }, payload){ //payload berisi kata kunci yg dicari
        //MENGECEK PAYLOAD ADA ATAU TIDAK
        let search = typeof payload != 'undefined' ? payload : ''
        return new Promise((resolve, reject) => {
            //REQUEST DATA DENGAN ENDPOINT /OUTLETS
            $axios.get(`/outlets?page=${state.page}&q=${search}`) //code search hanya jalan di page 1 saja. biar bisa search di semua page, maka code ini page=${state.page}& dibuang. tp sayangnya, pagination jdi gak jalan.
            .then((res) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('ASSIGN_DATA', res.data)
                resolve(res.data)
            })
        })
    },
    //FUNGSI UNTUK MENAMBAHKAN DATA BARU
    submitOutlet({ dispatch, commit, state }){
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            $axios.post(`/outlets`, state.outlet)
            .then((res) => {
                commit('SET_LOADING', false)
                dispatch('getOutlets').then(() => resolve(res.data))
            })
            .catch((error) => {
                commit('SET_LOADING', false)
                //APABILA TERJADI ERROR VALIDASI
                //DIMANA LARAVEL MENGGUNAKAN CODE 422
                if(error.response.status == 422){
                    //MAKA LIST ERROR AKAN DIASSIGN KE STATE ERRORS
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    //UNTUK MENGAMBIL SINGLE DATA DARI SERVER BERDASARKAN CODE OUTLET
    editOutlet({ commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.get(`/outlets/${payload}/edit`)
            .then((res) => {
                //APABIL BERHASIL, DI ASSIGN KE FORM
                commit('ASSIGN_FORM', res.data.data)
                resolve(res.data)
            })
        })
    },
    //UNTUK MENGUPDATE DATA BERDASARKAN CODE YANG SEDANG DIEDIT
    updateOutlet({ state, commit }, payload){
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN CODE DIURL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            //MELALUI STATE OUTLET
            $axios.put(`/outlets/${payload}`, state.outlet)
            .then((res) => {
                //FORM DIBERSIHKAN
                commit('CLEAR_FORM')
                commit('SET_LOADING', false)
                resolve(res.data)
            })
            .catch((error) => {
                commit('SET_LOADING', false)
                //kirim value error ke store.js
                commit('SET_ERRORS', error.response.data.errors, { root: true })
            })
        })
    },
    //MENGHAPUS DATA 
    removeOutlet({ dispatch, commit }, payload){
        return new Promise((resolve, reject) => {
            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENGHAPUS DATA
            //DENGAN METHOD DELETE DAN ID OUTLET DI URL
            $axios.delete(`/outlets/${payload}`)
            .then((res) => {
                //APABILA BERHASIL, panggil getOutlets() untuk FETCH DATA TERBARU DARI SERVER
                dispatch('getOutlets').then(() => resolve())
            })
            .catch((error) => {
                //kirim value error ke store.js
                commit('SET_ERRORS', error.response.data.errors, { root: true })
            })
        })
    },
    //melakukan request data tanpa pagination. untuk keperluan di operators/Form.vue
    getOutletsNoPage({ commit }, payload){ //payload berisi kata kunci yg dicari
        let search = typeof payload != 'undefined' ? payload : ''
        return new Promise((resolve, reject) => {
            $axios.get(`/outlets/nopage?q=${search}`)
            .then((res) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('ASSIGN_DATA', res.data)
                resolve(res.data)
            })
        })
    },
}
export default{
    namespaced: true,
    state,
    actions,
    mutations
}
