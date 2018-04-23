<template>
    <el-dialog
            title="报名信息确认"
            :visible.sync="digVisible"
            width="660px">
        <el-form
                status-icon
                :model="applyInfo"
                ref="applyForm"
                label-width="100px">
            <template>
                <el-form-item v-for="item,index in applyInfo"  :key="index" :label="applyLabel[index]">
                    <el-input v-model="applyInfo[index]" readonly v-if="index!=='commit'">

                    </el-input>
                    <el-input v-model="applyInfo.commit" v-else>

                    </el-input>
                </el-form-item>
            </template>

        </el-form>
        <span slot="footer" class="dialog-footer">
            <el-button @click="handleCancel">取 消</el-button>
            <el-button type="primary" :loading="popLoading" :disabled="popError" @click="handleConfirm">确 认</el-button>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        name: "applyDialog",
        props:{dialogVisible:
                {
                    required:true
                },
            aid:{
                required:true
            }
        },
        data(){
            return{
                digVisible:false,
                applyInfo:{

                },
                applyLabel:{
                    'phone':'电话号码',
                    'realname':'真实姓名',
                    'commit':'',
                },
                popLoading:false,
                popError:false,
            }
        },
        mounted(){

        },
        methods:{
            handleConfirm(){
                this.popLoading=true;
                axios({
                    method:'post',
                    url:this.$apiAddress.postActivityEnter,
                    data:{

                        aid:this.aid,
                        applyInfo:JSON.stringify(this.applyInfo),
                    }
                }).then((response)=>{
                    this.$message({
                        message: '报名成功',
                        type: 'success'
                    });
                    //emit applySuccess 来刷新列表
                    this.$emit('applySuccess');
                    this.$emit('update:dialogVisible',false);
                    this.digVisible=false;
                }).catch(()=>{

                }).finally(()=>{
                    this.popLoading=false;
                })

            },
            handleCancel(){
                this.$emit('update:dialogVisible',false);
                this.digVisible=false;
            }
        },
        watch:{
            'dialogVisible':function (val) {

                this.digVisible=!!val;
                if(!!val){
                    this.popLoading=true;
                    this.popError=false;
                    axios(this.$apiAddress.getApplyInfo,{params:{aid:this.aid}}).then((response)=>{
                        let data=response.data.data;
                        for(let i in data.applyInfo){
                            if(i!=='commit'){
                                this.$set(this.applyInfo,i,data.applyInfo[i]);
                            }else{
                                this.$set(this.applyInfo,i,'');
                            }

                        }
                        // this.$set(this.applyInfo,data.applyInfo);
                        this.applyLabel.commit=data.commitTitle;
                    }).catch(()=>{
                        this.popError=true;
                    }).finally(()=>{
                        this.popLoading=false;
                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>