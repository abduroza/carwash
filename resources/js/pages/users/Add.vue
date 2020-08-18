<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Add New User</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <user-form></user-form>
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
import FormUser from './Form.vue'
import { mapActions } from 'vuex'
export default {
    name: 'AddUser',
    methods: {
        ...mapActions('user', ['submitUser']), //PANGGIL ACTIONS submitOperator
        //KETIKA TOMBOL submit DITEKAN MAKA FUNGSI INI AKAN DIJALANKAN
        submit(){
            this.submitUser()
            .then(() => {
                //APABILA BERHASIL MAKA AKAN DI-REDIRECT KE HALAMAN /user
                this.$router.push({ name: 'users.data'})
            })
        }
    },
    components: {
        'user-form': FormUser
    }
}
</script>
