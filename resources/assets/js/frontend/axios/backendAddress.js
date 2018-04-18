let prefix='/api';
let address={
    getLoginStatus:'/user/login/status',
    postLogin:'/user/login',
    postLogout:'/user/logout',
    postRegister:'/user/register',
    postInfoEdit:'/user/login/info/edit',
    getLoggedUserInfo:'/user/login/info',

}
for(let i in address){
    address[i]=prefix+address[i]
}
export default address;