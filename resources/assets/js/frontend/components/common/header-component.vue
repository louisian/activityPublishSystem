<template>
    <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" @select="handleSelect">
        <li class="nav-brand">test</li>
        <el-submenu index="my">
            <template slot="title">我的</template>
            <el-menu-item index="login">登录</el-menu-item>
            <el-menu-item index="register">注册</el-menu-item>
        </el-submenu>
        <el-menu-item index="index">首页</el-menu-item>

    </el-menu>
</template>

<script>
    export default {
        name: "header-component",
        data(){
            return{
                activeIndex:'index'
            }
        },
        methods:{
            handleSelect(){

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