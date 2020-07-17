<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Add New Outlet</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <outlet-form></outlet-form>
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
import FormOperator from './Form.vue'
import { mapActions } from 'vuex'
export default {
    name: 'AddOperator',
    methods: {
        ...mapActions('operator', ['submitOperator']), //PANGGIL ACTIONS submitOperator
        //KETIKA TOMBOL submit DITEKAN MAKA FUNGSI INI AKAN DIJALANKAN
        submit(){
            this.submitOperator()
            .then(() => {
                //APABILA BERHASIL MAKA AKAN DI-REDIRECT KE HALAMAN /user
                this.$router.push({ name: 'operators.data'})
            })
        }
    },
    components: {
        'outlet-form': FormOperator
    }
}
</script>
