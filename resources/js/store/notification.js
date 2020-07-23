import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    notifications: []
})

const mutations = {
    ASSIGN_DATA(state, payload){
        state.notifications = payload
    }
}

const actions = {
    getNotifications({ commit }){
        return new Promise((resolve, reject) => {
            $axios.get(`/notification`)
            .then((res) => {
                commit('ASSIGN_DATA', res.data.data)
                resolve(res.data)
            })
        })
    },
    readNotification({ dispatch }, payload){
        return new Promise((resolve, reject) => {
            $axios.post(`/notification`, payload)
            .then((res) => {
                dispatch('getNotifications').then(() => resolve(res.data))
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
