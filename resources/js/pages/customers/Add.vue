<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Add New Customer</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <customer-form></customer-form>
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
import FormCustomer from './Form.vue'
import { mapActions, mapState } from 'vuex'
export default {
    name: 'AddCustomer',
    computed: {
        ...mapState('customer', {
            isLoading: state => state.isLoading
        })
    },
    methods: {
        ...mapActions('customer', ['submitCustomer']),
        submit(){
            this.submitCustomer().then(() => this.$router.push({ name: 'customers.data' }))
        }
    },
    components: {
        'customer-form': FormCustomer
    }
}
</script>
