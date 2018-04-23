<template>
    <div class="activity-container" @click="handleClick">
        <img class="img" :src="activityData.poster"/>
        <div class="activity-info">
            <h1 class="title">{{activityData.name||'活动'}}</h1>
            <p class="secondary-text date">{{activityData.activityStartTime||'2018-01-01'}}</p>
            <p class="secondary-text location">{{activityData.cityName||'杭州'}}</p>
            <el-button v-if="!editMode" @click="apply" :disabled="applied" type="primary" size="small" class="enter">{{applied?'已报名':'报名'}}</el-button>
            <el-button v-if="editMode" @click="edit" type="primary" size="small" class="enter">编辑</el-button>
        </div>
    </div>
</template>

<script>
    import ApplyDialog from "./applyDialog";
    export default {
        name: "activityBlock",
        components: {ApplyDialog},
        props:{
            activityData:{
                default:()=>{
                    return{
                        aid:'',
                        poster:'',
                        name:'',
                        activityStartTime:'',
                        cityName:'',
                    }

                }
            },
            editMode:{
                default:false
            },
            applied:{
                default:false
            }
        },
        data(){
            return{
                // applyVisible:false,
            }
        },
        methods:{
            handleClick(){
                this.$emit('activityClick',this.activityData.aid);
            },
            apply(e){
                e.stopPropagation();
                axios({
                    methods:'get',
                    url:this.$apiAddress.getLoginStatus
                }).then((response)=>{
                    // this.applyVisible=true;
                    this.$emit('applyClick',this.activityData.aid);
                })

            },
            edit(e){
              e.stopPropagation();
              this.$emit('clickEdit',this.activityData.aid);
            }
        }
    }
</script>

<style scoped>
    .activity-container{
        width: 100%;
        position: relative;
        margin-bottom: 20px;
        border: 1px solid #eee;
        /*background: #f3f3f3;*/
        height: 282px;
        cursor: pointer;
        overflow: hidden;
        transition:all 0.25s ease;
        border-radius: 5px;

    }
    .activity-container:hover{
        transform: translateY(-2%);
         box-shadow: 1px 4px 10px 2px #CCC;
    }
    .activity-info{
        padding: 15px 15px 0;
    }
    .img{
        border-bottom: 1px solid #eee;
        width: 100%;
        background-color: #fff;
        height: 135px;
    }
    .title{
        margin: 0;
        font-size: 16px;
        max-height: 38px;
        overflow: hidden;
     }
    .secondary-text{
        font-size: 13px;
        color: #999;
    }
    .enter{
        position: absolute;
        right: 15px;
        bottom: 15px;
    }

</style>