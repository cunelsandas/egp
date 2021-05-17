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
<body>
<div class="container">
    <div class="row my-2">
        <div class="col-lg-12">
            <div class="card border-primary">
                <div class="card-header">
                    <h2>โปรแกรมดึงข้อมูล EGP</h2>
                </div>
                <div id="myEgp" class="card-body" data-url-get-egp="<?php echo site_url('home/get_egp'); ?>"
                     data-url-get-webs="<?php echo site_url('home/get_webs'); ?>">
                    <div class="progress" style="height: 2rem;">
                        <div :class="[progress,progressStatus]" :style="{width: mPercent}">
                            {{mPercent}}
                        </div>
                    </div>
                    <div id="asd" class="mt-2" style="overflow-x: auto;max-height: 250px;">
                        <ul class="list-group">
                            <li v-for="mySuccess in success" class="list-group-item">ดึงข้อมูล Database ->
                                {{mySuccess}}
                            </li>
                        </ul>
                    </div>
                    <div id="asds" class="mt-2" style="overflow-x: auto;max-height: 250px;">
                        <ul class="list-group">
                            <li v-for="myError in error" class="list-group-item">ดึงข้อมูล Database ->
                                {{myError}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section id="custom-js">
    <script>
        let elEgp = '#myEgp', url = $(elEgp).data('url-get-egp'), url_webs = $(elEgp).data('url-get-webs');
        const epgManage = new Vue({
            el: elEgp,
            data: {
                message: 'ทดสอบ',
                mPercent: '0%',
                success: [],
                error: [],
                progress: 'progress-bar progress-bar-striped progress-bar-animated',
                progressStatus: '',
                myData: [],
            },
            created: function () {
                this.getDomain();
            },
            methods: {
                getDomain: function () {
                    let $data = new FormData(), self = this;
                    $data.append('action', 'get_webs');
                    axios.post(url_webs, $data).then(function (result) {
                        self.myData = result.data;
                        self.getEgp();
                    });
                },
                getEgp: async function () {
                    let self = this;
                    let ii = self.myData.webs.length;
                    // ii = 10;
                    let kk = self.myData.egp_type.length;
                    for (let i = 1; i <= ii; i++) {
                        for (let k = 1; k <= kk; k++) {
                            try {
                                let $data = new FormData();
                                $data.append('i', i);
                                $data.append('ii', ii);
                                $data.append('k', k);
                                $data.append('kk', kk);
                                $data.append('field', self.myData.webs[i - 1]['field']);
                                $data.append('type', self.myData.egp_type[k - 1]['egp_type']);
                                let result = await axios.post(url, $data);
                                self.mPercent = result.data.percent;
                                self.success.push('สำเร็จ ' + result.data.success + ' ประเภท ' + self.myData.egp_type[k - 1]['egp_type_name']);
                                //console.log(result.data.test);
                                self.progressStatus = '';
                                if (i === ii && k === kk) {
                                    self.progressStatus = 'bg-success';
                                }
                            } catch (error) {
                                self.progressStatus = 'bg-danger';
                                self.mPercent = parseFloat((i * 100 / ii)).toFixed(2) + '%';
                                self.error.push('ผิดพลาด ' + self.myData.webs[i - 1]['database'] + ' ประเภท ' + self.myData.egp_type[k - 1]['egp_type_name']);
                            }
                            // $('#asd').animate({scrollTop: document.getElementById('asd').scrollHeight}, "fast");
                        }
                    }
                }
            },
        });
    </script>
</section>
</body>
</html>
