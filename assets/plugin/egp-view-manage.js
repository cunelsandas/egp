let elEgpView = '#egpView', egpSearch = '#search', egpType = '#type', modal = '#modal-view-egp';
Vue.component('pagination', VuejsPaginate);
Vue.component('table-egp', {
    props: {
        data: Array,
        columns: Array,
        filter: String,
        type: String
    },
    template: '<table class="table table-sm table-bordered table-hover table-responsive-sm" style="font-size: 14px">' +
        '<thead>' +
        '<tr>' +
        '<th v-for="column in columns" :class="column.class" :width="column.width">{{column.text}}</th>' +
        '</tr>' +
        '</thead>' +
        '<tbody v-if="data.length > 0">' +
        '<tr v-for="value in data">' +
        '<td class="text-center">{{value.list}}</td>' +
        '<td>{{value.title}}</td>' +
        '<td class="text-center">' +
        '<div class="btn-group btn-group-sm">' +
        '  <button @click="egpView.changeStatus(value.field,value.status)" type="button" ' +
        '   :class="[\'btn\',value.status == 1 ? \'btn-primary\' :\'btn-danger\']"' +
        '   data-toggle="tooltip" data-placement="bottom" title="สถานะ">' +
        '     <i :class="[\'fa\',value.status == 1 ? \'fa-thumbs-o-up\' :\'fa-thumbs-o-down\']"></i>' +
        '  </button>' +
        '  <a :href="value.link" target="_blank" class="btn btn-info" ' +
        '   data-toggle="tooltip" data-placement="bottom" title="เปิดลิงค์"><i class="fa fa-eye"></i></a>' +
        '  <button @click="egpView.viewEgp(value.field)" type="button" class="btn btn-warning text-white"' +
        '   data-toggle="tooltip" data-placement="bottom" title="แก้ไข"><i class="fa fa-edit"></i></button>' +
        '  <button @click="egpView.deleteEgp(value.field)" type="button" class="btn btn-danger" ' +
        '   data-toggle="tooltip" data-placement="bottom" title="ลบ"><i class="fa fa-trash-o"></i></button>' +
        '</div>' +
        '</td>' +
        '<td>{{value.date}}</td>' +
        '</tr>' +
        '</tbody>' +
        '<tbody v-else>' +
        '<td class="text-danger text-center" colspan="4"><b>ไม่พบข้อมูล</b></td>' +
        '</tbody>' +
        '</table>',
});
Vue.component('modal-view', {
    props: {
        modal: Array,
    },
    template:
        '<form method="post" @submit.prevent="egpView.saveEgp(modal.data.egp_id,$event)">' +
        '<div class="modal fade" id="modal-view-egp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">' +
        '  <div class="modal-dialog modal-lg" role="document">' +
        '    <div class="modal-content">' +
        '      <div class="modal-header">' +
        '        <h5 class="modal-title">{{modal.title}}</h5>' +
        '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
        '          <span aria-hidden="true">&times;</span>' +
        '        </button>' +
        '      </div>' +
        '      <div class="modal-body">' +
        '        <div class="form-group">' +
        '         <label for="egp_name">ชื่อ e-GP</label>' +
        '         <input type="text" class="form-control form-control-sm" id="egp_name" name="egp_name" :value="modal.data.egp_name" autocomplete="off" required>' +
        '        </div>' +
        '        <div class="form-group">' +
        '         <label for="egp_link">link e-GP</label>' +
        '         <input type="text" class="form-control form-control-sm" id="egp_link" name="egp_link" :value="modal.data.egp_link" autocomplete="off" required>' +
        '        </div>' +
        '        <div class="form-group">' +
        '         <label for="egp_date">วันที่</label>' +
        '         <input type="text" class="form-control form-control-sm my-datepicker" id="egp_date" name="egp_date" :value="modal.data.egp_date" autocomplete="off" required>' +
        '        </div>' +
        '        <div class="form-group">' +
        '         <label for="egp_type">ประเภท e-GP</label>' +
        '         <select class="form-control form-control-sm" name="egp_type" id="egp_type" :value="modal.data.egp_type">' +
        '           <option v-for="type in modal.data.type" :value="type.parameter">{{type.name}}</option>' +
        '         </select>' +
        '        </div>' +
        '        <div class="custom-control custom-checkbox">' +
        '          <input type="checkbox" class="custom-control-input" id="egp_status" name="egp_status" :checked="modal.data.egp_status == 1?true:false" value="1">' +
        '          <label class="custom-control-label" for="egp_status">แสดงผลรายการนี้</label>' +
        '        </div>' +
        '      </div>' +
        '      <div class="modal-footer">' +
        '        <button type="submit" class="btn btn-success">บันทึก</button>' +
        '        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>' +
        '      </div>' +
        '    </div>' +
        '  </div>' +
        '</div>' +
        '</form>',
});
const egpView = new Vue({
    el: elEgpView,
    data: {
        myPage: 1,
        myPageAll: 1,
        mySearch: '',
        myType: '',
        myLimit: 10,
        datum: [],
        columns: [
            {class: 'text-center', text: 'ลำดับ', width: '70'},
            {class: '', text: 'ประกาศ', width: ''},
            {class: 'text-center', text: 'เอกสาร', width: '50'},
            {class: 'text-center', text: 'วันที่', width: '100'},
        ],
        myModal: {
            title: '',
            data: [],
            type: []
        },
    },
    created: function () {
        this.getEgp(this.myPage);
    },
    methods: {
        getEgp: function (page) {
            this.myPage = page;
            let self = this, data = new FormData(), url = $(elEgpView).data('site-url');
            data.append('page', this.myPage);
            data.append('search', this.mySearch);
            data.append('type', this.myType);
            data.append('limit', this.myLimit);
            axios.post(url, data).then(function (res) {
                self.myPageAll = parseInt(res.data.page);
                self.datum = res.data.datum;
                Vue.nextTick().then(function () {
                    $('[data-toggle="tooltip"]').tooltip();
                });
            });
        },
        changeStatus: function (field, status) {
            $('[data-toggle="tooltip"]').tooltip('hide');
            let self = this, data = new FormData(), url = $(elEgpView).data('status');
            data.append('field', field);
            data.append('status', status);
            axios.post(url, data).then((res) => {
                this.getEgp(this.myPage);
            }).catch();
        },
        viewEgp: function (field) {
            $('[data-toggle="tooltip"]').tooltip('hide');
            this.myModal.title = field === '' ? 'เพิ่มข้อมูล e-GP' : 'แก้ไขข้อมูล e-GP';
            let self = this, data = new FormData(), url = $(elEgpView).data('view');
            data.append('field', field);
            axios.post(url, data).then((res) => {
                this.myModal.data = res.data;
            }).catch();
            $(modal).modal({backdrop: 'static', keyboard: false});
        },
        saveEgp: function (field, e) {
            $('[data-toggle="tooltip"]').tooltip('hide');
            let self = this, data = new FormData(e.target), url = $(elEgpView).data('save');
            data.append('field', field);
            axios.post(url, data).then((res) => {
                swal({
                    title: "บันทึกข้อมูลสำเร็จ",
                    icon: "success",
                    closeOnClickOutside: false,
                    button: "ตกลง",
                });
                $(modal).modal('hide');
                this.getEgp(this.myPage);
            }).catch();
        },
        deleteEgp: function (field) {
            $('[data-toggle="tooltip"]').tooltip('hide');
            let self = this, data = new FormData(), url = $(elEgpView).data('delete');
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
                    axios.post(url, data).then((res) => {
                        swal("ลบข้อมูลสำเร็จ", {
                            icon: "success",
                            closeOnClickOutside: false,
                        });
                        this.getEgp(this.myPage);
                    }).catch();
                }
            });
        }
    }
});
