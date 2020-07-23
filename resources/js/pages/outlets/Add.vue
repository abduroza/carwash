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
import { mapActions, mapState } from 'vuex'
import FormOutlet from './Form.vue'
export default {
    name: 'AddOutlet',
    data() {
        return {

        }
    },
    methods: {
        ...mapActions('outlet', ['submitOutlet']),  //PANGGIL ACTIONS submitOutlet
        submit(){
            //KETIKA TOMBOL DITEKAN MAKA FUNGSI INI AKAN DIJALANKAN
            this.submitOutlet()
            .then(() => {
                //APABILA BERHASIL MAKA AKAN DI-DIRECT KE HALAMAN /outlets
                this.$router.push({ name: 'outlets.data'})
            })
        }
    },
    components: {
        'outlet-form': FormOutlet
    }
}
</script>
