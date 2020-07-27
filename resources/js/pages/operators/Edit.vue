<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Edit Operator</h5>
            </div>
        </div>
        <div>
            <!-- form yg diload dari form.vue -->
            <operator-form></operator-form>
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
import FormOperator from './Form.vue'
import { mapActions, mapState } from 'vuex'
export default {
    name: 'EditOutlet',
    created() {
        //sebelum komponen dirender, melakukan request ke server berdasarkan kode yg dikirmkan di route
        this.editOperator(this.$route.params.id)
    },
    computed: {
        ...mapState('operator', {
            isLoading: state => state.isLoading
        })
    },
    methods: {
        ...mapActions('operator', ['editOperator', 'updateOperator']),
        submit() {
            this.updateOperator(this.$route.params.id)
            .then(() => {
                //jika berhasil redirect ke halaman list data operator
                this.$router.push({name:'operators.data'})
            })
        }
    },
    components: {
        'operator-form': FormOperator
    }
}
</script>
