<template>
    <div class="main">
        <div class="title-container clearfix">
            <h1 class="title">最新活动</h1>
            <el-button class="submit-activity-button" size="small" @click="$router.push('/activity/publish')">新建活动</el-button>
        </div>
        <el-carousel :interval="4000" class="activity-carousel" type="card" height="200px">
            <el-carousel-item v-for="item in 6" :key="item">
                <h3>{{ item }}</h3>
            </el-carousel-item>
        </el-carousel>
        <el-row class="activity-container" :gutter="20">
            <el-col :span="6" v-for="item,key in activityDataList" :key="key">
                <activity-block @activityClick="handleActivityClick" :activity-data="item"></activity-block>
            </el-col>

        </el-row>
        <el-pagination
                background
                layout="prev, pager, next , sizes , total"
                :total="pageInfo.total"
                :page-sizes="pageInfo.sizes"
                :page-size="pageInfo.size"
                :current-page.sync="pageInfo.current">
        </el-pagination>
    </div>
</template>

<script>
    import ActivityBlock from "./activityBlock";
    export default {
        name: "index",
        components: {ActivityBlock},
        data(){
            return{
                activityDataList:[
                    {
                        id:1,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:2,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:3,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:4,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:5,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:6,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    }
                ],
                pageInfo:{
                    size:20,
                    sizes:[12,16,20,24],
                    total:20,
                    current:1
                }
            }
        },
        methods:{
            handleActivityClick(id){
               this.$router.push('/activity/'+id);
                // console.log(id);
            }
        }
    }
</script>
<style>
    .el-carousel__item h3 {
        color: #475669;
        font-size: 14px;
        opacity: 0.75;
        line-height: 200px;
        margin: 0;
    }

    .el-carousel__item:nth-child(2n) {
        background-color: #99a9bf;
    }

    .el-carousel__item:nth-child(2n+1) {
        background-color: #d3dce6;
    }
</style>
<style scoped>

    .main{
        margin-bottom: 40px;
    }
    .title-container{
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .activity-carousel{
        margin: 30px 0;
    }

    .title{
        font-size: 24px;
        float: left;

    }
    .activity-container{
        margin-top: 10px;
    }
    .submit-activity-button{
        float: right;
        margin-right: 10px;
    }
</style>