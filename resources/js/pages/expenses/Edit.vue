<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Edit Expense</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <expense-form></expense-form>
            <!-- tombol update untuk mengupdate data terbaru -->
            <div class="form-group">
                <button class="btn btn-primary btn-sm" @click.prevent="submit">
                    <i class="fa fa-save"></i> Update
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import FormExpense from './Form.vue'
import { mapActions } from 'vuex'
export default {
    name: 'EditExpense',
    created() {
        //ketika form edit dibuka, maka lamngsung memanggil editExpense
        this.editExpense(this.$route.params.id)
    },
    methods: {
        ...mapActions('expense', ['editExpense', 'updateExpense']),
        submit(){
            this.updateExpense(this.$route.params.id)
            .then(() => {
                this.$router.push({ name: 'expenses.data' })
            })
        }
    },
    components: {
        'expense-form': FormExpense
    }
    
}
</script>
