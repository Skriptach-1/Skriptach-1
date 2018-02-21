import * as Types from "./Types";
import Cookies from "js-cookie";
import axios from "axios";

const state = {
	user: {
		name: '',
		email: ''
	},
	token: Cookies.get('auth_token')
};

const mutations = {
    [Types.SAVE_USER](state, resp) {
    	Cookies.set('auth_token', resp.data.data.token);
    	state.token = resp.data.data.token;
        state.user.name = resp.data.user.name;
    },
    [Types.LOGOUT](state, resp) {
    	state.token = '';
        state.user.name = '';
        Cookies.remove('auth_token')
    },
    [Types.FETCH_USER_SUCCESS](state, resp) {
    	console.log(state, resp);
    },
    [Types.FETCH_USER_FAILURE](state, resp) {
    	console.log(state, resp);
    },
    [Types.FETCH_USER](state, resp) {
    	console.log(state, resp);
    }
};
const actions = {
    [Types.SAVE_USER]({commit}, resp) {
    	commit(Types.SAVE_USER, resp);
    },
    [Types.LOGOUT]({commit}) {
    	commit(Types.LOGOUT);
    },
    [Types.FETCH_USER_SUCCESS]({commit}, user) {
    	commit(Types.FETCH_USER_SUCCESS, user);
    },
    [Types.FETCH_USER_FAILURE]({commit}) {
    	commit(Types.FETCH_USER_FAILURE);
    },
    [Types.FETCH_USER]({commit}) {
    	axios.get('/api/auth/me').then((resp) => {
    		if (resp.data.status === 'ok') {
    			commit(Types.FETCH_USER_SUCCESS, resp.data.user);
    		} else {
    			commit(Types.LOGOUT);
    		}
    	});
    }
};

const getters = {
    authUser: state => state.user,
    authToken: state => state.token,
    isLoggedIn: state => state.token !== undefined
};

export default {
    state,
    mutations,
    actions,
    getters
}