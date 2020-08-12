<template>
    <div class="col-md-12">
        <div class="row mt-4 px-4">
            <div class="col-md-6" v-if="order.isPaid == 0">
                <h4>Payment</h4>
                <hr>
                <!-- form transaction -->
                <div class="form-group">
                    <label for=""><strong>Tagihan</strong></label>
                    <input type="text" :value="order.amount | currency" class="form-control w-50" readonly>
                </div>
                <div class="form-group" v-if="order.customer.deposite.amount >= 1">
                    <input type="checkbox" v-model="via_deposite"> Bayar via deposit?
                </div>
                <div class="form-group" v-if="via_deposite">
                    <!-- <label for="">Jumlah Bayar Menggunakan Deposit</label> -->
                    <input v-if="via_deposite" type="number" class="form-control w-50" :value="amount_via_deposite" readonly>
                    <br>
                    <small v-if="via_deposite">
                        Jumlah yang harus dibayarkan lagi <strong>{{ order.amount - amount_via_deposite | currency }}</strong>    
                    </small>
                </div>
                <!-- jika tagihan masih lebih besar dari jml_via_deposit -->
                <div class="form-group" v-if="order.amount > amount_via_deposite">
                    <label for=""><strong>Jumlah Bayar</strong></label>
                    <input type="number" class="form-control w-50" v-model="amount_via_cash" :class="{ 'is-invalid': payment_message }">
                    <p class="text-danger" v-if="payment_message">{{ payment_message }}</p>
                </div>
                <p v-if="isCustomerChange">Kembalian: {{ customerChangeAmount | currency }}</p>
                <div class="form-group" v-if="isCustomerChange">
                    <input type="checkbox" v-model="customer_change" id="customer_change">
                    <label for="customer_change"> Kembalian jadi deposit?</label>
                </div>
                <button class="btn btn-primary btn-sm" :disabled="isLoading" @click="makePayment">
                    <i v-if="!isLoading" class="fa fa-money"> Bayar</i>
                    <div v-else-if="isLoading">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                    </div>
                </button>
            </div>
            <div class="col-md-6">
                <!-- menampilkan data customer -->
                <h4>Customer Info</h4>
                <hr>
                <div v-if="order.customer">
                    <!-- ditambahkan [], karena :items merender array. sedangkan data dari orders.customer bentuknya object -->
                    <b-table stacked small responsive="sm" :items="[order.customer]" :fields="fieldCustomer">
                        <template v-slot:cell(deposite)="row" v-if="order.customer.deposite">
                            {{ row.item.deposite.amount | currency }}
                        </template>
                    </b-table>
                </div>
            </div>
            <div class="col-md-6">
                <!-- menampilkan riwayat order tersebut -->
                <div v-if="order.payment">
                    <h4>Riwayat Pembayaran</h4>
                    <hr>
                    <!-- ditambahkan [], karena :items merender array. sedangkan data dari order.customer bentuknya object -->
                    <b-table stacked small responsive="sm" :items="[order.payment]" :fields="fieldPayment">
                        <template v-slot:cell(amount)="row">
                            {{ row.item.amount | currency }}
                        </template>
                        <template v-slot:cell(customer_change)="row">
                            {{ row.item.customer_change | currency }}
                        </template>
                        <template v-slot:cell(type)="row">
                            <span v-if="row.item.type == 0">Cash</span>
                            <span v-else-if="row.item.type == 1">Deposite</span>
                            <span v-else-if="row.item.type == 2">Cash + Deposite</span>
                        </template>
                    </b-table>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12 pt-2">
                <!-- alert diganti toast -->
                <!-- <div class="alert alert-success" v-if="payment_success">Pembayaran Berhasil</div> -->
                <hr>
                <h4>Transaction</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Tipe</th>
                                <th>Size</th>
                                <th width="28%">Waktu Layanan</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Looping transaksi. transaksi dalam 1 kali order bisa lebih dari 1 -->
                            <tr v-for="(row, index) in order.transaction" :key="index">
                                <td>
                                    <!-- row.product.name. di table transaction tidak ada field product. namun di modelnya ada relasi ke product() yg mengarah ke model Product yg didalammya ada field name -->
                                    <strong>{{ row.product.name }}</strong>
                                    <!-- row.status_label adalah field virtual yg dibuat di model Transaction -->
                                    <!-- karena status_label mereturn html yg berupa span, shg pakai v-html -->
                                    <sup v-html="row.status_label"></sup>
                                </td>
                                <td>{{ row.type.name }}</td>
                                <td>{{ row.size }}</td>
                                <td>{{ row.time }} </td>
                                <td>{{ row.quantity }}</td>
                                <td>{{ row.price | currency }}</td>
                                <td>{{ row.subtotal | currency }}</td>
                                <td>
                                    <!-- tombol untuk menyelesaikan orderan atau istilahnya checkout -->
                                    <!-- Tombol ini ditampilkna jika sudah dibayar (status = 1 di table orders) dan statusnya masih proses / customer belum checkout/keluar(status = 0 di table transactions) -->
                                    <button class="btn btn-success btn-sm" v-if="order.isPaid == 1  && row.status == 0" @click="isDone(row.id)">
                                        <!-- ketika diklik akan menjalankan fungsi isDone() dan mengirimkan parameter id transaksi -->
                                        <i class="fa fa-paper-plane-o"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions, mapMutations } from 'vuex'
