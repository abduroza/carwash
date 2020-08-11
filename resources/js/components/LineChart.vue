<script>
import { Line } from 'vue-chartjs'
export default {
    extends: Line,
    props: ['data', 'options', 'labels'], //ketika component ini digunakan akan meminta data sebagai props
    mounted(){
        this.lineRenderChart()
    },
    watch: {
        //ketika terjadi perubahan value dari props data
        data: {
            handler(){
                this._data._chart.destroy(), //maka hapus chart
                this.lineRenderChart() //render kembali dengan data terbaru
            },
            deep: true
        }
    },
    methods: {
        lineRenderChart(){
            this.renderChart({
                labels: this.labels, //labelnya berdasarkan props labels (valuenya diisi dg list tanggal selama 1 bulan)
                datasets: [{
                    label: 'Data Transaksi',
                    data: this.data, //data yg akan menjadi chart (total transaksi perhari)
                    backgroundColor: [ //warna background kurva
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [ //warna garis kurva
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 2 //tebal garis kurva
                }]
            }, this.options)
        }
    }
}
</script>
