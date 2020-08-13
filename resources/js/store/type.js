import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    types: [],
    type: {
        name_type: '' //name_type adlah inputan yg diminta oleh backend. kalau database nama kolomnya "name"
    },
    isLoading: false
})

const mutations = {
    //memasukkan ke state type untuk ditampilkan (index)
    ASSIGN_DATA(state, payload){
        state.types = payload
    },
    CLEAR_FORM_TYPE(state, payload){
        state.type = {
            name_type: ''
        }
    },
    SET_LOADING(state, payload){
        state.isLoading = payload
    }
}

const actions = {
    getTypes({ commit }){
        return new Promise((resolve, reject) => {
            $axios.get(`/type-product`)
            .then((res) => {
                commit('ASSIGN_DATA', res.data.data)
                resolve(res.data)
            })
        })
    },
    addType({ commit, dispatch, state}){
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            $axios.post(`/type-product`, state.type)
            .then((res) => {
                dispatch('getTypes').then(() => {
                    commit('SET_LOADING', false)
                    resolve(res.data)
                })
                commit('SET_SUCCESS', res.data, { root: true })
            })
            .catch((err) => {
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, { root: true })
                }
                commit('SET_LOADING', false)
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
