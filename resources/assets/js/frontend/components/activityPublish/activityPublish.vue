<template>
    <div class="main">
        <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{ path: '/index' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>创建活动</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="publish-container">
            <el-steps class="publish-step" align-center :active="activeStep" finish-status="success">
                <el-step title="活动信息"></el-step>
                <el-step title="主办方信息"></el-step>
                <el-step title="完成提交"></el-step>
            </el-steps>
        </div>

    </div>
</template>

<script>
    export default {
        name: "activityPublish",
        data(){
            return{
                activeStep:0,
            }
        }
    }
</script>

<style scoped>
    .publish-container{
        margin-top: 15px;
        border: 1px solid #eee;
        padding: 10px;
    }
    .publish-step{
        padding-bottom: 10px;
        border-bottom:1px solid #eee ;
    }
</style>