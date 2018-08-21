
window._ = require('lodash');
window.Popper = require('popper.js').default;
 
try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

 
window.Vue = require('vue');
window.axios = require('axios');  
import VueRouter from 'vue-router';
import Form from './core/Form';
import BootstrapVue from 'bootstrap-vue'
import { Modal, FormInput } from 'bootstrap-vue/es/components';


 
Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(Modal, FormInput);
window.Form = Form;




window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
 

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}
 
