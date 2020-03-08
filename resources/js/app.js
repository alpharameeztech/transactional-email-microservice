require('./bootstrap');

import Vue from 'vue'

window.events = new Vue();

new Vue({
    el: '#app',
})
