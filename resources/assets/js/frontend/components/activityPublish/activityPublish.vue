<template>
    <div class="main">
        <el-breadcrumb separator="/">
            <el-breadcrumb-item :to="{ path: '/index' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>{{isCreate?'创建':'编辑'}}活动</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="publish-container">
            <el-steps class="publish-step" align-center :active="activeStep" finish-status="success">
                <el-step title="活动基本信息"></el-step>
                <el-step title="活动详细信息"></el-step>
                <el-step title="完成提交"></el-step>
            </el-steps>
            <div v-if="activeStep===0" class="publish-form-container">
                <el-form status-icon ref="publishForm" :rules="publishRule" :model="publishInfo" label-width="120px">
                    <el-form-item label="活动名称" prop="name">
                        <el-input v-model="publishInfo.name"></el-input>
                    </el-form-item>
                    <el-form-item label="活动标签" prop="tag">
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
                                        :key="item.tid"
                                        :label="item.name"
                                        :value="item.tid">
                                </el-option>
                            </el-select>
                            <el-button type="primary" class="add-tag-button" @click="addTag"><i class="fa fa-plus fa-icon" aria-hidden="true"></i>添加标签</el-button>
                        </div>

                    </el-form-item>
                    <el-form-item label="活动时间" prop="activityTime">
                        <el-date-picker
                                v-model="publishInfo.activityTime"
                                type="datetimerange"
                                range-separator="至"
                                start-placeholder="活动开始日期"
                                end-placeholder="活动结束日期"
                                :picker-options="pickerOptions">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="报名时间" prop="applyTime">
                        <el-date-picker
                                v-model="publishInfo.applyTime"
                                type="daterange"
                                range-separator="至"
                                start-placeholder="报名开始日期"
                                end-placeholder="报名结束日期"
                                :picker-options="pickerOptions">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="活动地址" prop="address">
                        <div class="address-container">
                            <el-cascader
                                    class="address-select"
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

                        <div  id="map-container">

                        </div>
                    </el-form-item>
                    <el-form-item label="活动详细地址" prop="detailAddress">
                        <el-input class="detail-address-input"
                                  :disabled="publishInfo.city===''"
                                  placeholder="请输入楼层\房间号等信息"
                                  v-model="publishInfo.detailAddress">
                        </el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" class="next-step-button" :plain="true" @click="onSubmitBasic">提交基本信息并进行下一步</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div v-if="activeStep===1" class="publish-detail-info-container">
                <h1 class="input-title">报名所需信息</h1>
                <div class="apply-info-container">
                    <el-checkbox-group class="checkbox-container" v-model="publishInfo.applyInfo">
                        <el-checkbox-button v-for="item,index in infoList" :label="item.value" :key="index">{{item.label}}</el-checkbox-button>
                    </el-checkbox-group>
                    <el-input placeholder="请输入备注标题" v-if="~publishInfo.applyInfo.indexOf('commit')" class="commit-title-input" v-model="publishInfo.commitTitle" ></el-input>
                </div>

                <h1 class="input-title">活动海报上传</h1>
                <pic-uploader :uploaded-img="posterUrl" :data-object="{aid:detailSubmitAid}" @uploadSuccess="picUploadStatus='success'" class="pic-uploader"></pic-uploader>
                <h1 class="input-title">活动详情撰写</h1>
                <mavon-editor @save="handleMdSave" class="md-editor" @change="handleMdChange"  ref="mdEditor" @imgAdd="imgAdd" @imgDel="imgDel" v-model="descriptionSource"/>
                <div class="button-container clearfix">
                    <el-button type="primary" class="next-step-button" :plain="true" @click="onSubmitDetail">提交详细信息</el-button>
                </div>
            </div>
            <div v-if="activeStep===2" class="publish-success-container">
                <div class="check-container">
                    <div class="check-inner">
                        <svg class="icon"  viewBox="0 0 1024 1024" version="1.1"
                             width="100" height="100">
                            <path stroke="#07D93B"
                                    fill="#fff"
                                  stroke-width="3px"
                                  id="check-path"
                                  d="M1000.7956 359.920402c-17.526241-65.903606-61.761093-130.706857-63.702896-133.534239-9.20591-13.392554-27.523582-16.773056-40.913194-7.564204-12.474611 8.58218-16.2523 25.066908-9.26181 38.106407l-0.114743 0.050017c0.535467 0.788489 1.047397 1.59169 1.579921 2.383121 0.079437 0.123569 0.147107 0.253022 0.232428 0.376592 0.020595 0.035306 0.179469 0.264791 0.438377 0.653152 1.188618 1.779985 2.359585 3.577624 3.521724 5.378205 3.321659 5.172257 8.440957 13.395497 14.289903 23.681166 0.764952 1.359261 1.541674 2.706755 2.291915 4.074843 12.356926 22.186566 27.144048 52.446325 34.890664 81.958784 1.132718 3.61293 2.230131 7.24057 3.280469 10.891748 2.286031 7.976102 4.401419 16.03164 6.269669 24.184269 27.099915 117.946859 6.637435 239.391907-57.609755 341.957336-64.244248 102.565428-164.582487 173.970809-282.538174 201.06484-117.952744 27.099915-239.394849 6.637435-341.960277-57.606813s-173.970809-164.585429-201.06484-282.541116c-27.094031-117.949802-6.634492-239.394849 57.609755-341.957336 64.24719-102.565428 164.585429-173.973751 282.538174-201.06484 69.122291-15.87865 139.439085-15.399084 206.457757 0.623731 44.276043 10.591651 87.107502 27.964901 127.202786 51.925568 2.774424 1.6623 5.545906 3.33637 8.296793 5.060456l0.079437-0.126512c13.595561 7.61422 30.909968 3.321659 39.271488-10.026763 8.626311-13.772088 4.45732-31.927943-9.314768-40.551313-0.597251-0.37365-3.901258-2.427253-9.303-5.557675C535.015981-69.782878 226.08106 4.111541 78.171597 240.234746c-150.089579 239.60374-77.522059 555.51444 162.084623 705.604018 239.606682 150.089579 555.51444 77.519117 705.604018-162.087565C1028.316241 652.11463 1043.532912 497.461816 1000.7956 359.920402zM774.872743 316.268089l-262.187493 339.691899-6.39618 8.285025-8.429189-6.184347-221.071292-162.184655c-13.0954-9.611923-31.513103-6.78454-41.125027 6.316743-9.614865 13.104226-6.78454 31.513103 6.316743 41.122085l240.671731 176.568706 11.577263 8.493915c4.954539 2.356643 10.473966 3.712962 16.322911 3.712962 9.491297 0 18.152913-3.48936 24.825653-9.229447l9.391264-12.171572 276.683344-358.468542c9.929673-12.862972 7.549494-31.339518-5.313478-41.266249C803.276021 301.033765 784.802417 303.40806 774.872743 316.268089z"
                            ></path>
                        </svg>
                        <p class="submit-success-title">活动已经成功提交</p>
                    </div>


                </div>
                <div class="success-button-container">
                    <el-button class="go-index" type="primary" :plain="true"  @click="$router.push('/index')">去往首页</el-button>
                    <el-button class="go-activity-page" type="success" :plain="true" @click="$router.push('/activity/'+detailSubmitAid)">去往活动详情页</el-button>
                </div>
            </div>
        </div>
        <el-dialog
                title="添加标签"
                :visible.sync="tagDialogVisible"
                label-width="80px"
                :close-on-click-modal="false"
                :close-on-press-escape="false">
            <el-form ref="addTagForm" :rules="addTagRule" :model="addTagInfo">
                <el-form-item label="标签名称" prop="name">
                    <el-input v-model="addTagInfo.name" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="标签简介" prop="description">
                    <el-input type="textarea"
                              :maxlength="255"
                              placeholder="简单的介绍一下标签的用处"
                              v-model="addTagInfo.description">

                    </el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="handleAddTagCancel">取 消</el-button>
                <el-button type="primary" @click="handleAddTagSubmit">确 定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import provCityJson from '../../../../json/prov-city.json';
    import PicUploader from "./picUploader";
    export default {
        name: "activityPublish",
        components: {PicUploader},
        data() {
            var checkApplyTime=(rule,value,callback)=>{
                if(!value){
                    callback(new Error('请填写报名截止时间'));
                }
                try{
                    if(this.publishInfo.activityTime[0].getTime()<value[1].getTime()){
                        callback(new Error('报名截止时间不能晚于活动开始时间'))
                    }else{
                        callback();
                    }
                }catch (e) {
                    callback(new Error('请先填写活动时间'))
                }

            }
            return {
                activeStep: 0,
                tagDialogVisible:false,
                detailSubmitAid:0,
                pickerOptions: {
                    disabledDate(time) {
                        return time.getTime() < new Date(new Date().getTime() - 24 * 60 * 60 * 1000);
                    }
                },
                infoList:[
                    {
                        label:'真实姓名',
                        value:'realname'
                    },
                    {
                        label:'手机号码',
                        value:'phone'
                    },
                    {
                        label: '备注信息',
                        value:'commit'
                    },


                ],
                isCreate:true,
                publishRule:{
                    name:[
                        {required:true,message:'请填写活动名称',trigger:'blur'}
                    ],
                    tag:[
                        {required:true,message:'请填写活动标签',trigger:'blur'}
                    ],
                    activityTime:[
                        {required:true,message:'请填写活动时间',trigger:'blur'}
                    ],
                    applyTime:[
                        {required:true,message:'请填写报名截止时间',trigger:'blur'},
                        {validator:checkApplyTime,trigger:'blur'}
                    ],
                    address:[
                        {required:true,message:'请填写活动地址',trigger:'blur'}
                        // {trigger:'blur'}
                    ],
                    detailAddress:[
                        {required:true,message:'请填写活动详细地址',trigger:'blur'}
                        // {trigger:'blur'}
                    ],


                },
                addTagRule:{
                    name:[
                        {required:true,message:'请填写标签名称',trigger:'blur'},
                    ],
                    description:[
                        {required:true,message:'请填写标签描述',trigger:'blur'}
                    ]
                },
                selectCityList: [],

                publishInfo: {
                    name: '',
                    tag: [],
                    activityTime: '',
                    applyTime: '',
                    city: '',
                    cityName: '',
                    address: '',
                    detailAddress: '',
                    applyInfo:[],
                    commitTitle:'',
                },
                addTagInfo:{
                  name:'',
                  description:'',

                },
                posterUrl:'',
                picUploadStatus:'',
                publishDetail: '',
                descriptionSource:'',
                mapInstance: null,
                localSearchInstance: null,
                tagLoading: false,
                recentCityName: '',
                tagOptions: [],
                editAid:'',
                provCityOptions: provCityJson,
            }
        },
        mounted() {
            // console.log(this.$router,'para',)
            this.mapInstance = new BMap.Map('map-container');

            //如果参数中有aid那么进入编辑模式
            let aid=this.$router.currentRoute.params.id;
            if(aid){
                this.editAid=+aid;
                this.detailSubmitAid=+aid;
                // console.log('fd')
                this.activeStep++;
                this.isCreate=false;
                //todo fetch activity data
                axios(this.$apiAddress.getActivityEditInfo,{params:{aid:aid}}).then((response)=>{
                    let data=response.data.data;
                    this.publishInfo.applyInfo=data.applyInfo.split(',');
                    this.publishInfo.commitTitle=data.commitTitle;
                    this.posterUrl=data.poster;
                    this.descriptionSource=data.descriptionSource;
                })
                return;
            }
            //如果localStorage中有aid 进入编辑详细信息模式
            let sAid=localStorage.getItem('aid');
            if(sAid){
                this.detailSubmitAid=+sAid;
                this.activeStep++;
                let md=localStorage.getItem('md');
                if(md){
                    this.publishDetail=md;
                }
            }
        },
        methods: {
            tagRemoteMethod(query) {
                if (query !== '') {
                    this.tagLoading = true;
                    axios({
                        method:'get',
                        url:this.$apiAddress.searchTag,
                        params:this.setUrlParams({query:query})
                    }).then((response)=>{
                        this.tagOptions = response.data.data;
                    }).finally(()=>{
                        this.tagLoading = false;
                    })

                } else {
                    this.tagOptions = [];
                }
            },
            handleMdChange(val,render){

              this.publishDetail=render;
            },
            handleMdSave(){
                localStorage.setItem('md',this.descriptionSource);
            },

            addTag() {

                this.tagDialogVisible=true;
                this.addTagInfo.name='';
                this.addTagInfo.description='';
                setTimeout(()=>{
                    this.$refs['addTagForm'].resetFields();
                },0)

            },
            setCityName() {
                if (this.selectCityList.length !== 2) return;
                this.publishInfo.cityName = this.provCityOptions.find((v) => {
                    // console.log(v.code);
                    return v.code == this.selectCityList[0];
                })['children'].find((v) => {
                    return v.code == this.selectCityList[1];
                })['name'];
            },
            onSubmitBasic() {
                let params={};
                this.$refs['publishForm'].validate((valid)=>{
                    if(valid){
                        for(let i in this.publishInfo){
                            switch (i){
                                case'applyTime':
                                    params['applyStartTime']=this.publishInfo[i][0].getTime()/1000;
                                    params['applyStopTime']=this.publishInfo[i][1].getTime()/1000;
                                    break;
                                case'activityTime':
                                    params['activityStartTime']=this.publishInfo[i][0].getTime()/1000;
                                    params['activityStopTime']=this.publishInfo[i][1].getTime()/1000;
                                    break;
                                case'applyInfo':
                                case'commitTitle':
                                    break;
                                default:
                                    params[i]=this.publishInfo[i];
                            }
                        }
                        if(this.editAid){
                            params['aid']=this.editAid;
                        }
                        axios({
                            method:'post',
                            url:this.$apiAddress.postBasicActivityInfo,
                            data:this.setUrlParams(params),
                        }).then((response)=>{
                            this.$message({
                                message:'基本信息提交成功',
                                type: 'success'
                            });
                            this.detailSubmitAid=response.data.data.aid;
                            localStorage.setItem('aid',this.detailSubmitAid);
                            this.activeStep++;
                        })

                    }
                })
                // console.log(this.publishInfo)

            },
            onSubmitDetail(){
                if(!this.detailSubmitAid){
                    this.$alert('基本信息上传未成功，无法上传详细信息');
                }
                if(this.isCreate&&this.picUploadStatus!=='success'){
                    this.$alert('请上传海报照片!');
                    return;
                }
                if(this.publishInfo.applyInfo){
                        if(~this.publishInfo.applyInfo.indexOf('commit')&&(!this.publishInfo.commitTitle)) {
                            this.$alert('请填写备注标题!');
                            return;
                        }
                        let params={
                            description:this.publishDetail,
                            descriptionSource:this.descriptionSource,
                            applyInfo:this.publishInfo.applyInfo,
                            commitTitle:this.publishInfo.commitTitle,
                            aid:this.detailSubmitAid
                        }
                        axios({
                            method:'post',
                            url:this.$apiAddress.postDetailActivityInfo,
                            data:this.setUrlParams(params),
                        }).then((response)=>{
                            this.$message({
                                message:'详细信息提交成功',
                                type: 'success'
                            });
                            localStorage.setItem('aid','');
                            localStorage.setItem('md','');
                            this.activeStep++;
                        })
                }else{
                    this.$alert('请选择报名所需信息');
                }


            },
            fetchAddress(query, cb) {
                if (!query) return;
                let local = null;
                let res = [];
                this.mapInstance.centerAndZoom(this.publishInfo.cityName, 12);
                this.mapInstance.clearOverlays();
                local = new BMap.LocalSearch(this.mapInstance, {
                    map: this.mapInstance,
                    onSearchComplete: (results) => {
                        if (local.getStatus() === 0) {
                            for (let i = 0; i < results.getCurrentNumPois(); i++) {
                                res.push({
                                    value: results.getPoi(i).address + ' ' + results.getPoi(i).title,
                                    point: results.getPoi(i).point
                                });
                            }
                        }
                        // console.log(res);
                    }
                });
                local.search(query)
                cb(res);


            },
            handleAddressSelect(item) {
                this.mapInstance.centerAndZoom(item.point, 18);
                this.mapInstance.addOverlay(new BMap.Marker(item.point));
            },
            handleAddTagSubmit(){
                this.$refs['addTagForm'].validate((valid)=>{
                    if(valid){
                        axios({
                            method:'post',
                            url:this.$apiAddress.postAddTag,
                            data:this.setUrlParams(this.addTagInfo)
                        }).then((response)=>{
                            this.$message({
                                message:'标签添加成功',
                                type: 'success'
                            });
                            this.tagDialogVisible=false;
                        })
                    }
                })

            },
            handleAddTagCancel(){
                this.$confirm('确认取消添加标签？')
                    .then(() => {
                        this.tagDialogVisible=false;
                    })
                    .catch(() => {});
            },
            handleCityChange() {
                this.publishInfo.city = this.selectCityList[1];


            },
            imgDel(){

            },
            imgAdd(pos, $file) {
                // 第一步.将图片上传到服务器.
                var formdata = new FormData();
                formdata.append('file', $file);
                axios({
                    url: '/api/activity/publish/uploader/md-pic',
                    method: 'post',
                    data: formdata,
                    headers: {'Content-Type': 'multipart/form-data'},
                }).then((response) => {
                    // 第二步.将返回的url替换到文本原位置![...](./0) -> ![...](url)
                    /**
                     * $vm 指为mavonEditor实例，可以通过如下两种方式获取
                     * 1. 通过引入对象获取: `import {mavonEditor} from ...` 等方式引入后，`$vm`为`mavonEditor`
                     * 2. 通过$refs获取: html声明ref : `<mavon-editor ref=md ></mavon-editor>，`$vm`为 `this.$refs.md`
                     */
                    // console.log(url);
                    this.$refs.mdEditor.$img2Url(pos,response.data.data.url);
                })
            }
        }
    }
