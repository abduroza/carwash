<template>
    
    <div>
        <div v-if="errors.message">
            <b-alert dismissible fade variant="danger" :show="dismissCount">
                {{ errors.message }} Check your internet connection
            </b-alert>
        </div>
        <!-- <p class="text-danger" v-if="errors.message">{{ errors.message}}</p> -->
        <!-- class error untuk memberikan warna merah pada form jika ada error. error ini didapatkan dari laravel-->
        <div class="form-group">
            <label for="" >Permintaan</label>
            <!-- readonly bernilai true jika route name yg diakses adalah operator.edit -->
            <input type="text" class="form-control" v-model="expense.title" :class="{'is-invalid' : errors.title}">
            <!-- untuk menampilkan error apa yg terjadi -->
            <p class="text-danger" v-if="errors.title">{{ errors.title[0]}}</p>
        </div>
        <div class="form-group"> 
            <label for="" >Harga</label>
            <input type="number" class="form-control" v-model="expense.price" :class="{'is-invalid' : errors.price}">
            <p class="text-danger" v-if="errors.price">{{ errors.price[0]}}</p>
        </div>
        <div class="form-group"> 
            <label for="" >Catatan</label>
            <textarea cols="5" rows="3" class="form-control" v-model="expense.note" :class="{'is-invalid' : errors.note}"></textarea>
            <p class="text-danger" v-if="errors.note">{{ errors.note[0]}}</p>
        </div>
    </div>
</template>
<script>
import { mapState, mapMutations } from 'vuex'
export default {
    name: "FormExpense",
    data(){
        return {
            dismissCount: true, //bisa diisi dengan integer. misal 5. berarti 5 detik
        }
    },
    computed: {
        ...mapState(['errors']),
        ...mapState('expense', {
            expense: state => state.expense
        })
    },
    methods: {
        ...mapMutations('expense', ['CLEAR_FORM']),
    },
    destroyed(){
        this.CLEAR_FORM()
    }
}
</script>
