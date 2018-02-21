<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-6 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Login</h4>
                        <form method="POST" action="#" @submit.prevent="login">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" v-model="fields.email" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" v-model="fields.password" required>
                            </div>
                            <button class="btn btn-default" type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {store} from '../store';
import * as Types from "../store/auth/Types";

export default {
    data() {
        return {
            message: '',
            fields: {
                email: '',
                password: ''
            }
        }
    },
    mounted() {
    },
    methods: {
        login: function() {
            let dataFields = {
                email: this.fields.email,
                password: this.fields.password
            };
            let component = this;
            axios.post('/api/login', dataFields).then((resp) => {
                if (resp.data.meta.status === 'ok') {
                    component.$store.dispatch(Types.SAVE_USER, resp);
                    component.$router.push('home');
                } else {
                    component.message = resp.data.message;
                }
            }, (err) => {
                console.log(err.message);
            });
        }
    }
}
</script>
