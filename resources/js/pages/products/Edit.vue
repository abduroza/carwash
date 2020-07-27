<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Edit Product</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <product-form></product-form>
            <!-- tombol update untuk mengupdate data terbaru -->
            <div class="form-group">
                <button class="btn btn-primary btn-sm" @click.prevent="submit" :disabled="isLoading">
                    <span v-if="!isLoading" class="fa fa-save"> Update</span>
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
    name: 'EditProduct',
    created() {
        //editProduct() membutuhkan parameter id
        this.editProduct(this.$route.params.id)
    },
    computed: {
        ...mapState('product', {
            isLoading: state => state.isLoading
        })
    },
    methods: {
        ...mapActions('product', ['editProduct', 'updateProduct']),
        submit() {
            //updateProduct() membutuhkan parameter id
            this.updateProduct(this.$route.params.id)
            .then((res) => {
                this.$router.push({ name: 'products.data' })
            })
        }
    },
    components: {
        'product-form': FormProduct
    }   
}
</script>
