<template>
    <div class="main">
        <h1 class="title clearfix">
            {{activityData.name}}
            <el-badge v-if="isCreator" class="show-apply-info item" :value="applyCount">
                <el-button
                           @click="handleShowApplyInfo"
                           type="primary"
                           :plain="true">
                    查看报名信息

                </el-button>
            </el-badge>
        </h1>
        <div class="main-info-container">
            <el-row>
                <el-col class="banner-container" :span="12">
                    <img class="banner" @error="activityData.poster=require('../../../../img/no-pic.png')" :src="activityData.poster"/>
                </el-col>
                <el-col :span="8">
                    <div class="detail-info-container">
                        <table cellspacing="10" class="info-table">
                            <tbody>
                            <tr class="info-table-item">
                                <td class="table-title">开始时间:</td>
                                <td>{{activityData.activityStartTime}}</td>
                            </tr>
                            <tr class="info-table-item">
                                <td class="table-title">结束时间:</td>
                                <td>{{activityData.activityStopTime}}</td>
                            </tr>
                            <tr class="info-table-item">
                                <td class="table-title">举办地点:</td>
                                <td>{{activityData.cityName}}</td>
                            </tr>
                            <tr class="info-table-item">
                                <td class="table-title">报名截止:</td>
                                <td class="date-item">
                                    {{countDownObj.day}}
                                    <p class="date-divider">天</p>
                                    {{countDownObj.hour}}
                                    <p class="date-divider">小时</p>
                                    {{countDownObj.minute}}
                                    <p class="date-divider">分</p>
                                    {{countDownObj.second}}
                                    <p class="date-divider">秒</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="button-container">
                            <el-button :disabled="true" v-if="activityData.applied" type="success">已报名</el-button>
                            <el-button v-else @click="applyNow" :disabled="isOutdate" type="primary">{{isOutdate?'报名截止':'立即报名'}}</el-button>
                        </div>
                    </div>
                </el-col>
                <el-col :span="4">
                    <div class="map-container">

                    </div>
                </el-col>
            </el-row>



        </div>
        <div class="content-container" v-html="activityData.description">

        </div>
        <el-dialog title="报名信息" :visible.sync="applyInfoVisible">
            <el-table :data="applyData">
                <el-table-column v-for="item,key in applyTableHeader" :key="key" :property="item.prop" :label="item.label"></el-table-column>
            </el-table>
        </el-dialog>
        <apply-dialog :dialog-visible.sync="applyVisible" @applySuccess="activityData.applied=true" :aid="activityData.aid"></apply-dialog>
    </div>
</template>

<script>
    import ApplyDialog from "../index/applyDialog";
    export default {
        name: "activityDetail",
        components: {ApplyDialog},
        data(){
            return{
                activityData:{
                    name:'test',
                    aid:'',
                    poster:'',
                    activityStartTime:'',
                    activityStopTime:'',
                    cityName:'',
                    description:'',
                    applied:false,
                },
                isOutdate:false,
                applyData:[

                ],
                countDownObj:{
                    day: 0,
                    hour: 0,
                    minute: 0,
                    second: 0
                },
                applyTableHeader:[
                    {
                        prop:'realname',
                        label:'真实姓名'
                    },
                    {
                        prop:'commit',
                        label:'备注'
                    }
                ],
                applyInfoVisible:false,
                applyVisible:false,
                applyCount:100,
                isCreator:true,
            }
        },
        mounted(){
            // this.countDown(new Date('2019/1/1'))

            let aid=this.$router.currentRoute.params.id;
            axios(this.$apiAddress.getActivityDetail,{params:{aid:aid}}).then((response)=>{
                let data=response.data.data.activityInfo;
                for(let i in this.activityData){
                    this.activityData[i]=data[i];
                }
                if(new Date(data.applyStopTime).getTime()<new Date().getTime()){
                    this.isOutdate=true;
                }else{
                    this.countDown(new Date(data.applyStopTime));
                }

                this.isCreator=!!response.data.data.admin;
            })
            // console.log(this.$router,'para',this.$router.currentRoute.params.id)
        },

        methods:{
            countDown(target) {
                var source = new Date();
                var diff = target.getTime() - source.getTime();
                var fullSecond = Math.ceil(diff / 1000);
                var day = Math.floor(fullSecond / (60 * 60 * 24));
                fullSecond = fullSecond - day * (60 * 60 * 24)
                var hour = Math.floor(fullSecond / (60 * 60));
                fullSecond = fullSecond - hour * (60 * 60)
                var minute = Math.floor(fullSecond / 60);
                fullSecond = fullSecond - minute * 60;
                var second = fullSecond;
                setInterval(() => {
                    second--
                    second < 0 && (minute--, second = 59);
                    minute < 0 && (hour--, minute = 59);
                    hour < 0 && (day--, hour = 23);
                    this.countDownObj= {
                        day: day,
                        hour: hour,
                        minute: minute,
                        second: second
                    }
                    // console.log(day,hour,minute,second)
                }, 1000)
            },
            applyNow(){
                axios({
                    methods:'get',
                    url:this.$apiAddress.getLoginStatus
                }).then((response)=>{
                    this.applyVisible=true;
                })
            },
            handleShowApplyInfo(){
                this.applyInfoVisible=true;
            }
        }

    }
</script>

<style scoped>
    .banner-container{
        position: relative;
    }
    .banner{
        /*position: absolute;*/
        width: 480px;
        margin-left:60px;
        height: 270px;
        /*top: 50%;*/
        /*left:50%;*/
        /*transform: translate(-50% -50%);*/
    }
    .table-title{
        padding-right: 10px;
        text-align: right;
    }
    .date-item{
        font-size: 18px;

    }
    .date-divider{
        display: inline-block;
        font-size: 15px;
        margin: 0 2px;

    }
    .info-table{
        border-collapse: separate;
        border-spacing: 0px 10px;

    }
    .show-apply-info{
        float: right;
    }
    .title{
        padding: 20px 0;
        line-height: 40px;
        border-bottom: 1px solid #e0e0e0;
    }
    .main-info-container{
        border: 1px solid #eee;
        padding: 5px;
        border-radius: 10px;
        margin: 30px 0;
    }
    .detail-info-container{
        padding: 25px 15px 0;
    }
    .info-table{
        font-size: 14px;

    }
    .info-table tr{
        padding-bottom: 5px;
    }
    .button-container{
        margin: 15px 0;
    }
    .map-container{
        height: 100%;
    }
    .content-container{
        width: 1200px;
        padding: 15px;
        margin: 0 auto 30px;
        border: 1px solid #eee;
        border-radius: 10px;

        min-height: 100px;

    }
</style>