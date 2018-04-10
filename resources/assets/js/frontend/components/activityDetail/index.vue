<template>
    <div class="main">
        <h1 class="title">{{activityData.title}}</h1>
        <div class="main-info-container">
            <el-row>
                <el-col :span="12">
                    <img class="banner" :src="activityData.banner"/>
                </el-col>
                <el-col :span="8">
                    <div class="detail-info-container">
                        <table class="info-table">
                            <tbody>
                            <tr>
                                <td>开始时间:</td>
                                <td>{{activityData.timeStart}}</td>
                            </tr>
                            <tr>
                                <td>结束时间:</td>
                                <td>{{activityData.timeEnd}}</td>
                            </tr>
                            <tr>
                                <td>举办地点:</td>
                                <td>{{activityData.location}}</td>
                            </tr>
                            <tr>
                                <td>主办方:</td>
                                <td>{{activityData.organizer}}</td>
                            </tr>
                            <tr>
                                <td>报名截止:</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="button-container">
                            <el-button type="primary">立即报名</el-button>
                        </div>
                    </div>
                </el-col>
                <el-col :span="4">
                    <div class="map-container">

                    </div>
                </el-col>
            </el-row>



        </div>
        <div class="content-container">
            {{activityData.content}}
        </div>
    </div>
</template>

<script>
    export default {
        name: "activityDetail",
        data(){
            return{
                activityData:{
                    title:'',
                    id:'',
                    banner:'',
                    timeStart:'',
                    timeEnd:'',
                    location:'',
                    organizer:'',
                    content:'',
                }
            }
        },
        mounted(){
            console.log(this.$router,'para',this.$router.currentRoute.params.id)
        },

        methods:{

        }

    }
</script>

<style scoped>
    .title{
        padding: 20px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    .main-info-container{
        margin-top: 30px;
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
</style>