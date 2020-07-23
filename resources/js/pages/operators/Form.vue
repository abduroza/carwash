<template>
    <div>
        <!-- class error untuk memberikan warna merah pada form jika ada error. error ini didapatkan dari laravel-->
        <div class="form-group"> 
            <label for="" >Nama User</label>
            <input type="text" class="form-control" v-model="operator.name" :class="{'is-invalid' : errors.name}">
            <!-- untuk menampilkan error apa yg terjadi -->
            <p class="text-danger" v-if="errors.name">{{ errors.name[0]}}</p>
        </div>
        <div class="form-group"> 
            <label for="" >Email</label>
            <input type="email" class="form-control" v-model="operator.email" :class="{'is-invalid' : errors.email}">
            <p class="text-danger" v-if="errors.email">{{ errors.email[0]}}</p>
        </div>
        <div class="form-group"> 
            <label for="" >Password</label>
            <input type="password" class="form-control" v-model="operator.password"  :class="{'is-invalid' : errors.password}">
            <small class="text-warning" v-if="$route.name == 'operator.edit'">Leave blank if you don't want to change password</small>
            <p class="text-danger" v-if="errors.password">{{ errors.password[0]}}</p>
        </div>
        <div class="form-group">
            <label for="">Outlet</label>
            <select name="outlet_id" class="form-control" v-model="operator.outlet_id" :class="{ 'is-invalid': errors.outlet_id }">
                <option value="">Pilih</option>
                <option v-for="(row, index) in outlets.data" :value="row.id" :key="index">{{ row.name }}</option>
            </select>
            <p class="text-danger" v-if="errors.outlet_id">{{ errors.outlet_id[0] }}</p>
        </div>
        <div class="form-group">
            <label for="">Foto</label>
            <!-- "uploadImage($event)" mengambil data dari fungsi di methods -->
            <input type="file" class="form-control" accept="image/*" @change="uploadImage($event)" id="file-input"  :class="{ 'is-invalid': errors.photo }">
            <!-- 'operator.edit' mengambil dari router.js -->
            <small class="text-warning" v-if="$route.name == 'operator.edit'" >Leave blank if you don't want to change photo</small>
            <p class="text-danger" v-if="errors.photo">{{ errors.photo[0] }}</p>
        </div>
    </div>
</template>
<script>
import { mapState, mapMutations, mapActions } from 'vuex'
export default {
    name: 'FormOperator',
    created() {
        //menjalankan fungsi getOutlets() dari methods untuk mendapatkan data outlet supaya bisa di render oleh select
        this.getOutletsNoPage() //atau this.$store.dispatch('outlet/getOutletsNoPage')
    },
    computed: {
        ...mapState(['errors']), //mengambil mapstate error dari
        ...mapState('operator', {
            operator: state => state.operator, //mengambil dan mengisi state operator di operator.js
        }),
        ...mapState('outlet', {
            outlets: state => state.outlets //mengambil state outlets dari outlets.js
        }),
    },
    methods: {
        ...mapMutations('operator', ['CLEAR_FORM']), //memanggil mutation CLEAR_FORM dari operator.js utl keperluan fungsi destroyed()
        ...mapActions('outlet', ['getOutletsNoPage']), //memanggil action getOutlets() dari outlet.js
        //KETIKA TERJADI PENGINPUTAN GAMBAR, MAKA FILE BERIKUT AKAN DI ASSIGN KE DALAM user.photo
        uploadImage(event){
            //mengakses fungsi state.operator di operator.js melalui computed diatas
            this.operator.photo = event.target.files[0]
        },
    },
    //ketika halaman form ditinggalkan
    destroyed(){
        //FORM DI BERSIHKAN
        this.CLEAR_FORM()
    }
}
</script>
