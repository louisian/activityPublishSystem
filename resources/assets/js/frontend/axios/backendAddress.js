let prefix='/api';
let address={
    getLoginStatus:'/user/login/status',

    postLogin:'/user/login',

    postLogout:'/user/logout',

    postRegister:'/user/register',

    postInfoEdit:'/user/login/info/edit',

    getLoggedUserInfo:'/user/login/info',

    postAddTag:'/tag/add',

    searchTag:'/tag/search',

    getTagByTidList:'/tag/get/list',

    postBasicActivityInfo:'/activity/info/basic',

    postDetailActivityInfo:'/activity/info/detail',

    getAllActivity:'/activity/info/all',

    getActivityDetail:'/activity/info/detail'
}
for(let i in address){
    address[i]=prefix+address[i]
}
export default address;