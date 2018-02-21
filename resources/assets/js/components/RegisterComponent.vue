<template>
	<div class="container">
        <div class="row justify-content-center">
            <div class="col-6 mt-4">
                <div class="card">
                    <div class="card-body">
                        <div v-if="status == 'fail'" class="alert alert-danger" role="alert">{{message}}</div>
                        <h4 class="card-title">Register</h4>
                        <form method="POST" action="#" @submit.prevent="register">
                        	<div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" v-model="fields.name" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" v-model="fields.email" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" v-model="fields.password" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" v-model="fields.confirm_password" required>
                            </div>
                            <button class="btn btn-default" type="submit">Register</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Registrer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"><h4>{{message}}</h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            status: '',
            message: '',
            fields: {
            	name: '',
                email: '',
                password: '',
                confirm_password: ''
            }
        }
    },
    mounted() {
    },
    methods: {
        register: function() {
            let dataFields = {
            	name: this.fields.name,
                email: this.fields.email,
                password: this.fields.password,
                confirm_password: this.fields.confirm_password
            };
            let component = this;
            axios.post('/api/register', dataFields).then((resp) => {
                component.message = resp.data.meta.message;
                component.status = resp.data.meta.status;
                if (resp.data.meta.status === "ok") {
                    component.fields.email = '';
                    component.fields.password = '';
                    component.fields.name = '';
                    component.fields.confirm_password = '';
                    $("#successModal").modal('show');
                    $("#successModal").on('hidden.bs.modal', function(e) {
                        component.$router.push('/');
                    });
                }
            }, (err) => {
                console.log(err);
            });
        }
    }
}
</script>