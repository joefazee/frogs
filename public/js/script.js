/**
 * Most of the codes here can bre refactored using mix or helper methods
 * The frog project :)
 */
var frogApp = new Vue({
    delimiters: ['%%', '%%'],
    el: '#frogApp',
    data: {
        records: [],
        networkRequest: false,
        form: {
            group_id: ''
        },
        errorMessage: null,
        groups: [],
        newGroup: ''
    },
    methods: {
        fetchFrogs() {
            let vm = this;
            vm.networkRequest = true;
            axios.get('/api/frogs').then((res) => {
                vm.networkRequest = false;
                vm.records = res.data.data;
            }).catch(function(e){
                alert('ERROR: ' + e);
                vm.networkRequest = false;
            });
        },

        fetchGroups() {
            let vm = this;
            vm.networkRequest = true;
            axios.get('/api/groups').then((res) => {
                vm.networkRequest = false;
                vm.groups = res.data.data;
            }).catch(function(e){
                alert('ERROR: ' + e);
                vm.networkRequest = false;
            });
        },

        handleDelete(record) {
            if(confirm('Are you sure you want to delete entry? Click Cancel to abort.')) {
                let vm = this;
                vm.networkRequest = true;
                axios.get('/api/deleteFrog/' + record.id).then((res) => {
                    vm.fetchFrogs();
                    vm.networkRequest = false;
                }).catch(function(e){
                    alert('ERROR: ' + e);
                    vm.networkRequest = false;
                });
            }
        },
        saveRecord() {
            let vm = this;
            vm.networkRequest = true;
            vm.errorMessage = null;
            let bodyFormData = new FormData();
            for(var k in vm.form) {
                bodyFormData.append(k, vm.form[k]);
            }
            axios({
                method: 'POST',
                url: '/api/frogs',
                data: bodyFormData,
                headers: {
                    'content-type': `multipart/form-data`,
                },
            }).then((res) => {
                if(res.data.status === 'error'){
                    vm.errorMessage = res.data.message;
                }
                vm.fetchFrogs();
                vm.networkRequest = false;
                this.resetForm();
            }).catch(function(e){
                alert('ERROR: ' + e);
                vm.networkRequest = false;
            });
        },
        handleEdit(record) {
            this.form = record;
        },
        handleAddGroup(e, g) {
            let vm = this;
            vm.networkRequest = true;
            vm.errorMessage = null;
            let bodyFormData = new FormData();
            bodyFormData.append('name', g === undefined ? vm.newGroup : e.target.value);
            bodyFormData.append('id', g === undefined ? '': g.id);
            axios({
                method: 'POST',
                url: '/api/groups',
                data: bodyFormData,
                headers: {
                    'content-type': `multipart/form-data`,
                },
            }).then((res) => {
                if(res.data.status === 'error'){
                    vm.errorMessage = res.data.message;
                }
                vm.fetchGroups();
                vm.networkRequest = false;
                vm.newGroup = '';
            }).catch(function(e){
                alert('ERROR: ' + e);
                vm.networkRequest = false;
            });
        },

        resetForm() {
            this.form = {
                group_id: ''
            }
        }
    },
    mounted(){
        this.fetchFrogs();
        this.fetchGroups();
    }
});
