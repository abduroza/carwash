<template>
    <div class="col-12">
        <div class="d-flex mb-3 mt-1">
            <div class="p-2 flex-grow-1">
                <h5>Detail Expense</h5>
            </div>
        </div>
        <div>
            <template>
                <!-- detail expense -->
                <dt>Permintaan Karyawan</dt>
                <dd>- {{ expense.title }}</dd>

                <hr>
                <dt>Biaya Yang Diperlukan</dt>
                <dd>- Rp {{ expense.price }}</dd>
                <hr>

                <dt>Catatan</dt>
                <dd>- {{ expense.note }}</dd>
                <hr>

                <dt>User/Kurir</dt>
                <dd>- {{ expense.user.name }}</dd>
                <hr>

                <dt>Status</dt>
                <dd>
                    <span class="badge badge-success" v-if="expense.status == 1">Diterima</span>
                    <span class="badge badge-warning" v-else-if="expense.status == 0">Diproses</span>
                    <span class="badge badge-secondary" v-else>Ditolak</span>
                </dd>
                <hr>
                <!-- jika status = 2 yg berarti ditolak, maka info alasan dimunculkan -->
                <div v-if="expense.status == 2">
                    <dt>Alasan Penolakan</dt>
                    <dd>- {{ expense.reason }}</dd>
                    <hr>
                </div>
                <!-- end informasi detail expense -->
                
                <!-- jika status = 0, maka tombol terima dan tolak ditampilkan -->
                <div class="pull-right" v-if="expense.status == 0 || (expense.status == 0 && !formReason)">
                    <!-- jika yg ditekan tombol tolak, maka formReason akan ditampilkan -->
                    <button class="btn btn-danger btn-sm" @click="formReason = true">Tolak</button>
                    <button class="btn btn-primary btn-sm" @click="accept">Terima</button>
                </div>
            </template>
            <div v-if="formReason">
                <div class="form-group">
                    <label for="">Alasan Penolakan</label>
                    <input type="text" v-model="inputReason" class="form-control">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-sm pull-right" @click="reject">Respon Penolakan</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
export default {
    name: 'ViewExpense',
    created() {
        this.editExpense(this.$route.params.id).then()
    },
    data() {
        return {
            formReason: false,
            inputReason: ''
        }
    },
    computed: {
        ...mapState('expense', {
            expense: state => state.expense
        })
    },
    methods: {
        ...mapActions('expense', ['editExpense', 'acceptExpense', 'rejectExpense']),
        accept(){
            this.$swal({
                title: 'Yakin Diterima?',
                text: "Tindakan ini tidak akan dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if(result.value){
                    this.acceptExpense({ id: this.$route.params.id })
                    .then(() => {
                        this.$router.push({ name: 'expenses.data' })
                    })
                }
            })
        },
        reject(){
            this.$swal({
                title: 'Yakin Mau Ditolak?',
                text: "Tindakan ini tidak dapat dikemablikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if(result.value){
                    this.rejectExpense({ id: this.$route.params.id, reason: this.inputReason })
                    .then(() => {
                        this.formReason = false
                        this.$router.push({ name: 'expenses.data'})
                    })
                }
            })
        }

    }
}
</script>