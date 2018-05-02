<template>
    <el-dialog width="30%"
               @close="$emit('update:dialogVisible',false)"
               title="用户登录"
               :visible.sync="digVisible">
        <el-form
                status-icon
                :model="loginInfo"
                :rules="loginRules"
                ref="loginForm"
                label-width="100px">
            <el-form-item label="用户昵称" prop="username">
                <el-input v-model="loginInfo.username"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="password">
                <el-input type="password" v-model="loginInfo.password"></el-input>
            </el-form-item>
        </el-form>
        <span slot="footer" class="dialog-footer">
            <el-button @click="handleCancel">取 消</el-button>
            <el-button :loading="submitting"  type="primary" @click="handleSubmit">登 录</el-button>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        name: "loginDialog",
        props:['dialogVisible'],
        data(){
            return{
                digVisible:false,
                loginInfo:{
                    username:'',
                    password:'',
                },
                loginLoading:false,
                loginRules:{
                    username:[
                        {required:true,message:'请输入用户昵称',trigger:'blur'}
                    ],
                    password:[
                        {required:true,message:'请输入密码',trigger:'blur'},
                        {min:6,message:'密码必须大于6位',trigger:'blur'}
                    ]
                },
                submitting:false,
            }
        },
        methods:{
            handleSubmit(){
                this.submitting=true;
                this.$refs['loginForm'].validate((valid)=>{
                    if(valid){
                        axios({
                            method:'post',
                            url:this.$apiAddress.postLogin,
                            data:this.setUrlParams(this.loginInfo)
                        }).then((response)=>{
                            this.$message({
                                message: '登录成功',
                                type: 'success'
                            });
                            this.digVisible=false;
                            this.$emit('loginSuccess')
                        }).catch((error)=>{
                            // console.log(response,resText)


                        }).finally(()=>{
                            this.submitting=false;
                        })
                    }
                    this.submitting=false;
                })

            },
            handleCancel(){
                this.digVisible=false;
            }
        },
        watch:{
            'dialogVisible':function (val) {

                this.digVisible=!!val;
                if(!!val){
                    setTimeout(()=>{
                        this.$refs['loginForm'].resetFields();
                    },0)
                }
            }
        }
    }
</script>

<style scoped>

</style>