window._ = require('lodash');
window.Vue = require('vue');
import './axios';

import router from './router';
import './plugins';
import '../../css/reset.css';
import * as filters from './filter';
import ElementUI from 'element-ui';
// import 'element-ui/lib/theme-default/index.css';
import 'element-ui/lib/theme-chalk/index.css';

import '../../sass/frontend/app.scss';
Vue.use(ElementUI);

//注册全局的过滤函数
Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key]);
});

const app = new Vue({
    beforeCreate() {},
    router,
}).$mount('#app');