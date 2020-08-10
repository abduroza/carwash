<template>
    <div class="container">
        <flash-message class="" :position="'right top'"></flash-message>
        <!-- header -->
        <div class="row" style="padding: 8px 0 8px 0">
            <div class="col-6">
                <h4>
                    Manage Outlets
                </h4>
            </div>
            <div class="col-6">
                <breadcrumb></breadcrumb>
            </div>
        </div>
        <!-- end header -->
        <!-- content -->
        <div class="col-md-12 bg-white mb-3">
            <div class="row">
                <router-view></router-view>
            </div>
        </div>
        <!-- end content -->
    </div>
</template>
<script>
import Breadcrumb from '../../components/Breadcrumb.vue'
export default {
    name: 'IndexOutlet',
    components: {
        'breadcrumb' : Breadcrumb,
        // 'FlashMessage': VueFlashMessage
    }
}
</script>
