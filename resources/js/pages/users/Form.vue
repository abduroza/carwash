<template>
    <div>
        <div v-if="errors.message">
            <!-- :show="dismissCount" untuk mendefinisikan berapa detik waktu tampil. di data harus di set waktunya. misal dismissCount: 5 -->
            <b-alert dismissible fade variant="danger" show>
                {{ errors.message }}
            </b-alert>
        </div>
        <!-- class error untuk memberikan warna merah pada form jika ada error. error ini didapatkan dari laravel-->
        <div class="form-group"> 
            <label for="" >Nama User</label>
            <input type="text" class="form-control" v-model="user.name" :class="{'is-invalid' : errors.name}">
            <!-- untuk menampilkan error apa yg terjadi -->
            <p class="text-danger" v-if="errors.name">{{ errors.name[0]}}</p>
        </div>
        <div class="form-group"> 
            <label for="" >Email</label>
            <input type="email" class="form-control" v-model="user.email" :class="{'is-invalid' : errors.email}">
            <p class="text-danger" v-if="errors.email">{{ errors.email[0]}}</p>
        </div>
        <div class="form-group"> 
            <label for="" >Password</label>
            <input type="password" class="form-control" v-model="user.password"  :class="{'is-invalid' : errors.password}">
            <small class="text-warning" v-if="$route.name == 'user.edit'">Leave blank if you don't want to change password</small>
            <p class="text-danger" v-if="errors.password">{{ errors.password[0]}}</p>
        </div>
        <div class="form-group" :class="{'has-error' : errors.role}">
            <label for="">Role User</label>
            <div class="col-md-auto" >
                <label class="btn btn-outline-secondary btn-sm">
                    <input type="radio" v-model="user.role" value="1"> Admin
                </label>
                <label class="btn btn-outline-secondary btn-sm">
                    <input type="radio" v-model="user.role" value="2"> Finance
                </label>
                <label class="btn btn-outline-secondary btn-sm">
                    <input type="radio" v-model="user.role" value="3"> Operator
                </label>
            </div>
            <p class="text-danger" v-if="errors.role">{{ errors.role[0]}}</p>
        </div>
        <div class="form-group">
            <label for="">Outlet</label>
            <select name="outlet_id" class="form-control" v-model="user.outlet_id" :class="{ 'is-invalid': errors.outlet_id }">
                <option value="">Pilih</option>
                <option v-for="(row, index) in outlets.data" :value="row.id" :key="index">{{ row.name }}</option>
            </select>
            <p class="text-danger" v-if="errors.outlet_id">{{ errors.outlet_id[0] }}</p>
        </div>
        <div class="form-group">
            <label for="">Foto</label>
            <!-- "uploadImage($event)" mengambil data dari fungsi di methods -->
            <input type="file" class="form-control" accept="image/*" @change="uploadImage($event)" id="file-input"  :class="{ 'is-invalid': errors.photo }">
            <!-- 'user.edit' mengambil dari router.js -->
            <small class="text-warning" v-if="$route.name == 'user.edit'" >Leave blank if you don't want to change photo</small>
            <p class="text-danger" v-if="errors.photo">{{ errors.photo[0] }}</p>
        </div>
    </div>
</template>
<script>
import { mapState, mapMutations, mapActions } from 'vuex'
export default {
    name: 'FormUser',
    created() {
        //menjalankan fungsi getOutlets() dari methods untuk mendapatkan data outlet supaya bisa di render oleh select
        this.getOutletsNoPage() //atau this.$store.dispatch('outlet/getOutletsNoPage')
    },
    computed: {
        ...mapState(['errors']), //mengambil mapstate error dari
        ...mapState('user', {
            user: state => state.user, //mengambil dan mengisi state user di user.js
        }),
        ...mapState('outlet', {
            outlets: state => state.outlets //mengambil state outlets dari outlets.js
        }),
    },
    methods: {
        ...mapMutations('user', ['CLEAR_FORM']), //memanggil mutation CLEAR_FORM dari user.js utl keperluan fungsi destroyed()
        ...mapActions('outlet', ['getOutletsNoPage']), //memanggil action getOutlets() dari outlet.js
        //KETIKA TERJADI PENGINPUTAN GAMBAR, MAKA FILE BERIKUT AKAN DI ASSIGN KE DALAM user.photo
        uploadImage(event){
            //mengakses fungsi state.user di user.js melalui computed diatas
            this.user.photo = event.target.files[0]
        },
    },
    //ketika halaman form ditinggalkan
    destroyed(){
        //form dibersihkan
        this.CLEAR_FORM()
    }
}
</script>
