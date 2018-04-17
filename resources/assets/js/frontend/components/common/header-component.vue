<template>
    <div>
        <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
            <li class="nav-brand"><i class="iconfont aws-icon">&#xe605;</i> 活动</li>
            <el-submenu v-if="!isLogin" index="my">
                <template slot="title">我的</template>
                <el-menu-item index="login" @click="loginVisible=true">登录</el-menu-item>
                <el-menu-item index="register" @click="isRegister=true;registerVisible=true">注册</el-menu-item>
            </el-submenu>
            <el-submenu v-else index="my">
                <template slot="title">我的</template>
                <el-menu-item @click="isRegister=false;registerVisible=true" index="info">我的信息</el-menu-item>
                <el-menu-item @click="$router.push('/my-activity')" index="activity">我的活动</el-menu-item>
                <el-menu-item index="logout" @click="logout">退出登录</el-menu-item>
            </el-submenu>
            <el-menu-item @click="$router.push('/index')" index="index">首页</el-menu-item>

        </el-menu>
        <login-dialog @loginSuccess="handleLoginSuccess" :dialog-visible.sync="loginVisible"></login-dialog>
        <register-dialog :is-register="isRegister" :dialog-visible.sync="registerVisible"></register-dialog>
    </div>


</template>

<script>
    import RegisterDialog from "../login/registerDialog";
    import LoginDialog from "../login/loginDialog";
    export default {
        name: "header-component",
        props:['isLogin'],
        components: {LoginDialog, RegisterDialog},
        data(){
            return{
                activeIndex:'index',
                registerVisible:false,
                loginVisible:false,
                isRegister:true,

            }
        },
        methods:{
            handleLoginSuccess(){
                this.$emit('loginSuccess');
            },
            handleSelect(){

            },
            logout(){
                axios({
                    method:'post',
                    url:this.$apiAddress.postLogout,
                }).then((response)=>{
                    this.$message({
                        message: '登出成功',
                        type: 'success'
                    });
                    this.$emit('logout')
                })


            }
        }
    }
</script>

<style scoped>
    .nav-brand{
        float: left;
        line-height: 60px;
        height: 60px;
        margin-left: 10px;
        margin-right: 20px;
    }

</style>