require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

window.events = new Vue();

new Vue({
    el: '#app',
})
