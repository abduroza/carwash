<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Add New Product</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <product-form></product-form>
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
import { mapActions, mapState } from 'vuex'
import FormProduct from './Form.vue'
export default {
    name: 'AddProduct',
    computed: {
        ...mapState('product', {
            isLoading: state => state.isLoading
        })
    },
    methods: {
        ...mapActions('product', ['submitProduct']), //panggil submitProduct di product.js
        submit(){
            this.submitProduct()
            .then(() => {
                //jika berhasil redirect ke list product
                this.$router.push({ name: 'products.data'})
            })
        }
    },
    components: {
        'product-form': FormProduct
    }
}
</script>
