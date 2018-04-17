let prefix='/api';
let address={
    getLoginStatus:'/user/login/status',
    postLogin:'/user/login',
    postLogout:'/user/logout',
}
for(let i in address){
    address[i]=prefix+address[i]
}
export default address;