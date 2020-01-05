/**
 * App entry point
 */
import Vue from 'vue';
import Vuex from 'vuex'
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'
import App from '../layout/App.vue' ;
import AppHeader from '../layout/AppHeader'
import AppFooter from '../layout/AppFooter'
import VueRouter from 'vue-router'
import VueAxios from 'vue-plugin-axios'
import routes from '../utils/routes'
import configured_axios from '../utils/axios'

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import '../css/app.css';

// creation of the store
Vue.use(Vuex);
let store = new Vuex.Store({

    state: {
        user :null ,// {"email" : "test@test.com"} ,

    },
    getters: {
        //used to check if the user user is logged in or not
        is_logged_in : state => state.user !== null ,
        //getter for the email user
        user_email : (state,getters) => ! (getters.is_logged_in) ?  null : state.user.email
    },
    mutations: {
        //
        logout (state) {
            state.user = null
        },
        login (state ,user) {
            this.user = user
            /**
             * user { email , token }
             */
        }
    }
});


/*
    Installing Vue Plugins
    BootstrapVue : Bootstrap for vuejs
    Vuex : state store
    VueRouter : Used for routing
    VueAxios : A plugin that wraps axios and makes it available inside child components
 */
Vue.use(BootstrapVue);
Vue.use(VueRouter);
// the second parameter is an object containing the axios object and requests configuration
Vue.use(VueAxios,configured_axios(store));


// loading the routes and using them
// passing the store to the routes to check user permissions before redirecting him
const router = new VueRouter({
    routes : routes(store)
});

// Vue init
new Vue({
    components: {App, AppHeader , AppFooter} ,
    store ,
    router ,
    el : "#app",
});