</script>

<style scoped>
    .main{
        margin-bottom: 20px;
    }
    .input-title{
        width: 80%;
        margin: 15px auto;
        font-size: 18px;
        padding-left: 10px;
    }
    .publish-container{
        margin-top: 15px;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 10px;
    }
    .publish-form-container{
        width: 50%;
        margin: 20px auto 35px;
    }
    .publish-detail-info-container{
        /*width: 50%;*/
        margin: 20px auto 35px;
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
        flex-wrap: wrap;
        justify-content: space-between;
    }
    .address-select{
        width: 30%;
    }
    .address-input{
        width: 65%;
        /*margin-left: 10px;*/
    }
    /*.detail-address-input{*/
        /*display: block;*/

    /*margin-top: 10px;*/
    /*}*/
    .add-tag-button{
        margin-left: 10px;
    }
    .next-step-button{
        float: right;
    }
    #map-container{
        height: 150px;
        margin-top: 15px;
        pointer-events: none;
    }
    .apply-info-container{
        width: 80%;
        margin: 20px auto 20px;
        display: flex;
        justify-content: space-around;
        border: 1px solid #eee;
        padding: 10px;
        border-radius: 10px;
    }
    .checkbox-container{

    }
    .commit-title-input{
        width: 40%;

    }
    .md-editor{
        width: 80%;
        margin: 20px auto 20px;
        min-height: 600px;
    }
    .button-container{
        width: 80%;
        margin: 15px auto 0;
    }
    .success-button-container{
        text-align: center;
        width: 50%;
        margin:15px auto 0;
    }
    .go-activity-page{
        margin-left: 15px;
    }
    .check-container{
        position: relative;
        width: 100%;
        height: 300px;
        margin: 15px auto;

    }
    .check-inner{
       position: absolute;
        text-align: center;
        left: 50%;
        top:50%;
        transform: translate(-50% ,-50%);

    }
    .submit-success-title{
        font-size: 20px;
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