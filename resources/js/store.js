import Vue from 'vue'
import Vuex from 'vuex'

//IMPORT MODULE SECTION
import auth from './store/auth.js'
import outlet from './store/outlet.js'
import operator from './store/operator.js'

Vue.use(Vuex)

//DEFINE ROOT STORE VUEX
const store = new Vuex.Store({
    //SEMUA MODULE YANG DIBUAT AKAN DITEMPATKAN DIDALAM BAGIAN INI DAN DIPISAHKAN DENGAN KOMA UNTUK SETIAP MODULE-NYA
    modules: {
        auth,
        outlet,
        operator
    },
    //STATE HAMPIR SERUPA DENGAN PROPERTY DATA DARI COMPONENT HANYA SAJA DAPAT DIGUNAKAN SECARA GLOBAL
    state: {
        //VARIABLE TOKEN MENGAMBIL VALUE DARI LOCAL STORAGE token
        token: localStorage.getItem('token'),
        errors: []
    },
    getters: {
        //KITA MEMBUAT SEBUAH GETTERS DENGAN NAMA isAuth
        //DIMANA GETTERS INI AKAN BERNILAI TRUE/FALSE DENGAN KONDISI BERDASARKAN STATE token.
        isAuth: state => {
            return state.token != 'null' && state.token != null
        }
    },
    mutations: {
         //SEBUAH MUTATIONS YANG BERFUNGSI UNTUK MEMANIPULASI VALUE DARI STATE token
        SET_TOKEN(state, payload){
            state.token = payload
        },
        SET_ERRORS(state, payload){
            state.errors = payload
        },
        CLEAR_ERRORS(state, payload){
            state.errors = []
        }
    }
})

export default store
