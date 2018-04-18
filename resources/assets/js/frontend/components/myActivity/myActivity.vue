<template>
    <div class="main">
        <div class="create-activity container">
            <h1 class="title">我发起的活动</h1>
            <el-row v-if="!noCreateActivity" class="activity-container" :gutter="20">
                <el-col :span="6" v-for="item,key in createActivityList" :key="key">
                    <activity-block :edit-mode="true" @clickEdit="handleEdit" @activityClick="handleActivityClick" :activity-data="item"></activity-block>
                </el-col>

            </el-row>
            <div v-else class="no-activity">
                <p class="no-activity-text">
                    <i class="icon iconfont">&#xe618;</i>
                    <span>暂无活动</span>
                </p>

            </div>

        </div>
        <div class="apply-activity container">
            <h1 class="title">我参与的活动</h1>
            <el-row v-if="!noApplyActivity" class="activity-container" :gutter="20">
                <el-col :span="6" v-for="item,key in applyActivityList" :key="key">
                    <activity-block :applied="true" @activityClick="handleActivityClick" :activity-data="item"></activity-block>
                </el-col>

            </el-row>
            <div v-else class="no-activity">
                <p class="no-activity-text">
                    <i class="icon iconfont">&#xe618;</i>
                    <span>暂无活动</span>
                </p>
            </div>
        </div>


    </div>
</template>

<script>
    import ActivityBlock from "../index/activityBlock";
    export default {
        name: "myActivity",
        components: {ActivityBlock},
        data(){
            return{
                applyActivityList:[],
                createActivityList:[],
            }
        },
        computed:{
            noCreateActivity:function () {
                return this.createActivityList.length===0;
            },
            noApplyActivity:function () {
                return this.applyActivityList.length===0;
            }
        },
        method:{
            handleActivityClick(id){
                this.$router.push('/activity/'+id);
            },
            handleEdit(id){

            }
        }
    }
</script>

<style scoped>
    .container{
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .no-activity{
        position: relative;
        height: 300px;
        width: 100%;
    }
    .no-activity-text{
        position: absolute;
        top:50%;
        left: 50%;
        display: flex;
        transform: translate(-50%,-50%);
        font-size: 20px;

    }
    .no-activity-text span{
        display: inline-block;
        height: 40px;
        line-height: 40px;
        margin-left: 10px;
    }
    .no-activity-text .icon{
        font-size: 40px;
    }
</style>