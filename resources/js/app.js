import router from "./components/router";

require('./bootstrap');

import Vue from 'vue'

import VueRouter from 'vue-router'

import Vuetify from 'vuetify'

import '@mdi/font/css/materialdesignicons.css'

import ReadMore from 'vue-read-more';

Vue.use(VueRouter)

Vue.use(Vuetify);

Vue.use(ReadMore);

window.events = new Vue();

window.flash = function(message, type = 'success') {
    window.events.$emit('flash', {message, type} );
}
Vue.component(
    'app-header',
    require('./components/Header.vue').default);

Vue.component(
    'loader-component',
    require('./components/Loader.vue').default);

Vue.component(
    'flash-component',
    require('./components/Flash.vue').default);

new Vue({
    el: '#app',
    router,
    vuetify: new Vuetify({
        icons: {
            iconfont: 'mdi', // default - only for display purposes
        },
    }),
})
