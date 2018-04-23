<template>
    <div class="main">
        <div class="title-container clearfix">
            <h1 class="title">最新活动</h1>
            <el-button class="submit-activity-button" size="small" @click="handleNewActivity">新建活动</el-button>
        </div>
        <el-carousel :interval="4000" class="activity-carousel" type="card" height="200px">
            <el-carousel-item v-for="item in 6" :key="item">
                <h3>{{ item }}</h3>
            </el-carousel-item>
        </el-carousel>
        <div class="activity-container-box">
            <div v-if="!activityLoading" class="activity-container-box-inner">
                <el-row class="activity-container" :gutter="20">
                    <el-col :span="6" v-for="item,key in activityDataList" :key="key">
                        <activity-block :applied="item.applied" @activityClick="handleActivityClick" @applyClick="handleApplyClick" :activity-data="item"></activity-block>
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

                <div class="rotate-loader center-div" v-else></div>

        </div>

        <apply-dialog @applySuccess="getAllActivity" :dialog-visible.sync="applyVisible" :aid="applyAid"></apply-dialog>
    </div>
</template>

<script>
    import ActivityBlock from "./activityBlock";
    import ApplyDialog from "./applyDialog";
    export default {
        name: "index",
        components: {ApplyDialog, ActivityBlock},
        data(){
            return{
                activityDataList:[

                ],
                pageInfo:{
                    size:20,
                    sizes:[12,16,20,24],
                    total:20,
                    current:1
                },
                applyVisible:false,
                applyAid:0,
                activityLoading:false,
            }
        },
        watch:{
          '$root.isLogin':function (val) {
              this.getAllActivity();
          }
        },
        mounted(){
            this.$nextTick(()=>{
                if(!this.activityLoading){
                    this.getAllActivity();
                }
            })

        },
        methods:{
            getAllActivity(){
                this.activityLoading=true;
                axios({
                    method:'get',
                    url:this.$apiAddress.getAllActivity
                }).then((response)=>{
                    this.activityDataList=response.data.data
                }).finally(()=>{
                    this.activityLoading=false;
                })
            },
            handleActivityClick(id){
               this.$router.push('/activity/'+id);
                // console.log(id);
            },
            handleApplyClick(id){
                this.applyAid=id;
                this.applyVisible=true;
            },
            handleNewActivity(){
                localStorage.setItem('aid','');//清空以防止跳转
                localStorage.setItem('md','');
                this.$router.push('/activity/publish');

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
    .activity-container-box{
        width: 100%;
        min-height: 400px;
        border: 1px solid #eee;
        padding: 15px;
        border-radius: 10px;
        position: relative;
    }
    .activity-container-box-loading{
        position: relative;
        width: 100%;
        height: 100%;
    }
    .center-div{
        position: absolute;
        top:50%;
        left:50%;
        transform: translate(-50%,-50%);
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