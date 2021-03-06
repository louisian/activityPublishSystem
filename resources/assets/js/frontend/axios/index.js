window.axios = require('axios');
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';
import { Message } from 'element-ui';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials=true;
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


//axios拦截器
axios.interceptors.request.use(function(config) {
    NProgress.start();
    return config;
}, function(error) {
    NProgress.done();
    return Promise.reject(error);
});

axios.interceptors.response.use(function(response) {
    NProgress.done();
    return response;
}, function(error) {
    NProgress.done();
    Message.error({
        message:error.response.data.message,
        center:true,
        duration:1500,
        showClose:true
    });
    // console.log(error.response);
    return Promise.reject(error);
});