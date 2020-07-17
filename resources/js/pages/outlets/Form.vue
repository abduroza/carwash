<template>
    <div>
        <!-- class error untuk memberikan warna merah pada form jika ada error. error ini didapatkan dari laravel-->
        <div class="form-group" :class="{'has-error' : errors.code}"> 
            <label for="" >Kode Outlet</label>
            <!-- readonly bernilai true jika route name yg diakses adalah operator.edit -->
            <input type="text" class="form-control" v-model="outlet.code" :readonly="$route.name == 'outlets.edit'">
            <!-- untuk menampilkan error apa yg terjadi -->
            <p class="text-danger" v-if="errors.code">{{ errors.code[0]}}</p>
        </div>
        <div class="form-group" :class="{'has-error' : errors.name}"> 
            <label for="" >Nama Outlet</label>
            <input type="text" class="form-control" v-model="outlet.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0]}}</p>
        </div>
        <div class="form-group" :class="{'has-error' : errors.address}"> 
            <label for="" >Alamat</label>
            <textarea cols="5" rows="4" class="form-control" v-model="outlet.address"></textarea>
            <p class="text-danger" v-if="errors.address">{{ errors.address[0]}}</p>
        </div>
        <div class="form-group" :class="{'has-error' : errors.phone}"> 
            <label for="" >No. Telp</label>
            <input type="number" class="form-control" v-model="outlet.phone">
            <p class="text-danger" v-if="errors.phone">{{ errors.phone[0]}}</p>
        </div>
        <div class="form-group">
            <input type="checkbox" v-model="outlet.status">
            <label for=""> Set Active</label>
        </div>
    </div>
</template>
<script>
import {mapState, mapMutations} from 'vuex'
export default {
    name: 'FormOutlet',
    computed: {
        ...mapState(['errors']), //mengambil state error dari 
        ...mapState('outlet', {
            outlet: state => state.outlet //MENGAMBIL STATE OUTLET dari outlet.js
        }),
    },
    methods: {
        ...mapMutations('outlet', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM dari outlet.js
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA 
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_FORM()
    }
}
</script>
<!-- Pada form diatas, kita melakukan pengecekan error validasi yang dihasilkan oleh Laravel. Misalnya, apabila errors.name ada, maka class has-error akan ditambahkan. Selain itu tag p dengan class text-danger yang berisi pesan error akan ditampilkan.--->
