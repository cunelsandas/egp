let elEgpView = '#egpView', egpSearch = '#search', egpType = '#type';
Vue.component('pagination', VuejsPaginate);
Vue.component('table-egp', {
    props: {
        data: Array,
        columns: Array,
        filter: String,
        type: String
    },
    template: '<table class="table table-sm table-bordered table-hover" style="font-size: 14px">' +
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
        '<a :href="value.link" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>' +
        '</td>' +
        '<td>{{value.date}}</td>' +
        '</tr>' +
        '</tbody>' +
        '<tbody v-else>' +
        '<td class="text-danger text-center" colspan="4"><b>ไม่พบข้อมูล</b></td>' +
        '</tbody>' +
        '</table>',
});
const egpView = new Vue({
    el: elEgpView,
    data: {
        myPage: 1,
        myPageAll: 1,
        mySearch: '',
        myType: 'W0',
        myLimit: 10,
        datum: [],
        columns: [
            {class: 'text-center', text: 'ลำดับ', width: '70'},
            {class: '', text: 'ประกาศ', width: ''},
            {class: 'text-center', text: 'เอกสาร', width: '50'},
            {class: 'text-center', text: 'วันที่', width: '100'},
        ],
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
            });
        }
    }
});
