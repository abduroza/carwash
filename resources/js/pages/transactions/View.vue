<template>
    <div class="col-md-12">
        <div>
            <div class="col-md-6" v-if="order.isPaid == 0">
                <div class="col-md-6" v-if="order.isPaid == 0">
                    <h4>Payment</h4>
                    <hr>
                    <!-- form transaction -->
                    <div class="form-group">
                        <label for="">Tagihan</label>
                        <input type="text" :value="order.amount" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" v-model="via_deposite"> Bayar Via Deposit?
                    </div>
                    <div class="form-group">
                        <label for="">Jumlah Bayar</label>
                        <input type="number" class="form-control" v-model="amount">
                    </div>
                    <p v-if="isCustomerChange">Kembalian: Rp {{ customerChangeAmount }}</p>
                    <div class="form-group" v-if="isCustomerChange">
                        <input type="checkbox" v-model="customer_change" id="customer_change">
                        <label for="customer_change"> Kembalian Jadi Deposit?</label>
                    </div>
                    <p class="text-danger" v-if="payment_message">{{ payment_message }}</p>
                    <button class="btn btn-primary btn-sm" :disabled="loading" @click="makePayment">Bayar</button>
                </div>
            </div>
            <div class="col-md-6">
                
            </div>
            <div class="col-md-6">
                
            </div>
            <div class="col-md-12">
                
            </div>
        </div>
    </div>
</template>