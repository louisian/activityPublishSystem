<template>
    <div class="main">
        <div class="title-container clearfix">
            <h1 class="title">最新活动</h1>
            <el-button class="submit-activity-button" size="small" @click="handleNewActivity">新建活动</el-button>
        </div>
        <el-carousel v-if="activityDataList.length>4" :interval="4000" class="activity-carousel" type="card"  height="297px">
            <el-carousel-item   v-for="index in 4" :key="index">
                <div @click="$router.push('/activity/'+activityDataList[index].aid)" class="carousel-container">
                    <img class="carousel-background" :src="activityDataList[index].poster">
                    <div class="des-container">
                        <h1 class="title">{{activityDataList[index].name}}</h1>
                        <p class="city-name">{{activityDataList[index].cityName}}</p>
                    </div>
                </div>

            </el-carousel-item>
        </el-carousel>
        <div class="activity-container-box">
            <div v-if="!activityLoading&&pageInfo.total>0" class="activity-container-box-inner">
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
                        :current-page.sync="pageInfo.current"
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange">
                </el-pagination>
            </div>
                <div class="no-activity" v-if="!activityLoading&&pageInfo.total<=0">
                    <p class="no-activity-text">
                        <i class="icon iconfont">&#xe618;</i>
                        <span>暂无活动</span>
                    </p>
                </div>
                <div class="rotate-loader center-div" v-if="activityLoading"></div>

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
              this.$nextTick(()=>{
                  if(!this.activityLoading){
                      this.getAllActivity();
                  }
              })
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
            handleCurrentChange(val) {
                this.pageInfo.current=val;
              this.getAllActivity();
            },
            handleSizeChange(val){
                this.pageInfo.size=val;
                this.pageInfo.current=1;
                this.getAllActivity();
            },
            getAllActivity(){
                this.activityLoading=true;
                axios({
                    method:'get',
                    url:this.$apiAddress.getAllActivity,
                    params:{
                        page:+this.pageInfo.current,
                        pageSize:+this.pageInfo.size,
                    }
                }).then((response)=>{
                    let res=response.data.data;
                    this.activityDataList=res.list;
                    this.pageInfo.total=res.total;
                    this.pageInfo.current=+res.page;


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

    .el-carousel__item:hover .des-container{
        opacity: 0.8;

    }

</style>
<style scoped>
    .carousel-container{
        /*width: 528px;*/
        /*height: 297px;*/

    }
    .main{
        margin-bottom: 40px;
    }
    .title-container{
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .activity-carousel{
        margin: 30px auto;
    }
    .activity-container-box{
        width: 100%;
        min-height: 300px;
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
    .carousel-background{
        border-radius: 10px;
        width: 100%;
        height: 297px;
        /*height: 100%;*/
    }
    .des-container{
        bottom: 0;
        border-radius: 10px;
        width: 100%;
        height: 30%;
        left:0;
        background-color: transparent;
        opacity: 0.3;
        position: absolute;
        transition: .25s ease opacity;

    }
    .des-container .title{
        display: block;
        width: 38%;
        border-top:2px solid black;
        text-align: right;
        margin-right: 20px;
        margin-left: 60%;
    }
    .des-container .city-name{
        display: block;
        width: 100%;
        text-align: right;
        padding-right: 20px;
        margin-top: 15px;
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