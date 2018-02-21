require('./bootstrap');

import Vue from "vue";
import VueResource from "vue-resource";
import Root from "./Root";
import {store} from "./store";
import router from "./router";
import './utils/interceptors';

Vue.config.productionTip = false;
Vue.use(VueResource);

new Vue({
    el: '#app',
    store,
    router,
    template: '<Root/>',
    components: {Root}
});
