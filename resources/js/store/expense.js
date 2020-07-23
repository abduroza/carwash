import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    //menampilkan semua data untuk keperluan index
    expenses: [],

    //menampikan satu data untuk keperluan edit, update, store
    expense: {
        title: '',
        note: '',
        price: '',
        user_id: '',
        user: '', //dari backend masih ada error

        status: '',
        reason: ''
    },
    page: 1
})

const mutations = {
    //untuk keperluan index (get all data)
    ASSIGN_DATA(state, payload){
        state.expenses = payload
    },

    SET_PAGE(state, payload){
        state.page = payload
    },

    ASSIGN_FORM(state, payload){ //untuk keperluan edit
        state.expense = {
            title: payload.title,
            note: payload.note,
            price: payload.price,
            user_id: payload.user_id,
            user: payload.user,

            status: payload.status,
            reason: payload.reason
        }
    },

    CLEAR_FORM(state, payload){
        state.expense = {
            title: '',
            note: '',
            price: '',
            user_id: '',

            status: '',
            reason: ''
        }
    }
}

const actions = {
    getExpenses({commit, state }, payload){
        return new Promise((resolve, reject) => {
            let search = typeof payload != 'undefined' ? payload : ''
            $axios.get(`/expense?page=${state.page}&q=${search}`)
            .then((res) => {
                commit('ASSIGN_DATA', res.data)
                resolve(res.data)
            })
        })
    },
    submitExpense({ state, commit, dispatch }){
        return new Promise((resolve, reject) => {
            $axios.post(`/expense`, state.expense)
            .then((res) => {
                dispatch('getExpenses').then(() => resolve(res.data))
            })
            .catch((err) => {
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, { root: true })
                }
            })
        })
    },
    editExpense({ commit, state }, payload){
        return new Promise((resolve, reject) => {
            $axios.get(`/expense/${payload}/edit`)
            .then((res) => {
                commit('ASSIGN_FORM', res.data.data)
                resolve(res.data)
            })
        })
    },
    updateExpense({ state, commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.put(`/expense/${payload}`, state.expense)
            .then((res) => {
                commit('CLEAR_FORM')
                resolve(res.data)
            })
            .catch((err) => {
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, { root: true })
                }
            })
        })
    },
    removeExpense({ dispatch }, payload){
        return new Promise((resolve, reject) => {
            $axios.delete(`/expense/${payload}`)
            .then((res) => {
                dispatch('getExpenses').then(() => resolve())
            })
            .catch((err) => {
                commit('SET_ERRORS', err.response.data.errors, { root: true })
            })
        })
    },
    acceptExpense({ commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.post(`/expense/accept`, payload)
            .then((res) => {
                resolve(res.data)
            }).catch((err) => {
                if(err.response.status == 422){
                    commit('SET_ERRORS', err.response.data.errors, { root: true })
                }
            })
        })
    },
    rejectExpense({ commit }, payload){
        return new Promise((resolve, reject) => {
            $axios.post(`/expense/reject`, payload)
            .then((res) => {
                resolve(res.data)
            }).catch((err) => {
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
    mutations,
    actions
}
