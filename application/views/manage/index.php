<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16/8/2561
 * Time: 10:18
 */
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div id="user-manage" class="card border-info my-2" data-site-url="<?php echo $site_domain; ?>">
                <div class="card-header bg-info text-white text-center">
                    <h4><i class="fa fa-server" aria-hidden="true"></i> จัดการรหัส e-GP</h4>
                </div>
                <div class="card-body">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control form-control-sm col-lg-11 col-sm-10" placeholder="คำค้น..."
                               @keyup="getMyDomain(1)" v-model="mySearch">
                        <select name="limit" id="limit" class="form-control form-control-sm col-lg-1 col-sm-2"
                                @change="getMyDomain(1)" v-model="myLimit">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success btn-sm mb-2 float-right"
                                    @click="viewMyDomain('')">
                                เพิ่มข้อมูลใหม่
                            </button>
                        </div>
                    </div>
                    <table-domain id="table-egp" :data="allDomain" :columns="column"
                                  data-site-status="<?php echo site_url('manage/change_status') ?>"
                                  data-site-view="<?php echo site_url('manage/view') ?>"
                                  data-site-save="<?php echo site_url('manage/save') ?>"
                                  data-site-delete="<?php echo site_url('manage/delete') ?>"
                                  data-site-table="<?php echo site_url('manage/table') ?>"></table-domain>
                    <modal-domain :data="viewData" :modal="modalView"></modal-domain>
                    <pagination-domain v-model="myPage" :page-count="myPageAll" :click-handler="getMyDomain"
                                       :container-class="'pagination pagination-sm justify-content-start'"
                                       :prev-text="'<'" :next-text="'>'" :page-class="'page-item'"
                                       :page-link-class="'page-link'" :prev-class="'page-item'"
                                       :prev-link-class="'page-link'" :next-class="'page-item'"
                                       :next-link-class="'page-link'" :first-last-button="true"
                                       :first-button-text="'<<'" :last-button-text="'>>'"></pagination-domain>
                </div>
            </div>
        </div>
    </div>
