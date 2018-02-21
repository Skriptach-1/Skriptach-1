<template>
	<div class="flex-center position-ref full-height">
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand" href="#">Exercise App</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
				<span v-if="isLoggedIn" class="navbar-text">Hi, {{authUser.name}}! </span>
				<ul class="navbar-nav">
					<template v-if="isLoggedIn">
						<li class="nav-item">
							<a href="javascript:void(0);" v-on:click="logout" class="nav-link">Logout</a>
						</li>
					</template>
					<template v-else>
						<li class="nav-item">
							<router-link class="nav-link" to="/">Sign In</router-link>
						</li>
						<li class="nav-item">
							<router-link class="nav-link" to="register">Register</router-link>
						</li>
					</template>
				</ul>
			</div>
		</nav>
		<router-view></router-view>
	</div>
</template>

<script>
import * as Types from './store/auth/Types';

export default {
	computed: {
		authUser() {
			return this.$store.getters.authUser;
		},
		isLoggedIn() {
			return this.$store.getters.isLoggedIn;
		},
		authToken() {
			return this.$store.getters.authToken;
		}
	},
	mounted() {},
	methods: {
		logout(evt) {
			let component = this;
			component.$store.dispatch(Types.LOGOUT);
			component.$router.push('/');
		}
	}
}
</script>