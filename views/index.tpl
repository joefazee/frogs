<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Wakanow</title>
    <style>
        .frog-form{
            padding: 40px;
        }
        .push-down{
            margin-top: 20px;
        }
        .info{
            background-color: #0062cc;
            color: #FFF;
        }
    </style>
</head>
<body data-gr-c-s-loaded="true">
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">Wakanow</h5>
    <a class="btn btn-outline-primary" href="#">Seed DB</a>
</div>

<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">CoolFrogs</h1>
    <p class="lead">Simple is better than complex.</p>
</div>

<div class="container" id="frogApp">
    <div class="card-deck mb-4 text-center">
        <div class="card mb-8 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal" v-if="!networkRequest">Frog`s Log: TOTAL RECORDS: %% records.length %%</h4> <h4 class="my-0 font-weight-normal" v-else>fetching records..</h4>
            </div>
            <form class="frog-form">
                <div class="row">
                    <p class="info">*This form is used to create and edit records and you must click on save to persist the record.</p>
                </div>
                <div class="row">
                    <div class="col">
                        <input type="text" v-model="form.weight" class="form-control" placeholder="Weight">
                    </div>
                    <div class="col">
                        <input type="text" v-model="form.color" class="form-control" placeholder="Color">
                    </div>
                    <div class="col">
                        <input type="text" v-model="form.batch" class="form-control" placeholder="Batch">
                    </div>
                    <div class="col">
                        <select name="" id="" class="form-control" v-model="form.group_id">
                            <option value="" disabled>Select a group</option>
                            <option :value="g.id" v-for="g in groups" v-html="g.name"></option>
                        </select>
                    </div>
                    <div class="col"><button class="btn btn-success" @click.prevent="saveRecord">Save</button></div>
                </div>
                <div class="alert alert-danger push-down" role="alert" v-if="errorMessage" v-html="errorMessage"></div>
            </form>

            <div class="card-body">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Weight</th>
                        <th scope="col">Color</th>
                        <th scope="col">Group</th>
                        <th scope="col">Batch</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="record in records">
                        <th scope="row" v-html="record.weight"></th>
                        <td v-html="record.color"></td>
                        <td v-html="record.group_name"></td>
                        <td v-html="record.batch"></td>
                        <td>
                            <button class="btn btn-sm btn-danger" @click.prevent="handleDelete(record)">X</button>
                            <button class="btn btn-sm btn-secondary" @click.prevent="handleEdit(record)">Edit</button>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-deck text-center">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">Groups</h4>
                </div>
                <div class="card-body">

                </div>
            </div>


    </div>


</div>


    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script type="application/javascript">
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
                groups: []
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
    </script>
</body>
</html>
