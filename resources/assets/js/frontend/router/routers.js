import Main from '../components/common/main.vue';

import Index from '../components/index/index.vue';
import ActivityDetail from '../components/activityDetail/index.vue';
import ActivityPublish from '../components/activityPublish/activityPublish.vue';
import MyActivity from '../components/myActivity/myActivity.vue';
// import Register from '../components/login/registerDialog.vue';
export default [
    {
        path:'/',
        component: Main,
        name: 'skeleton',
        children: [
            { path: 'index', component: Index, name: '首页' },
            { path: 'activity/publish',component:ActivityPublish, name:'活动发布'},
            { path: 'activity/publish/:id',component:ActivityPublish, name:'活动编辑'},
            { path: 'activity/:id', component: ActivityDetail, name: '活动详情' },
            { path: 'my-activity',component:MyActivity,name:'我的活动'},
            // { path:'register',component:Register,name:'用户注册'},
            // { path: 'register-active', component: RegisterActive, name: '邮箱激活页面' },
            // { path: 'login', component: Login, name: '登录页面' },
        ]
    }
]