import moment from 'moment'
export default {
    name: 'ViewTransaction',
    created(){
        this.viewTransaction(this.$route.params.id)
    },
    data(){
        return {
            amount_via_cash: 0, //menyimpan jumlah bayar yg harus diinputkan sesuai uang dari customer
            amount_via_deposite: 0, //menyimpan jumlah bayar khusus dari deposite
            customer_change: 0, //default di set ke 0 (false). tidak dimasukkan ke deposit
            via_deposite: false, //jika di centang maka akan bernilai true. ini adalah field type (0 => cash, 1 => deposit)
            payment_message: null,
            // payment_success: false,

            fieldCustomer: [
                { key: 'name', label: 'Nama' },
                { key: 'nik', label: 'NIK' },
                { key: 'address', label: 'Alamat' },
                { key: 'phone', label: 'No. Telp' },
                { key: 'point', label: 'Point' },
                { key: 'deposite', label: 'Deposite' }
            ],
            fieldPayment: [
                { key: 'amount', label: 'Jumlah Pembayaran' },
                { key: 'customer_change', label: 'Kembalian' },
                { key: 'type', label: 'Tipe Pembayaran' }
            ]
        }
    },
    computed: {
        ...mapState(['success']),
        ...mapState('transaction', {
            order: state => state.order,
            isLoading: state => state.isLoading
        }),
        //untuk menampilkan form uang kembalian. ini hanya mereturn true atau false
        isCustomerChange(){
            return parseFloat(this.amount_via_cash) + parseFloat(this.amount_via_deposite) > parseFloat(this.order.amount) //me return true atau false sesuai kondisi
        },
        customerChangeAmount(){
            return parseFloat(this.amount_via_cash ) + parseFloat(this.amount_via_deposite) - parseFloat(this.order.amount)
        }
    },
    watch: {
        //jika value via_deposite berubah
        //fungsi ini untuk memberi inputan otomatis ke dalam amount_via_cash jika via_deposite di centang
        via_deposite(){
            //jika via_deposite bernilai true
            if(this.via_deposite){
                //jika jumlah tagihan lebih besar atau sama dengan jumlah deposite
                if(this.order.amount >= this.order.customer.deposite.amount){
                    //set inputan amount_via_deposite menjadi nilai sejumlah deposit yg dimiliki
                    this.amount_via_deposite = this.order.customer.deposite.amount
                } else { //jika jumlah tagihan lebih kecil
                    //set inputan amount_via_deposite sesuai jumlah order
                    this.amount_via_deposite = this.order.amount
                }
            } else {
                //jika via_deposite bernilai false, set amount_via_deposite menjadi 0 lagi
                this.amount_via_deposite = 0
            }
        }
    },
    methods: {
        ...mapActions('transaction', ['viewTransaction', 'payment', 'completeItem']),
        ...mapMutations('transaction', ['CLEAR_FORM']),
        makeToast(variantt = null) {
            if(this.success != null){
                this.$bvToast.toast(this.success.message, {
                    title: this.success.status,
                    variant: variantt,
                    solid: true,
                    autoHideDelay: 10000,
                })
            }
        },
        makePayment(){
            //jika jumlah pembayran kurang dari tagihan
            if(parseFloat(this.amount_via_cash) + parseFloat(this.amount_via_deposite) < parseFloat(this.order.amount)){
                this.payment_message = "Jumlah pembayaran kurang dari tagihan"
                return //menghentikan proses dan keluar dari method makePayment()
            }
            this.loading = true
            this.payment({
                order_id: this.$route.params.id,
                amount_via_cash: this.amount_via_cash,
                amount_via_deposite: this.amount_via_deposite,
                customer_change: this.customer_change,
                type: this.via_deposite
            }).then((res) => {
                this.makeToast('success')
                //jika pembayaran berhasil
                if(res.status == 'Success'){
                    // this.payment_success = true
                    //semua variable di reset semua
                    setTimeout(() => {
                        this.amount_via_cash = 0,
                        this.amount_via_deposite = 0,
                        this.customer_change = 0,
                        this.payment_message = null
                    }, 500);
                    //load data transaksi terbaru. Jika tidak diambil data terbaru, tombol actions tidak muncul, karena tidak diperbaharui
                    this.viewTransaction(this.$route.params.id)
                } else {
                    //jika gagal, tampilkan alert gagal
                    alert(res.data)
                }
            }).catch((err) => {
                //jika gagal, tampilkan alert gagal
                alert(res.data)
            })
        },
        //ketikan tombol masing2 transaksi di klik, karena customer sudah checkout/pulang dan ambil motornya
        isDone(id){
            this.$swal({
                title: 'Konfirmasi Pengambilan Barang',
                text: "Transaksi akan selesai",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Lanjutkan!'
            }).then((result) => {
                if(result.value){
                    this.completeItem({ id: id })
                    .then(() => {
                        this.makeToast('warning')
                        //perbaharui data lagi
                        this.viewTransaction(this.$route.params.id)
                    })
                }
            })
        }
    },
    destroyed(){
        //menghapus state success di store.js saat form ini ditutup
        this.$store.commit('SET_SUCCESS', null) //mengakses mutations di root module
        this.CLEAR_FORM() //memanggil CLEAR_FORM untuk menghapus state order di transaction.js
    }
}
</script>
