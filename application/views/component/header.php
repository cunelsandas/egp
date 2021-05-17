<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18/8/2561
 * Time: 15:28
 */
$egp_id = $this->session->egp_id;
?>
<nav id="my-nav" class="navbar navbar-expand-lg fixed-top navbar-dark">
    <i class="navbar-brand">e-GP Admin</i>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <?php if ($this->session->permission == 0): ?>
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo site_url('manage'); ?>">จัดการรหัส e-GP</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo site_url('user'); ?>">จัดการผู้ใช้การในระบบ</a>
                </li>
            <?php else: ?>
                <li class="nav-item active">
                    <a class="nav-link" href="<?php echo site_url("egp/$egp_id"); ?>">จัดการข้อมูล e-GP</a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="form-inline">
            <a href="<?php echo site_url('user/logout'); ?>" class="btn btn-danger" data-toggle="tooltip"
               data-placement="bottom" title="ออกจากระบบ"><i class="fa fa-sign-out"></i></a>
        </div>
    </div>
</nav>