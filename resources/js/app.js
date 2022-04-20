require('./bootstrap');

window.Vue = require('vue').default;

import App from './layouts/App.vue';
import Axios from "axios";

Vue.prototype.$http = Axios;

const app = new Vue({
    el: '#app',
    render: h => h(App)
});
