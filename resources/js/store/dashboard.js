import $axios from '../api.js'
import { reject } from 'lodash'

const state = () => ({
    orders: [] //state unutk menyimpan data order
})

const mutations = {
    //mutation untuk mengirim data order
    ASSIGN_DATA_ORDER(state, paylod){
        state.orders = paylod
    }
}

const actions = {
    getChartData({ commit }, paylod){
        return new Promise((resolve, reject) => {
            $axios.get(`/chart?month=${paylod.month}&year=${paylod.year}`)
            .then((res) => {
                commit('ASSIGN_DATA_ORDER', res.data)
                resolve(res.data)
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
