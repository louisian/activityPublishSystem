<template>
    <div class="main">
        <el-row :gutter="20">
            <el-col :span="6" v-for="item,key in activityDataList" :key="key">
                <activity-block @activityClick="handleActivityClick" :activity-data="item"></activity-block>
            </el-col>

        </el-row>
        <el-pagination
                background
                layout="prev, pager, next , sizes , total"
                :total="1000">
        </el-pagination>
    </div>
</template>

<script>
    import ActivityBlock from "./activityBlock";
    export default {
        name: "index",
        components: {ActivityBlock},
        data(){
            return{
                activityDataList:[
                    {
                        id:1,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:2,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:3,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:4,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:5,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    },
                    {
                        id:6,
                        banner:'',
                        title:'',
                        date:'',
                        location:'',
                    }
                ],
                pageInfo:{
                    size:20,
                    sizes:[12,16,20,24],
                    total:20,
                    current:1
                }
            }
        },
        methods:{
            handleActivityClick(id){
               this.$router.push('/activity/'+id);
                // console.log(id);
            }
        }
    }
</script>

<style scoped>

</style>