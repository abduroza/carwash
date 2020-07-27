import $axios from '../api.js'
import { reject, spread } from 'lodash'

const state = () => ({
    customers: [],

    customer: {
        name: '',
        nik: '',
        address: '',
        phone: '',
        amount: 0
    },
    page: 1,
    isLoading: false
})

const mutations = {
    ASSIGN_DATA(state, payload){
        state.customers = payload
    },

    SET_PAGE(state, payload){
        state.page = payload
    },

    ASSIGN_FORM(state, payload){
        state.customer = {
            name: payload.name,
            nik: payload.nik,
            address: payload.address,
            phone: payload.phone,
            amount: payload.deposite.amount
        }
    },

    CLEAR_FORM(state, payload){
        state.customer = {
            name: '',
            nik: '',
            address: '',
            phone: '',
            amount: ''
        }
    },
    SET_LOADING(state, payload){
        state.isLoading = payload
    }
}

const actions = {
    getCustomers({ commit, state}, payload){
        let search = typeof payload != 'undefined' ? payload : ''
        return new Promise((resolve, reject) => {
            $axios.get(`/customer/?page=${state.page}&q=${search}`)
            .then((res) => {
                commit('ASSIGN_DATA', res.data)
                resolve(res.data)
            })
        })
    },
    submitCustomer({ state, commit, dispatch }){
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            $axios.post(`/customer`, state.customer)
            .then((res) => {
                commit('SET_LOADING', false)
                dispatch('getCustomers').then(() => resolve(res.data))
            })
            .catch((err) => {
                commit('SET_LOADING', false)
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, { root: true })
                }
            })
        })
    },
    editCustomer({ commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.get(`/customer/${payload}/edit`)
            .then((res) => {
                commit('ASSIGN_FORM', res.data.data)
                resolve(res.data)
            })
        })
    },
    updateCustomer({ commit, state }, payload){
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)
            $axios.put(`/customer/${payload}`, state.customer)
            .then((res) => {
                commit('CLEAR_FORM')
                commit('SET_LOADING', false)
                resolve(res.data)
            })
            .catch((err) => {
                commit('SET_LOADING', false)
                if (err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, {root: true})
                }
            })
        })
    },
    removeCustomer({ dispatch }, payload){
        return new Promise((resolve, reject) => {
            $axios.delete(`/customer/${payload}`)
            .then((res) => {
                dispatch('getCustomers').then(() => resolve(res.data))
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
