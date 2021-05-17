<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 17/8/2561
 * Time: 16:53
 */
?>
<div class="container">
    <div class="row">
        <div class="col-lg-4 offset-lg-8 col-md-6 offset-md-1">
            <div id="login-form" class="card border-0 shadow-lg mt-5">
                <div class="card-body">
                    <form action="<?php echo site_url('user/login'); ?>" method="post">
                        <h4>เข้าสู่ระบบเพื่อจัดการข้อมูล</h4>
                        <hr>
                        <div class="form-group">
                            <input type="text" class="form-control" id="username" name="username"
                                   placeholder="ชื่อผู้ใช้..." autocomplete="off">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="รหัสผ่าน..." autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
