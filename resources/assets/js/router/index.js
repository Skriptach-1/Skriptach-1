import Vue from "vue";
import Router from "vue-router";
import * as Types from '../store/auth/Types';
import LoginComponent from "../components/LoginComponent";
import RegisterComponent from "../components/RegisterComponent";
import HomeComponent from "../components/HomeComponent";
import {store} from '../store';

Vue.use(Router);

const router = new Router({
	routes: [
		{
			path: '/',
			name: 'home',
			component: HomeComponent
		},
		{
			path: '/login',
			name: 'login',
			component: LoginComponent
		},
		{
			path: '/register',
			name: 'register',
			component: RegisterComponent
		}
	]
});

router.beforeEach((to, from, next) => {
	if (store.getters.isLoggedIn) {
		store.dispatch(Types.FETCH_USER).then(() => next());
	} else {
		next();
	}
});

export default router;