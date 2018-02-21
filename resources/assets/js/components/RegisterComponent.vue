<template>
	<div class="container">
        <div class="row justify-content-center">
            <div class="col-6 mt-4">
                <div class="card">
                    <div class="card-body">
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
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
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
                password: this.fields.password
            };
            let component = this;
            axios.post('/api/register', dataFields).then((resp) => {
                component.message = resp.data.meta.message;
                if (resp.data.meta.status === "ok") {
                    component.user.email = '';
                    component.user.password = '';
                    component.user.name = '';
                    component.user.confirm_password = '';
                    $("#reg-success").modal('show');
                }
            }, (err) => {
                console.log(err);
            });
        }
    }
}
</script>