</div>
<section id="custom-js">
    <script>
        let elUserManage = '#user-manage', tableDomain = 'table-domain', tableEgp = '#table-egp',
            modalDomain = 'modal-domain';
        Vue.component('pagination-domain', VuejsPaginate);
        Vue.component(tableDomain, {
            props: {
                data: Array,
                columns: Array,
            },
            template:
                '<table id="table-manage" class="table table-responsive-sm table-striped table-hover table-bordered table-sm" style="font-size: 14px;">' +
                '<thead>' +
                '<tr>' +
                '<th v-for="column in columns" :width="column.width" :class="column.class">{{column.text}}</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody v-if="data.length > 0">' +
                '<tr v-for="datum,key in data">' +
                '<td class="text-center">{{datum.list}}</td>' +
                '<td>{{datum.egp_name}}</td>' +
                '<td>{{datum.egp_id}}</td>' +
                '<td>{{datum.database_name}}</td>' +
                '<td class="text-center">' +
                '<button v-if="datum.database_status == 1" type="button" class="btn btn-sm btn-primary" @click="userManage.changeStatus(datum.database_id,datum.database_status)">' +
                '<i class="fa fa-thumbs-o-up"></i>' +
                '</button>' +
                '<button v-else type="button" class="btn btn-sm btn-danger" @click="userManage.changeStatus(datum.database_id,datum.database_status)">' +
                '<i class="fa fa-thumbs-o-down"></i>' +
                '</button>' +
                '</td>' +
                '<td class="text-center">' +
                '<div class="btn-group" role="group" aria-label="Basic example">' +
                '  <button type="button" class="btn btn-sm btn-success" @click="userManage.viewMyDomain(datum.database_id)" data-toggle="tooltip" data-placement="bottom" title="แก้ไข"><i class="fa fa-edit"></i></button>' +
                '  <button type="button" class="btn btn-sm btn-danger" @click="userManage.deleteMyDomain(datum.database_id)" data-toggle="tooltip" data-placement="bottom" title="ลบ"><i class="fa fa-trash-o"></i></button>' +
                '  <button type="button" class="btn btn-sm btn-primary" @click="userManage.createMyTable(datum.database_id)" data-toggle="tooltip" data-placement="bottom" title="สร้างตารางเก็บข้อมูล"><i class="fa fa-table"></i></button>' +
                '</div>' +
                '</td>' +
                '</tr>' +
                '</tbody>' +
                '<tbody v-else>' +
                '<tr class="text-center text-danger"><td colspan="7"><b>ไม่พบข้อมูล</b></td></tr>' +
                '</tbody>' +
                '</table>',
            methods: {}
        });
        Vue.component(modalDomain, {
            props: {
                data: Array,
                modal: Array
            },
            template: '<div class="modal fade" id="modal-view">' +
                '  <div class="modal-dialog modal-lg">' +
                '   <form @submit.prevent="userManage.saveMyDomain(data.database_id,$event)" method="post">' +
                '    <div class="modal-content">' +
                '       <div class="modal-header">' +
                '        <h5 class="modal-title">{{modal.title}}</h5>' +
                '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                '          <span aria-hidden="true">&times;</span>' +
                '        </button>' +
                '      </div>' +
                '      <div class="modal-body">' +
                '             <div class="form-group">' +
                '               <label for="egp_name">ชื่อองค์กร</label>' +
                '               <input :value="data.egp_name" type="text" class="form-control form-control-sm" id="egp_name" name="egp_name" required autocomplete="off">' +
                '             </div>' +
                '             <div class="form-group">' +
                '               <label for="egp_id">รหัส E-GP</label>' +
                '               <input :value="data.egp_id" type="number" class="form-control form-control-sm" id="egp_id" name="egp_id" required autocomplete="off">' +
                '             </div>' +
                '             <div class="form-group">' +
                '               <label for="egp_date_start">วันที่ลงทะเบียน</label>' +
                '               <input :value="data.egp_date_start" type="text" class="form-control form-control-sm my-datepicker" id="egp_date_start" name="egp_date_start" required autocomplete="off">' +
                '             </div>' +
                '             <div class="form-group">' +
                '               <label for="egp_date_exp">กำหนดวันหมดอายุ</label>' +
                '               <input :value="data.egp_date_exp" type="text" class="form-control form-control-sm my-datepicker" id="egp_date_exp" name="egp_date_exp" required autocomplete="off">' +
                '             </div>' +
                '             <div class="form-group">' +
                '               <label for="database_host">HostName</label>' +
                '               <input :value="data.database_host" type="text" class="form-control form-control-sm" id="database_host" name="database_host" required autocomplete="off">' +
                '             </div>' +
                '             <div class="form-group">' +
                '               <label for="database_user">User Database</label>' +
                '               <input :value="data.database_user" type="text" class="form-control form-control-sm" id="database_user" name="database_user" required autocomplete="off">' +
                '             </div>' +
                '             <div class="form-group">' +
                '               <label for="database_password">Password Database</label>' +
                '               <input :value="data.database_password" type="text" class="form-control form-control-sm" id="database_password" name="database_password" required autocomplete="off">' +
                '             </div>' +
                '             <div class="form-group">' +
                '               <label for="database_name">Database Name</label>' +
                '               <input :value="data.database_name" type="text" class="form-control form-control-sm" id="database_name" name="database_name" required autocomplete="off">' +
                '             </div>' +
                '      </div>' +
                '      <div class="modal-footer">' +
                '        <button type="submit" class="btn btn-success">บันทึก</button>' +
                '        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>' +
                '      </div>' +
                '    </div>' +
                '   </form>' +
                '  </div>' +
                '</div>',
        });
        const userManage = new Vue({
            el: elUserManage,
            data: {
                myPage: 1,
                myPageAll: 10,
                myLimit: 10,
                mySearch: '',
                column: [
                    {text: 'ลำดับ', width: '50', class: 'text-center'},
                    {text: 'ชื่อองค์กร', width: '', class: ''},
                    {text: 'รหัส E-GP', width: '', class: ''},
                    {text: 'DatabaseName', width: '', class: ''},
                    {text: 'สถานะ', width: '50', class: ''},
                    {text: 'จัดการ', width: '100', class: 'text-center'},
                ],
                allDomain: [],
                viewData: [],
                modalView: {title: 'เพิ่มข้อมูลใหม่'}
            },
            created: function () {
                this.getMyDomain(this.myPage);
            },
            methods: {
                getMyDomain: function (page) {
                    this.myPage = page;
                    let self = this, data = new FormData(), url = $(elUserManage).data('site-url');
                    data.append('page', this.myPage);
                    data.append('limit', this.myLimit);
                    data.append('search', this.mySearch);
                    axios.post(url, data).then(function (res) {
                        self.allDomain = res.data.datum;
                        self.myPageAll = res.data.pages;
                        Vue.nextTick().then(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        });
                    }).catch();
                },
                changeStatus: function (field, status) {
                    let self = this, data = new FormData(), url = $(tableEgp).data('site-status');
                    data.append('field', field);
                    data.append('status', status);
                    axios.post(url, data).then(function (res) {
                        if (res.data) {
                            self.getMyDomain(self.myPage);
                        } else {
                            alert('พบข้อผิดพลาด!!');
                        }
                    }).catch();
                },
                viewMyDomain: function (field) {
                    let self = this, data = new FormData(), url = $(tableEgp).data('site-view');
                    data.append('field', field);
                    $('#modal-view').modal({backdrop: 'static', keyboard: false});
                    this.modalView.title = 'แก้ไขข้อมูล';
                    axios.post(url, data).then(function (res) {
                        self.viewData = res.data;
                        $('[data-toggle="tooltip"]').tooltip('hide');
                    }).catch();
                },
                saveMyDomain: function (field, e) {
                    let self = this, form = $(e.target), data = new FormData(e.target),
                        url = $(tableEgp).data('site-save');
                    data.append('field', field);
                    axios.post(url, data).then(function (res) {
                        swal({
                            title: "บันทึกข้อมูลสำเร็จ",
                            icon: "success",
                            closeOnClickOutside: false,
                            button: "ตกลง",
                        });
                        $('#modal-view').modal('hide');
                        self.getMyDomain(self.myPage);
                    }).catch();
                },
                deleteMyDomain: function (field) {
                    let self = this, data = new FormData(), url = $(tableEgp).data('site-delete');
                    data.append('field', field);
                    swal({
                        title: "ต้องการลบข้อมูลนี้หรือไม่?",
                        icon: "warning",
                        dangerMode: true,
                        buttons: {
                            defeat: {text: 'ลบ', value: true},
                            cancel: 'ยกเลิก',
                        },
                        closeOnClickOutside: false,
                    }).then((willDelete) => {
                        if (willDelete) {
                            axios.post(url, data).then(function (response) {
                                $('[data-toggle="tooltip"]').tooltip('hide');
                                self.getMyDomain(self.myPage);
                            }).catch();
                            swal("ลบข้อมูลสำเร็จ", {
                                icon: "success",
                                closeOnClickOutside: false,
                            });
                        }
                    });
                },
                createMyTable: function (field) {
                    let self = this, data = new FormData(),
                        url = $(tableEgp).data('site-table');
                    data.append('field', field);
                    axios.post(url, data).then(function (res) {
                        if (res.data) {
                            swal({
                                title: "เพิ่มตารางเรียบร้อย",
                                icon: "success",
                                closeOnClickOutside: false,
                                button: "ตกลง",
                            });
                        } else {
                            swal({
                                title: "พบข้อผิดพลาด",
                                icon: "error",
                                closeOnClickOutside: false,
                                button: "ตกลง",
                            });
                        }
                        self.getMyDomain(self.myPage);
                    }).catch(function (e) {
                        swal({
                            title: "พบข้อผิดพลาด",
                            icon: "error",
                            closeOnClickOutside: false,
                            button: "ตกลง",
                        });
                    });
                }
            },
        });
    </script>
</section>
