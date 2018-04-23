import Vue from "vue";

window._ = require('lodash');
window.Vue = require('vue');
import './axios';

import router from './router';
import '../../css/reset.css';
import * as filters from './filter';
import ElementUI from 'element-ui';
import '../../css/reset.css';
import 'font-awesome/css/font-awesome.min.css';
import mavonEditor from 'mavon-editor'
import 'mavon-editor/dist/css/index.css'
// import 'element-ui/lib/theme-default/index.css';
import 'element-ui/lib/theme-chalk/index.css';

import '../../sass/app.scss';
import '../../sass/frontend/app.scss';
import address from  './axios/backendAddress.js';
import plugins from "./plugins/index.js";
Vue.prototype.$apiAddress=address;
// window.$isLogin=false;
Vue.use(plugins);
Vue.use(ElementUI);
Vue.use(mavonEditor);
//注册全局的过滤函数
Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key]);
});

const app = new Vue({
    beforeCreate() {},
    router,
    data(){
        return{
            isLogin:false,
        }
    }

}).$mount('#app');