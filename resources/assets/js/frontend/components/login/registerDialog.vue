<template>
    <el-dialog width="40%"
               @close="$emit('update:dialogVisible',false)"
               :title="'用户'+(isRegister?'注册':'信息编辑')"
               :visible.sync="digVisible"
               :close-on-click-modal="false"
               :close-on-press-escape="false"
                top="5vh">
        <div class="register-form">
            <el-form
                    status-icon
                    :model="registerInfo"
                    :rules="registerRules"
                    ref="registerForm"
                    label-width="100px"
                   >
                <el-row>
                    <el-col :span="12">
                        <el-form-item label="用户昵称" prop="username">
                            <el-input v-model="registerInfo.username"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="真实姓名" prop="realname">
                            <el-input v-model="registerInfo.realname"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="请输入密码" prop="password">
                            <el-input v-model="registerInfo.password" type="password"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="请重复密码" prop="rePassword">
                            <el-input v-model="registerInfo.rePassword" type="password"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="电话号码" prop="phone">
                            <el-input v-model="registerInfo.phone"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="常住城市" prop="city">
                            <el-cascader
                                    class="address-select"
                                    placeholder="请选择城市"
                                    :props="{value:'code',label:'name'}"
                                    :options="provCityOptions"
                                    v-model="selectCityList"
                                    @blur="setCityName"
                                    @change="handleCityChange">
                            </el-cascader>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="每周空闲时间" prop="freeDay">
                    <el-checkbox-group class="checkbox-container" v-model="registerInfo.freeDay">
                        <el-checkbox :class="{'no-margin':index===4}" border v-for="item,index in weekList" :label="item.value" :key="index">{{item.label}}</el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
                <el-form-item label="每日空闲时间" prop="freeTime">
                    <el-slider
                            v-model="registerInfo.freeTime"
                            range
                            :format-tooltip="formatTooltip"
                            show-stops
                            :max="24">
                    </el-slider>
                </el-form-item>
                <el-form-item label="喜欢的标签" prop="tag">
                    <el-select
                            v-model="registerInfo.tag"
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
                </el-form-item>
            </el-form>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click="handleCancel">取 消</el-button>
            <el-button :loading="submitting" type="primary" @click="handleSubmit">确 定</el-button>
        </span>
    </el-dialog>
</template>

<script>
    import provCityJson from '../../../../json/prov-city.json';
    export default {

        name: "registerDialog",
        props:{dialogVisible:
                {
                    required:true
                },
                isRegister:{
                    default:true
                }
            },
        data(){
            var checkPasswordEqual=(rule,value,callback)=>{
                if(value===this.registerInfo.password){
                    callback()
                }else{
                    callback(new Error('两次填写的密码不一致'))
                }
            }
            return{
                selectCityList: [],
                isReg:this.isRegister,
                digVisible:false,
                submitting:false,
                registerRules:{
                    username:[
                        {required:true,message:'请输入用户名',trigger:'blur'}
                    ],
                    realname:[
                        {required:true,message:'请输入真实姓名',trigger:'change'},
                    ],
                    password:[
                        {required:true,message:'请输入密码',trigger:'blur'},
                        {min:6,message:'密码必须大于6位',trigger:'blur'}
                    ],
                    rePassword:[
                        {required:true,message:'请重复密码',trigger:'blur'},
                        {validator:checkPasswordEqual,trigger:'blur'},
                    ],
                    phone:[
                        {required:true,message:'请输入电话号码',trigger:'blur'},
                        {pattern:/^1([0-9]{10})$/,message:'请输入正确的电话号码',trigger:'blur'},
                    ],
                    city:[
                        {required:true,message:'请选择常住城市',trigger:'change'},
                    ],
                    freeDay:[
                        {required:true,message:'请选择每周空闲时间',trigger:'change'},
                    ],
                    freeTime:[
                        {required:true,message:'请选择每日空闲时间',trigger:'change'},
                    ],
                    tag:[
                        {required:true,message:'请选择喜欢的标签',trigger:'blur'},
                    ]
                },
                registerInfo:{
                    username:'',
                    password:'',
                    rePassword:'',
                    phone:'',
                    realname:'',
                    city:'',
                    cityName:'',
                    freeDay:[],
                    freeTime:[22,24],
                    tag:''
                },
                weekList:[
                    {
                        label:'星期一',
                        value:'1'
                    },
                    {
                        label:'星期二',
                        value:'2'
                    },
                    {
                        label:'星期三',
                        value:'3'
                    },
                    {
                        label:'星期四',
                        value:'4'
                    },
                    {
                        label:'星期五',
                        value:'5'
                    },
                    {
                        label:'星期六',
                        value:'6'
                    },
                    {
                        label:'星期日',
                        value:'0'
                    },

                ],
                provCityOptions: provCityJson,
                tagOptions:[],
                tagLoading:false,
            }
        },
        methods:{
            formatTooltip(val){
                return val+':00';
            },
            handleCancel(){
                this.digVisible=false;
                this.$emit('update:dialogVisible',false)
            },
            handleSubmit(){
                this.submitting=true;
                this.setCityName();
                this.$refs['registerForm'].validate((valid)=>{
                  if(valid){
                      this.submitting=false;
                  }
                  this.submitting=false;
                })
            },
            handleCityChange() {
                this.registerInfo.city = this.selectCityList[1];


            },
            tagRemoteMethod(){

            },
            setCityName() {
                if (this.selectCityList.length !== 2) return;
                this.registerInfo.cityName = this.provCityOptions.find((v) => {
                    // console.log(v.code);
                    return v.code == this.selectCityList[0];
                })['children'].find((v) => {
                    return v.code == this.selectCityList[1];
                })['name'];
            }
        },
        mounted(){
          if(!this.isReg){
              console.log('write edit')
          }
        },
        watch:{
            'dialogVisible':function (val) {

                this.digVisible=!!val;
                if(!!val){
                    setTimeout(()=>{
                        this.$refs['registerForm'].resetFields();
                    },0)
                }
            }
        }
    }
</script>

<style scoped>
    .register-form{
        /*width: 50%;*/
        margin: 20px auto;
    }
    .no-margin{
        margin-left: 0!important;
    }
</style>
<style>
    .el-select{
        width: 100%;
    }
    .el-cascader{
        width: 100%;
    }
</style>