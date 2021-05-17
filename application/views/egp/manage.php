<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 22/8/2561
 * Time: 10:37
 */
?>

<div class="container">
    <div class="row my-2">
        <div class="col-lg-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white text-center">
                    <h4><i class="fa fa-file-text" aria-hidden="true"></i> ระบบจัดซื้อจัดจ้างภาครัฐ e-GP</h4>
                </div>
                <div class="card-body" id="egpView" data-site-url="<?php echo $site_url; ?>"
                     data-status="<?php echo $site_status; ?>" data-view="<?php echo $site_view; ?>"
                     data-delete="<?php echo $site_delete; ?>" data-save="<?php echo $site_save; ?>">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group input-group-sm mb-2">
                                <input v-model="mySearch" type="text" id="search" @keyup="getEgp(1)"
                                       class="form-control col-lg-9" placeholder="คำค้น...">
                                <select v-model="myType" name="type" id="type" @change="getEgp(1)"
                                        class="form-control col-lg-3">
                                    <?php foreach ($egp_type as $k => $v): ?>
                                        <option value="<?php echo $v['egp_type_parameter']; ?>"><?php echo $v['egp_type_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="input-group-append">
                                    <button @click="viewEgp('')" type="button" class="btn btn-success">เพิ่มข้อมูล
                                        e-GP
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <table-egp :data="datum" :columns="columns" :filter="'a'" :type="'a'"></table-egp>
                        </div>
                        <div class="col-10">
                            <pagination v-model="myPage" :page-count="myPageAll" :click-handler="getEgp"
                                        :container-class="'pagination pagination-sm justify-content-start'"
                                        :prev-text="'<<'" :next-text="'>>'" :page-class="'page-item'"
                                        :page-link-class="'page-link'" :prev-class="'page-item'"
                                        :prev-link-class="'page-link'" :next-class="'page-item'"
                                        :next-link-class="'page-link'"></pagination>
                        </div>
                        <div class="col-2">
                            <select v-model="myLimit" name="limit" id="limit" class="form-control form-control-sm"
                                    @change="getEgp(1)">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <modal-view :modal="myModal"></modal-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section id="custom-js">
    <!--    <script src="--><?php //echo base_url('assets/plugin/egp-view-manage.js'); ?><!--"></script>-->
    <script src="<?php echo base_url('assets/plugin/egp-view-manage.min.js'); ?>"></script>
</section>
