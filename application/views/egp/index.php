<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 10/8/2561
 * Time: 14:35
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugin/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/my-css.css'); ?>">
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/popper.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vue.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/axios.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/paginate.js'); ?>"></script>
    <title>Document</title>
</head>
<body style="background-color: <?php echo $bg_color; ?>">
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12  my-2">
            <div class="card border-primary">
                <div class="card-header">
                    <span class="mt-2" style="font-size:18px;text-shadow:5px 5px 5px #5b5b5b;">ระบบ RSS เชื่อมโยงระบบจัดซื้อจัดจ้าง (EGP)</span>
                </div>
                <div class="card-body" id="egpView" data-site-url="<?php echo $site_url; ?>">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group mb-2">
                                <input v-model="mySearch" type="text" id="search" @keyup="getEgp(1)"
                                       class="form-control form-control-sm col-9" placeholder="คำค้น...">
                                <select v-model="myType" name="type" id="type" @change="getEgp(1)"
                                        class="form-control form-control-sm col-3">
										
                                    <?php foreach ($egp_type as $k => $v): ?>
										
										
                                        <option value="<?php echo $v['egp_type_parameter']; ?>"><?php echo $v['egp_type_name']; ?></option>
									
                                    <?php endforeach; ?>
                                </select>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section id="custom-js">
    <script src="<?php echo base_url('assets/plugin/egp-view-index.min.js'); ?>"></script>
</section>
</body>
</html>
