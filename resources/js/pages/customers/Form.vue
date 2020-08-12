<template>
    <div>
        <div v-if="errors.message">
            <!-- :show="dismissCount" untuk mendefinisikan berapa detik waktu tampil. di data harus di set waktunya. misal dismissCount: 5 -->
            <b-alert dismissible fade variant="danger" show>
                {{ errors.message }}
            </b-alert>
        </div>
        <!-- class error untuk memberikan warna merah pada form jika ada error. error ini didapatkan dari laravel-->
        <div class="form-group" > 
            <label for="" >NIK</label>
            <input type="number" class="form-control" :class="{ 'is-invalid': errors.nik }" v-model="customer.nik">
            <!-- untuk menampilkan error apa yg terjadi -->
            <p class="text-danger" v-if="errors.nik">{{ errors.nik[0]}}</p>
        </div>
        <div class="form-group" > 
            <label for="" >Nama Lengkap</label>
            <input type="text" class="form-control" :class="{ 'is-invalid': errors.name }" v-model="customer.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0]}}</p>
        </div>
        <div class="form-group" > 
            <label for="" >No Telp</label>
            <input type="number" class="form-control" :class="{ 'is-invalid': errors.phone }" v-model="customer.phone">
            <p class="text-danger" v-if="errors.phone">{{ errors.phone[0]}}</p>
        </div>
        <div class="form-group"> 
            <label for="" >Alamat</label>
            <textarea cols="5" rows="3" class="form-control" v-model="customer.address" :class="{'is-invalid' : errors.address}"></textarea>
            <p class="text-danger" v-if="errors.address">{{ errors.address[0]}}</p>
        </div>
        <div class="form-group">
            <label for="">Deposit</label>
            <input type="number" class="form-control" v-model="customer.amount" :class="{ 'is-invalid': errors.amount }">
            <p class="text-danger" v-if="errors.amount">{{ errors.amount[0] }}</p>
        </div>
    </div>
</template>
<script>
import { mapState, mapMutations } from 'vuex'
export default {
    name: 'FormCustomer',
    computed: {
        ...mapState(['errors']),
        ...mapState('customer', {
            customer: state => state.customer //mengambil dan mengirim customer ke state customer.js
        }),
    },
    methods: {
        ...mapMutations('customer', ['CLEAR_FORM']),
    },
    destroyed(){
        this.CLEAR_FORM()
    }
}
</script>
