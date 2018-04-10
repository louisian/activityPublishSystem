<template>
    <div class="activity-container" @click="handleClick">
        <img class="img" :src="activityData.bannner"/>
        <div class="activity-info">
            <h1 class="title">{{activityData.title||'活动'}}</h1>
            <p class="secondary-text date">{{activityData.date||'2018-01-01'}}</p>
            <p class="secondary-text location">{{activityData.location||'杭州'}}</p>
            <el-button @click="apply" type="primary" size="small" class="enter">报名</el-button>
        </div>

    </div>
</template>

<script>
    export default {
        name: "activityBlock",
        props:{
            activityData:{
                default:()=>{
                    return{
                        id:'',
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    }

                }
            }
        },
        methods:{
            handleClick(){
                this.$emit('activityClick',this.activityData.id);
            },
            apply(e){
                e.stopPropagation();
                this.$emit('clickApply',this.activityData.id);
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