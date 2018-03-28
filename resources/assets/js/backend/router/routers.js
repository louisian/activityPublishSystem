import Main from '../components/common/main.vue';

// import Index from '../components/index/index.vue';
export default [
    {
        path:'/',
        component: Main,
        name: '首页',
        children: [
            // { path: 'index', component: Index, name: '首页' },
            // { path: 'register', component: Register, name: '注册页面' },
            // { path: 'register-active', component: RegisterActive, name: '邮箱激活页面' },
            // { path: 'login', component: Login, name: '登录页面' },
        ]
    }
]