import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({

})

const mutations = {

}

const actions = {
    submit({ commit }, payload ) { //submit() digunakan pada component Login.vue
        localStorage.setItem('token', null) //RESET LOCAL STORAGE MENJADI NULL
        commit('SET_TOKEN', null, { root: true }) //RESET STATE TOKEN MENJADI NULL. KARENA MUTATIONS SET_TOKEN BERADA PADA ROOT STORES, MAKA DITAMBAHKAN PARAMETER { root: true }

        //KITA MENGGUNAKAN PROMISE AGAR FUNGSI SELANJUTNYA BERJALAN KETIKA FUNGSI INI SELESAI
        return new Promise((resolve, reject) => {
            //MENGIRIM REQUEST KE SERVER DENGAN URI /login 
            //DAN PAYLOAD ADALAH DATA YANG DIKIRIMKAN DARI COMPONENT LOGIN.VUE
            $axios.post('/login', payload)
            .then((res) => {
                //KEMUDIAN JIKA RESPONNYA SUKSES
                if(res.data.status == 'success'){
                    //MAKA LOCAL STORAGE DAN STATE TOKEN AKAN DISET MENGGUNAKAN
                    //API DARI SERVER RESPONSE
                    localStorage.setItem('token', res.data.data.api_token)
                    commit('SET_TOKEN', res.data.data.api_token, { root: true })
                } else {
                    //membuat key invalid yg valuenya Email / Password salah
                    //error ini muncul selain karena err validasi di backend. misal password keliru. di backend tidak divalidasi password keliru/tak ada
                    commit('SET_ERRORS', { invalid: res.data.message }, { root: true })
                }
                resolve(res.data)
            })
            .catch((err) => { //error ini berasal dari backend jika inputan tidak sesuai validasi backend
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, { root: true })
                }
            })
        })
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}
