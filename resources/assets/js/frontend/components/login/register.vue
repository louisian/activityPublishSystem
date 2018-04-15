<template>
    <div class="main">
        <h1 class="title">用户{{isRegister?'注册':'信息编辑'}}</h1>
        <div class="register-form">
            <el-form :model="registerInfo" :rules="registerRules" ref="registerForm" label-width="100px" >
                <el-form-item label="用户昵称" prop="username">
                    <el-input v-model="registerInfo.username"></el-input>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>

<script>
    export default {
        name: "register",
        data(){
            return{
                isRegister:true,
                registerRules:{
                },
                registerInfo:{
                    username:'',
                    password:'',
                    rePassword:'',
                    phone:'',
                    realname:'',
                    tag:''
                }
            }
        }
    }
</script>

<style scoped>

</style>