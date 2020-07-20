<template>
    <div>
        <!-- class error untuk memberikan warna merah pada form jika ada error. error ini didapatkan dari laravel-->
        <div class="form-group" > 
            <label for="" >Nama Product</label>
            <input type="text" class="form-control" :class="{ 'is-invalid': errors.name }" v-model="product.name">
            <!-- untuk menampilkan error apa yg terjadi -->
            <p class="text-danger" v-if="errors.name">{{ errors.name[0]}}</p>
        </div>
        <div class="row">
            <div class="col-md-6">
              	<!-- KETIKA TOMBOL ADD NEW DITEKAN -->
                <div class="form-group">
                    <label for="">Tipe Kendaraan 
                        <sup>
                            <a @click="showFormType = true" href="javascript:void(0)" v-if="!showFormType">Add New</a>
                        </sup>
                    </label>
                    <select class="form-control" v-model="product.type_id" :class="{ 'is-invalid': errors.type_id }">
                        <option value="">Pilih</option>
                        <option v-for="(row, index) in types" :value="row.id" :key="index">{{ row.name }}</option>
                    </select>
                    <p class="text-danger" v-if="errors.type_id">{{ errors.type_id[0] }}</p>
                </div>
            </div>
          	<!-- MAKA FORM UNTUK MENAMBAHKAN JENIS LAUNDRY AKAN DITAMPILKAN -->
            <div class="col-md-6" v-if="showFormType">
                <div class="form-group">
                    <label for="">&nbsp;</label>
                    <div class="input-group">
                        <input type="text" placeholder="Mobil, Motor, Truk..." v-model="type.name_type" class="form-control" :class="{ 'is-invalid': errors.name_type }">
                        <div class="input-group-append">
                            <!-- <button class="btn btn-outline-secondary" type="button">Button</button> -->
                            <a  href="javascript:void(0)" class="btn btn-outline-primary" id="basic-addon2" @click="addNewType">Save</a>
                        </div>
                        
                    </div>
                    <p class="text-danger" v-if="errors.name_type">{{ errors.name_type[0] }}</p>
                </div>
            </div>
          	<!-- END FORM ADD JENIS LAUNDRY -->
        </div>
        <div class="form-group">
            <label for="">Size</label>
            <div class="col-md-auto">
                <label class="btn btn-outline-secondary btn-sm">
                    <input type="radio" v-model="product.size" value="Small"> Small
                </label>
                <label class="btn btn-outline-secondary btn-sm">
                    <input type="radio" v-model="product.size" value="Medium"> Medium
                </label>
                <label class="btn btn-outline-secondary btn-sm">
                    <input type="radio" v-model="product.size" value="Large"> Large
                </label>
                <label class="btn btn-outline-secondary btn-sm">
                    <input type="radio" v-model="product.size" value="Extra Large"> Extra Large
                </label>
            </div>
            <p class="text-danger" v-if="errors.size">{{ errors.size[0]}}</p>
        </div>
        <div class="form-group">
            <label for="">Harga</label>
            <input type="number" class="form-control" v-model="product.price" :class="{ 'is-invalid': errors.price }">
            <p class="text-danger" v-if="errors.price">{{ errors.price[0] }}</p>
        </div>
        <div class="form-group"> 
            <label for="" >Deskripsi</label>
            <textarea cols="5" rows="2" class="form-control" v-model="product.description" :class="{'is-invalid' : errors.description}"></textarea>
            <p class="text-danger" v-if="errors.description">{{ errors.description[0]}}</p>
        </div>
    </div>
</template>
<script>
import { mapState, mapMutations, mapActions } from 'vuex'
export default {
    name: 'FormProduct',
    created() {
        //menjalankan fungsi getTypes() dari methods untuk mendapatkan data type supaya bisa di render oleh select
        this.getTypes()
    },
    data(){
        return {
            showFormType: false //default formType adalah false atau form tidak ditampilkan
        }
    },
    computed: {
        ...mapState(['errors']),  //mengambil mapstate error dari
        ...mapState('product', {
            product: state => state.product //mengambil dan mengisi state product dari product.js
        }),
        ...mapState('type', {
            types: state => state.types, //mengambil state types di type.js
            type: state => state.type, //mengambil dan mengisi state type di type.js
        }),
    },
    methods: {
        ...mapMutations('product', ['CLEAR_FORM']), //memanggil mutation CLEAR_FORM dari product.js utk keperluan fungsi destroyed()
        ...mapActions('type', ['getTypes', 'addType']),
        ...mapMutations('type', ['CLEAR_FORM_TYPE']),
        //buat type baru
        addNewType(){
            this.addType().then(() => {
                this.CLEAR_FORM_TYPE() //hapus form type setelah berhasil membuat type baru
                this.getTypes().then(() => {
                    this.showFormType = false //tutup form isian type setlah berhasil membuat type baru
                })
            })
        },
        //ketika halaman form ditinggalkan
        destroyed() {
            //form dibersihkan
            this.CLEAR_FORM()
        }
    }
}
</script>
