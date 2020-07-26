<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Edit Outlet</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <customer-form></customer-form>
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
import { mapActions } from 'vuex'
import FormCustomer from './Form.vue'
export default {
    name: 'EditCustomer',
    created() {
        //sebelum component di render, request ke server sesuai dg id untuk mendapatkan data yg mau di update
        this.editCustomer(this.$route.params.id)
    },
    methods: {
        ...mapActions('customer', ['editCustomer', 'updateCustomer']),
        submit(){
            this.updateCustomer(this.$route.params.id).then(() => this.$router.push({ name: 'customers.data'}))
        }
    },
    components: {
        'customer-form': FormCustomer
    }
}
</script>
