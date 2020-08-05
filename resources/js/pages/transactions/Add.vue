<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Add New Transaction</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <transaction-form ref="form"></transaction-form>
            <!-- tombol add untuk menambahkan -->
            <div class="form-group">
                <button class="btn btn-primary btn-sm" @click.prevent="submit" :disabled="isLoading">
                    <span v-if="!isLoading" class="fa fa-save"> Create Transaction</span>
                    <div v-else-if="isLoading">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                    </div>
                </button>
                <button class="btn btn-danger btn-sm" @click.prevent="clearForm">
                    Clear Form
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions, mapState } from 'vuex'
import FormTransaction from './Form.vue'
export default {
    name: 'AddTransaction',
    computed: {
        ...mapState('transaction', {
            isLoading: state => state.isLoading
        })
    },
    methods: {
        ...mapActions('transaction', ['submitTransaction']), //panggil submitTransaction di transaction.js
        // submit(){
        //     this.submitTransaction()
        //     .then(() => {
        //         //jika berhasil redirect ke list product
        //         this.$router.push({ name: 'transactions.data'})
        //     })
        // }
        submit() {
            this.$refs.form.submit() //DIMANA KITA MENGINSTRUKSIKAN UNTUK MENJALANKAN METHOD submit() PADA FILE FORM.VUE MELALUI REFS
        },
        clearForm() {
            this.$refs.form.resetForm() //menjalankan method resetForm() pada file Form.vue melalui refs
        }
    },
    components: {
        'transaction-form': FormTransaction
    }
}
</script>
