import * as Types from "./Types";
import Cookies from "js-cookie";
import axios from "axios";

const state = {
	user: {
		name: '',
		email: ''
	},
    question: {
        title: '',
        answers: []
    },
	token: Cookies.get('auth_token')
};

const mutations = {
    [Types.SAVE_USER](state, resp) {
    	Cookies.set('auth_token', resp.data.data.token);
    	state.token = resp.data.data.token;
        state.user = resp.data.data.user;
    },
    [Types.LOGOUT](state, resp) {
    	state.token = null;
        state.user.name = '';
        state.question = [];
        Cookies.remove('auth_token');
    },
    [Types.FETCH_USER_SUCCESS](state, resp) {
    	state.user = resp.user;
    },
    [Types.FETCH_USER_FAILURE](state) {
    	state.user.name = '';
        state.token = null;
        state.question = [];
    },
    [Types.FETCH_USER](state, resp) {
    	state.user = resp.user;
    },
    [Types.FETCH_QUESTION](state, resp, questions) {
        state.question = resp.question;
        state.question = questions;
    }
};
const actions = {
    [Types.SAVE_USER]({commit}, resp) {
    	commit(Types.SAVE_USER, resp);
    },
    [Types.LOGOUT]({commit}) {
    	commit(Types.LOGOUT);
    },
    [Types.FETCH_USER_SUCCESS]({commit}, data) {
    	commit(Types.FETCH_USER_SUCCESS, data);
    },
    [Types.FETCH_USER_FAILURE]({commit}) {
    	commit(Types.FETCH_USER_FAILURE);
    },
    [Types.FETCH_USER]({commit}) {
    	axios.get('/api/user').then((resp) => {
    		if (resp.data.meta.status === 'ok') {
    			commit(Types.FETCH_USER_SUCCESS, resp.data.data);
    		} else {
    			commit(Types.LOGOUT);
    		}
    	});
    },
    [Types.FETCH_QUESTION]({commit}, data, questions) {
        commit(Types.FETCH_QUESTION, data, questions);
    }
};

const getters = {
    authUser: state => state.user,
    authToken: state => state.token,
    isLoggedIn: state => state.token != undefined
};

export default {
    state,
    mutations,
    actions,
    getters
}