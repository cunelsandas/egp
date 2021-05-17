<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 18/8/2561
 * Time: 14:53
 */
?>
<div id="user-manage" class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-info my-2">
                <div class="card-header bg-info text-white text-center">
                    <h4><i class="fa fa-users"></i> จัดการผู้ใช้งานระบบ</h4>
                </div>
                <div class="card-body">
                    <div class="input-group input-group-sm mb-2">
                        <input v-model="mySearch" type="text" class="form-control" placeholder="คำค้น..."
                               @keyup="getUser(1)">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success btn-sm float-right" @click="viewUser('')">
                                เพิ่ม User
                            </button>
                        </div>
                    </div>
                    <table-user id="table-user" :data="users" :columns="columns"
                                data-site="<?php echo $site; ?>"
                                data-view="<?php echo $site_view; ?>"
                                data-save="<?php echo $site_save; ?>"
                                data-delete="<?php echo $site_delete; ?>"></table-user>
                    <pagination-user v-model="myPage" :page-count="myPageAll" :click-handler="getUser"
                                     :container-class="'pagination pagination-sm justify-content-end'"
                                     :prev-text="'<'" :next-text="'>'" :page-class="'page-item'"
                                     :page-link-class="'page-link'" :prev-class="'page-item'"
                                     :prev-link-class="'page-link'" :next-class="'page-item'"
                                     :next-link-class="'page-link'" :first-last-button="true"
                                     :first-button-text="'<<'" :last-button-text="'>>'"></pagination-user>
                </div>
            </div>
        </div>
    </div>
    <modal-user :modal="myModal"></modal-user>
