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
                <button class="btn btn-primary btn-sm" @click.prevent="submit">
                    <i class="fa fa-save"></i> Add New
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions } from 'vuex'
import FormProduct from './Form.vue'
export default {
    name: 'AddProduct',
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
