
const axios = require('axios');
const $ = require('jquery');
window.Vue = require('vue');

Vue.prototype.$http = axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('list', require('./components/List.vue'));
Vue.component('stars', require('./components/Stars.vue'));
Vue.component('characters', require('./components/Characters.vue'));
Vue.component('members', require('./components/Members.vue'));

var app = new Vue({
    el: '#app',
    data: {
        states: ['characters', 'members'],
        selected: 'characters',
    },
    methods: {
        show: function(state) {
            if (this.selected === state) { return; }
            this.selected = state;
        }
    }
});

$(document).ready(function() {
    $('[e-go]').on('click.go', function() {
        window.location.assign($(this).attr('e-go'));
    });
})