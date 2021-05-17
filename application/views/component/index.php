<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18/8/2561
 * Time: 15:10
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
    <link rel="stylesheet" href="<?php echo base_url('assets/plugin/datapicker/datepicker.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugin/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/my-css.css'); ?>">
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/popper.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/datapicker/bootstrap-datepicker.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/datapicker/bootstrap-datepicker-thai.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugin/datapicker/bootstrap-datepicker.th.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/vue.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/axios.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/paginate.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/sweetalert.min.js'); ?>"></script>
    <link rel="icon" href="<?php echo base_url('assets/img/receipt.png'); ?>" type="image/png" sizes="16x16">
    <title>จัดการข้อมูล e-GP</title>
</head>
<body>
<div id="wallpaper-login-form"></div>
<header>
    <?php
    if ($this->session->user_id) {
        $this->load->view('component/header');
    }
    ?>
</header>
<main>
    <?php
    if ($this->session->user_id) {
        $this->load->view($view, $data);
    } else {
        $this->load->view('component/login');
    }
    ?>
</main>
<footer>
    <?php $this->load->view('component/footer') ?>
</footer>
</body>
</html>
