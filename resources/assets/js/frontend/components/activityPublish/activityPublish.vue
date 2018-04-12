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
            <div class="publish-form-container">
                <el-form ref="publishForm" :model="publishInfo" label-width="80px">
                    <el-form-item label="活动名称">
                        <el-input v-model="publishInfo.name"></el-input>
                    </el-form-item>
                    <el-form-item label="活动标签">
                        <div class="tag-container">
                            <el-select
                                    v-model="publishInfo.tag"
                                    multiple
                                    filterable
                                    remote
                                    reserve-keyword
                                    placeholder="请输入关键词"
                                    :remote-method="tagRemoteMethod"
                                    :loading="tagLoading">
                                <el-option
                                        v-for="item in tagOptions"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                            <el-button type="primary" class="add-tag-button" @click="addTag">添加标签</el-button>
                        </div>

                    </el-form-item>
                    <el-form-item label="活动时间">
                        <el-date-picker
                                v-model="publishInfo.activityTime"
                                type="datetimerange"
                                range-separator="至"
                                start-placeholder="活动开始日期"
                                end-placeholder="活动结束日期"
                                :picker-options="pickerOptions">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="报名时间">
                        <el-date-picker
                                v-model="publishInfo.applyTime"
                                type="daterange"
                                range-separator="至"
                                start-placeholder="报名开始日期"
                                end-placeholder="报名结束日期"
                                :picker-options="pickerOptions">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="活动地址">
                        <div class="address-container">
                            <el-cascader
                                    placeholder="请选择城市"
                                    :props="{value:'code',label:'name'}"
                                    :options="provCityOptions"
                                    v-model="selectCityList"
                                    @blur="setCityName"
                                    @change="handleCityChange">
                            </el-cascader>
                            <el-autocomplete
                                    clearable
                                    placeholder="请填写详细地址"
                                    class="address-input"
                                    :fetch-suggestions="fetchAddress"
                                    :disabled="publishInfo.city===''"
                                    v-model="publishInfo.address"
                                    :debounce="450"
                                    @select="handleAddressSelect"
                                    :trigger-on-focus="false">
                            </el-autocomplete>
                        </div>
                        <el-input class="detail-address-input"
                                  :disabled="publishInfo.city===''"
                                  placeholder="请输入楼层\房间号等信息"
                                  v-model="publishInfo.detailAddress">

                        </el-input>
                        <div  id="map-container">

                        </div>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmit">立即创建</el-button>
                        <el-button>取消</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>

    </div>
</template>

<script>
    import provCityJson from '../../../../json/prov-city.json';
    export default {
        name: "activityPublish",
        data(){
            return{
                activeStep:0,
                pickerOptions:{
                  disabledDate(time){
                      return time.getTime() < new Date(new Date().getTime()-24*60*60*1000);
                  }
                },
                selectCityList:[],
                publishInfo:{
                    name:'',
                    tag:[],
                    activityTime:'',
                    applyTime:'',
                    city:'',
                    cityName:'',
                    address:'',
                    detailAddress:''

                },
                mapInstance:null,
                localSearchInstance:null,
                tagLoading:false,
                recentCityName:'',
                tagOptions:[] ,
                provCityOptions:provCityJson,
            }
        },
        mounted(){
            this.mapInstance=new BMap.Map('map-container',{enableMapClick:false,minZoom:18,maxZoom:18});
            this.mapInstance.disableDragging();
        },
        methods:{
            tagRemoteMethod(query){
                if (query !== '') {
                    this.tagLoading = true;
                    setTimeout(() => {
                        this.tagLoading = false;
                        this.tagOptions = [{
                            label:'1',
                            value:'1'
                        }]
                    }, 200);
                } else {
                    this.tagOptions = [];
                }
            },
            addTag(){

            },
            setCityName(){
                if(this.selectCityList.length!==2)return;
                this.publishInfo.cityName= this.provCityOptions.find((v)=>{
                    // console.log(v.code);
                    return v.code==this.selectCityList[0];
                })['children'].find((v)=>{
                    return v.code==this.selectCityList[1];
                })['name'];
            },
            onSubmit(){




            },
            fetchAddress(query,cb){
                if(!query)return;
                let local=null;
                let res=[];
                this.mapInstance.centerAndZoom(this.publishInfo.cityName,12);
                this.mapInstance.clearOverlays();
                local=new BMap.LocalSearch(this.mapInstance,{
                    map:this.mapInstance,
                    onSearchComplete:(results)=>{
                        if(local.getStatus()===0){
                            for(let i=0;i<results.getCurrentNumPois();i++){
                                res.push({value:results.getPoi(i).address+' '+results.getPoi(i).title,point:results.getPoi(i).point});
                            }
                        }
                        // console.log(res);
                    }
                });
                local.search(query)
                cb(res);


            },
            handleAddressSelect(item){
                this.mapInstance.centerAndZoom(item.point,18);
                this.mapInstance.addOverlay(new BMap.Marker(item.point));
            },
            handleCityChange(){
                this.publishInfo.city=this.selectCityList[1];


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
    .publish-form-container{
        width: 50%;
        margin: 20px auto 0;
    }
    .publish-step{
        padding-bottom: 10px;
        border-bottom:1px solid #eee ;
    }
    .tag-container{
        display: flex;
        justify-content: center;
    }
    .address-container{
        display: flex;
        justify-content: center;
    }
    .address-input{
        margin-left: 10px;
    }
    .detail-address-input{
        margin-top: 10px;
    }
    .add-tag-button{
        margin-left: 10px;
    }
    #map-container{
        height: 150px;
        margin-top: 15px;
    }
</style>
<style>
    .el-select{
        width: 100%;
    }
    .el-range-editor.el-input__inner{
        width: 100%;
    }
    .el-autocomplete{
        width: 100%;
    }
</style>