</div>
<script>
    let elUser = '#user-manage', tableUser = 'table-user';
    Vue.component('pagination-user', VuejsPaginate);
    Vue.component(tableUser, {
        props: {
            data: Array,
            columns: Array,
        },
        template:
            '<table class="table table-bordered table-striped table-hover table-sm table-responsive-sm">' +
            '<thead>' +
            '<tr>' +
            '<th v-for="value in columns" :width="value.width" :class="value.class">{{value.text}}</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody v-if="data.length > 0">' +
            '<tr v-for="value in data">' +
            '<td class="text-center">{{value.list}}</td>' +
            '<td>{{value.name}}</td>' +
            '<td>{{value.username}}</td>' +
            '<td>' +
            '<div class="btn-group btn-group-sm">' +
            '  <button @click="userManage.viewUser(value.field)" type="button" class="btn btn-warning text-white" data-toggle="tooltip" data-placement="bottom" title="แก้ไข"><i class="fa fa-edit"></i></button>' +
            '  <button @click="userManage.deleteUser(value.field)" type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="ลบ"><i class="fa fa-trash-o"></i></button>' +
            '</div>' +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '<tbody v-else>' +
            '<tr>' +
            '<td colspan="4" class="text-center text-danger">ไม่พบข้อมูล</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>',
    });
    Vue.component('modal-user', {
        props: {
            modal: Array,
        },
        template:
            '<form @submit.prevent="userManage.addUser(modal.data.field,$event)" method="post">' +
            '<div class="modal fade" id="exampleModalLong">' +
            '  <div class="modal-dialog modal-lg" role="document">' +
            '    <div class="modal-content">' +
            '      <div class="modal-header">' +
            '        <h5 class="modal-title" id="exampleModalLongTitle">{{modal.title}}</h5>' +
            '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
            '          <span aria-hidden="true">&times;</span>' +
            '        </button>' +
            '      </div>' +
            '      <div class="modal-body">' +
            '           <div class="form-row">' +
            '               <div class="col-md-6 mb-3">' +
            '                   <label for="name">ชื่อ</label>' +
            '                   <input :value="modal.data.name" type="text" class="form-control form-control-sm" id="name" name="name" autocomplete="off" required>' +
            '               </div>' +
            '               <div class="col-md-6 mb-3">' +
            '                   <label for="username">ชื่อผู้ใช้งาน</label>' +
            '                   <input :value="modal.data.username" type="text" class="form-control form-control-sm" id="username" name="username" autocomplete="off" required>' +
            '               </div>' +
            '               <div class="col-md-6 mb-3">' +
            '                   <label for="password">รหัสผ่าน</label>' +
            '                   <input :value="modal.data.password" type="type" class="form-control form-control-sm" id="password" name="password" autocomplete="off" required>' +
            '               </div>' +
            '               <div class="col-md-6 mb-3">' +
            '                   <label for="password">สิทธิ์</label>' +
            '                   <select class="form-control form-control-sm" name="permission">' +
            '                       <option value="0">ผู้ดูแลระบบ</option>' +
            '                       <option v-for="value in modal.data.domain" :value="value.domain_id" :selected="value.domain_id == modal.data.permission ? true : false">{{value.name}}</option>' +
            '                   </select>' +
            '               </div>' +
            '           </div>' +
            '      </div>' +
            '      <div class="modal-footer">' +
            '        <button type="submit" class="btn btn-primary">บันทึก</button>' +
            '        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>' +
            '      </div>' +
            '    </div>' +
            '  </div>' +
            '</div>' +
            '</form>',
    });
    const userManage = new Vue({
        el: elUser,
        data: {
            users: [],
            message: 'hoo yah',
            columns: [
                {text: 'ลำดับ', class: 'text-center', width: '70'},
                {text: 'ชื่อผู้ใช้', class: '', width: ''},
                {text: 'Username', class: '', width: ''},
                {text: 'จัดการ', class: 'text-center', width: '70'},
            ],
            myPage: 1,
            myPageAll: 1,
            myLimit: 10,
            mySearch: '',
            myModal: {title: '', data: []},
        },
        created: function () {
            this.getUser(1);
        },
        methods: {
            getUser: function (page) {
                this.myPage = page;
                let url = $('#' + tableUser).data('site'), data = new FormData(), self = this;
                data.append('search', self.mySearch);
                data.append('page', self.myPage);
                data.append('limit', self.myLimit);
                axios.post(url, data).then((res) => {
                    self.myPageAll = res.data.page;
                    self.users = res.data.all;
                    $('[data-toggle="tooltip"]').tooltip();
                }).catch();
            },
            viewUser: function (field) {
                $('#exampleModalLong').modal({backdrop: 'static', keyboard: false});
                let url = $('#' + tableUser).data('view'), data = new FormData(), self = this;
                this.myModal.title = field !== '' ? 'แก้ไขข้อมูล' : 'เพิ่มข้อมูล';
                data.append('field', field);
                axios.post(url, data).then((res) => {
                    self.myModal.data = res.data;
                    $('[data-toggle="tooltip"]').tooltip('hide');
                }).catch();
            },
            addUser: function (field, e) {
                let form = $(e.target), url = $('#' + tableUser).data('save'), data = new FormData(e.target),
                    self = this;
                data.append('field', field);
                axios.post(url, data).then((res) => {
                    $('#exampleModalLong').modal('hide');
                    self.getUser(this.myPage);
                    swal({
                        title: "บันทึกข้อมูลสำเร็จ",
                        icon: "success",
                        closeOnClickOutside: false,
                        button: "ตกลง",
                    });
                }).catch();
            },
            deleteUser: function (field) {
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
                        let url = $('#' + tableUser).data('delete'), data = new FormData(), self = this;
                        data.append('field', field);
                        axios.post(url, data).then((res) => {
                            swal("ลบข้อมูลสำเร็จ", {
                                icon: "success",
                                closeOnClickOutside: false,
                            });
                            self.getUser(this.myPage);
                            $('[data-toggle="tooltip"]').tooltip('hide');
                        }).catch();
                    }
                });
            },
        },
    });
</script>

