
const axios = require('axios');
window.Vue = require('vue');

Vue.prototype.$http = axios;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

var app = new Vue({
    el: '#app',
    data: {
        memberColumns: [
            { prop: 'name', label: 'Character' },
            { prop: 'member.name', label: 'Member' },
            { prop: 'rarity', label: 'Rarity' },
            { prop: 'level', label: 'Level' },
            { prop: 'gear_level', label: 'Gear' },
        ]
    }
});