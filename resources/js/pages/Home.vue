<template>
    <div class="container">
        <!-- header -->
        <div class="row" style="padding: 8px 0 8px 0">
            <div class="col-6">
                <h4>
                    Dashboard
                </h4>
            </div>
            <div class="col-6">
                <nav aria-label="breadcrumb" class="float-right mb-lg-2">
                    <ol class="breadcrumb d-inline-flex my-0 px-2 py-1 bg-transparent">
                        <li class="breadcrumb-item">
                            <router-link :to="{ name: 'home' }" class="text-decoration-none text-dark">
                                <i class="fa fa-home"></i>
                                Home
                            </router-link>
                        </li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- end header -->
        <!-- content -->
        <div class="col-md-12 bg-white mb-3">
            <div class="row pt-3">
                <!-- form filter berdasarkan tahun -->
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="">Bulan</label>
                        <select v-model="month" class="form-control">
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                </div>
                <!-- form filter berdasarkan tahun -->
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="">Tahun</label>
                        <select v-model="year" class="form-control">
                            <option v-for="(y, index) in years" :key="index" :value="y">{{ y }}</option>
                        </select>
                    </div>
                </div>
                <!-- tombol export -->
                <div class="col-md-2 pt-4">
                    <div class="btn-group pull-right pt-2">
                        <button type="button" class="btn btn-outline-primary">Download</button>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <button @click="exportData" :disabled="loading" class="dropdown-item" type="button">
                                <i class="fa fa-file-excel-o"></i> Excel
                            </button>
                            <button :disabled="loading" class="dropdown-item" type="button">
                                <i class="fa fa-file-pdf-o"></i> PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <!-- menampilkan chart dari component LineChart.vue dengan mengirimkan data (data, labels, options) sebagai props ke LineChart.vue -->
                <line-chart v-if="orders.length > 0" :data="order_data" :labels="labels" :options="chartOptions"></line-chart>
            </div>
        </div>
        <!-- end content -->
    </div>
</template>
<script>
import moment from 'moment'
import _ from 'lodash'
import LineChart from '../components/LineChart.vue'
import Breadcrumb from '../components/Breadcrumb.vue'
import { mapState, mapActions } from 'vuex'

export default {
    created(){
        this.getChartData({
            month: this.month,
            year: this.year
        })
    },
    data(){
        return {
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            month: moment().format('MM'), //default bulan adalah bulan sekarang
            year: moment().format('Y'), //default tahun adalah tahun sekarang
            loading: false
        }
    },
    computed: {
        ...mapState('dashboard', {
            orders: state => state.orders
        }),
        //mengambil data tahun dari moment untuk ditampilkan di tag select
        years(){
            return _.range(2015, moment().add(1, 'years').format('Y'))
        },
        //data label yg diterima dari server
        labels(){
            //karena format datanya hanya date dan total, maka kita ambil hanya date nya saja
            return _.map(this.orders, function(o){
                return moment(o.date).format('DD')
            })
        },
        //total amount di table order yg di get dari server
        order_data(){
            //mengambil hanya total amount
            return _.map(this.orders, function(o){
                return o.total
            })
        }
    },
    watch: {
        //ketika value bulan di data berubah
        month(){
            this.getChartData({
                month: this.month,
                year: this.year
            })
        },
        //ketika value tahun di data berubah
        year(){
            this.getChartData({
                month: this.month,
                year: this.year
            })
        }
    },
    methods: {
        ...mapActions('dashboard', ['getChartData']),
        exportData(){
            this.loading = true
            window.open(`/api/export?api_token=${localStorage.getItem('token')}&month=${this.month}&year=${this.year}`)
            this.loading = false            
        }
    },
    mounted(){
        //setelah login, user diarahkan ke home. jika halaman tidak direload, maka kondisinya masih seperti sebelum logout. sehingga perlu direload 1 kali.
        if (localStorage.getItem('reloaded')) {
            // The page was just reloaded. Clear the value from local storage
            // so that it will reload the next time this page is visited.
            localStorage.removeItem('reloaded');
        } else {
            // Set a flag so that we know not to reload the page twice.
            localStorage.setItem('reloaded', '1');
            location.reload();
        }
    },
    components: {
        'breadcrumb' : Breadcrumb,
        'line-chart' : LineChart
    }
}
</script>
