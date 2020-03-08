require('./bootstrap');

import Vue from 'vue'

import VueRouter from 'vue-router'

import '@mdi/font/css/materialdesignicons.css'

Vue.use(VueRouter)

window.events = new Vue();

new Vue({
    el: '#app',
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        },
    }),
})
