<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Edit User</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <user-form></user-form>
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
import FormUser from './Form.vue'
import { mapActions } from 'vuex'
export default {
    name: 'EditUser',
    created() {
        //sebelum komponen dirender, melakukan request ke server berdasarkan kode yg dikirmkan di route
        this.editUser(this.$route.params.id)
    },
    methods: {
        ...mapActions('user', ['editUser', 'updateUser']),
        submit() {
            this.updateUser(this.$route.params.id)
            .then(() => {
                //jika berhasil redirect ke halaman list data user
                this.$router.push({name:'users.data'})
            })
        }
    },
    components: {
        'user-form': FormUser
    }
}
</script>
