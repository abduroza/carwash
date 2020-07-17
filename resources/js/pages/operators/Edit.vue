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
                <button class="btn btn-primary btn-sm" @click.prevent="submit">
                    <i class="fa fa-save"></i> Update
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import FormOperator from './Form.vue'
import { mapActions } from 'vuex'
export default {
    name: 'EditOutlet',
    created() {
        //sebelum komponen dirender, melakukan request ke server berdasarkan kode yg dikirmkan di route
        this.editOperator(this.$route.params.id)
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
