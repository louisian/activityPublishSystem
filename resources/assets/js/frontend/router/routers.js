import Main from '../components/common/main.vue';

import Index from '../components/index/index.vue';
import ActivityDetail from '../components/activityDetail/index.vue';
export default [
    {
        path:'/',
        component: Main,
        name: '首页',
        children: [
            { path: 'index', component: Index, name: '首页' },
            { path: 'activity/:id', component: ActivityDetail, name: '活动详情' },
            // { path: 'register-active', component: RegisterActive, name: '邮箱激活页面' },
            // { path: 'login', component: Login, name: '登录页面' },
        ]
    }
]