import router from "./components/router";

require('./bootstrap');

import Vue from 'vue'

import VueRouter from 'vue-router'

import Vuetify from 'vuetify'

import '@mdi/font/css/materialdesignicons.css'

Vue.use(VueRouter)

Vue.use(Vuetify);

window.events = new Vue();

new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        },
    }),
})
