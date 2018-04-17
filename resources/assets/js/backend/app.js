window._ = require('lodash');
window.Vue = require('vue');
import './axios';

import router from './router';
import './plugins';
import * as filters from './filter';
import ElementUI from 'element-ui';
import '../../css/reset.css';
import 'element-ui/lib/theme-chalk/index.css';
import 'font-awesome/css/font-awesome.min.css';
import '../../sass/app.scss';
import '../../sass/backend/app.scss';


Vue.use(ElementUI);

//注册全局的过滤函数
Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key]);
});

const app = new Vue({
    beforeCreate() {},
    router,
}).$mount('#app');