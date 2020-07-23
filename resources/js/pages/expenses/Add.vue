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
                <button class="btn btn-primary btn-sm" @click.prevent="submit">
                    <i class="fa fa-save"></i> Add New
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import FormExpense from './Form.vue'
import { mapActions } from 'vuex'
export default {
    name: 'AddExpense',
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
