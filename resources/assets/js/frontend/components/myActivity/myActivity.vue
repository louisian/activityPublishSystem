<template>
    <div class="main">
        <div class="create-activity container">
            <h1 class="title">我发起的活动</h1>
            <el-row class="activity-container" :gutter="20">
                <el-col :span="6" v-for="item,key in createActivityList" :key="key">
                    <activity-block :edit-mode="true" @clickEdit="handleEdit" @activityClick="handleActivityClick" :activity-data="item"></activity-block>
                </el-col>

            </el-row>

        </div>
        <div class="apply-activity container">
            <h1 class="title">我参与的活动</h1>
            <el-row class="activity-container" :gutter="20">
                <el-col :span="6" v-for="item,key in applyActivityList" :key="key">
                    <activity-block :applied="true" @activityClick="handleActivityClick" :activity-data="item"></activity-block>
                </el-col>

            </el-row>
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
</style>