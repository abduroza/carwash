<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Add New Expense</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <expense-form></expense-form>
            <!-- tombol add untuk menambahkan -->
            <div class="form-group">
                <button class="btn btn-primary btn-sm" @click.prevent="submit" :disabled="isLoading">
                    <span v-if="!isLoading" class="fa fa-save"> Add New</span>
                    <div v-else-if="isLoading">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                    </div>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import FormExpense from './Form.vue'
import { mapActions, mapState } from 'vuex'
export default {
    name: 'AddExpense',
    computed: {
        ...mapState('expense', {
            isLoading: state => state.isLoading
        })
    },
    methods: {
        ...mapActions('expense', ['submitExpense']),
        submit(){
            this.submitExpense()
            .then(() => {
                //jika berhasil redirect ke index
                this.$router.push({ name: 'expenses.data' })
            })
        }
    },
    components: {
        'expense-form' : FormExpense
    }
}
</script>
