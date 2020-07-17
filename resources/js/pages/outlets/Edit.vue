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
                <button class="btn btn-primary btn-sm" @click.prevent="submit">
                    <i class="fa fa-save"></i> Update
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import FormOutlet from './Form.vue'
import {mapActions} from 'vuex'
export default {
    name: 'EditOutlet',
    created() {
        //SEBELUM COMPONENT DI-RENDER KITA MELAKUKAN REQUEST KESERVER
        //BERDASARKAN CODE YANG ADA DI URL
        this.editOutlet(this.$route.params.id)
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

