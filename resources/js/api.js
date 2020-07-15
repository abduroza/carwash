import axios from 'axios'
import store from './store.js'

const $axios = axios.create({
    baseURL: '/api',
    headers: {
        //MATIKAN BAGIAN INI
        // Authorization: localStorage.getItem('token') != 'null' ? 'Bearer ' + localStorage.getItem('token'):'',
        'Content-Type': 'application/json'
    }
});

//KONFIGURASINYA KITA PINDAHKAN MENGGUNAKAN INTERCEPTORS
$axios.interceptors.request.use (
    function (config) {
        //mengakses state di store.js untuk mengambil token
        const token = store.state.token
        if (token) config.headers.Authorization = `Bearer ${token}`;
        return config;
    },
    function (error) {
        return Promise.reject (error);
    }
);

export default $axios;

// file ini berisi konfigurasi global dari axios. 
// Konfigurasi global-nya adalah dimana sebuah url yang digunakan untuk melakukan request memiliki prefix /api, contohnya /api/login. Selanjutnya pada bagian headers akan mengirimkan Authorization berdasarkan token yang didapatkan dari local storage dan dengan content type json.
