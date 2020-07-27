<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Edit Outlet</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <outlet-form></outlet-form>
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
import FormOutlet from './Form.vue'
import {mapActions, mapState} from 'vuex'
export default {
    name: 'EditOutlet',
    created() {
        //SEBELUM COMPONENT DI-RENDER KITA MELAKUKAN REQUEST KESERVER
        //BERDASARKAN CODE YANG ADA DI URL
        this.editOutlet(this.$route.params.id)
    },
    computed: {
        ...mapState('outlet', {
            isLoading: state => state.isLoading
        })
    },
    methods: {
        ...mapActions('outlet', ['editOutlet', 'updateOutlet']),
        submit(){
            //KETIKA TOMBOL UPDATE DITEKAN MAKA AKAN MENGIRIMKAN PERMINTAAN
            //UNTUK MENGUBAH DATA BERDASARKAN CODE YANG DIKIRIMKAN
            this.updateOutlet(this.$route.params.id)
            .then(() => {
                //APABILA BERHASIL MAKA AKAN DI-DIRECT KEMBALI
                //KE HALAMAN /outlets
                this.$router.push({ name: 'outlets.data'})
            })
        }
    },
    components: {
        'outlet-form' : FormOutlet
    }
}
</script>

