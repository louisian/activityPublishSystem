<template>
    <div class="pic-uploader-container">
        <el-upload
                class="avatar-uploader"
                action="/api/activity/publish/uploader/poster"
                :show-file-list="false"
                :on-success="handlePicSuccess"
                :on-progress="handlePicUploading"
                :on-error="handlePicError"
                :before-upload="beforePicUpload">
            <img v-if="imageUrl" :src="imageUrl" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
        </el-upload>
        <div class="upload-rule">
            <h1 class="upload-title">请在左侧上传活动海报</h1>
            <p class="rule-content">
                支持 jpg/jpeg/png 格式，大小不要超过 2MB
                <br/>
                建议上传 1600*900 分辨率的图片
            </p>
            <div class="progress-container">
                <p class="progress-title">上传进度</p>
                <div>
                    <el-progress :percentage="uploadPercent" class="upload-progress" :status="uploadStatus"></el-progress>
                </div>

            </div>

        </div>
    </div>

</template>
<style scoped>

    .pic-uploader-container{
        width: 80%;
        margin: 0 auto;
        display: flex;
        justify-content: space-around;
        border: 1px solid #eee;
        padding: 15px;

    }
    .upload-progress{
       margin-left: 10px;
    }

    .progress-container{
        /*display: flex;*/
        margin-top: 15px;
    }

    .upload-rule{
        margin-left: 15px;
    }
    .upload-title{
        margin: 5px 0;
        padding-bottom: 5px;

    }
    .rule-content,.progress-title{
        color: #737373;
        font-size: 13px;
    }

</style>
<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 320px;
        height: 180px;
        line-height: 180px;
        text-align: center;
    }
    .avatar {
        width: 320px;
        height: 180px;
        display: block;
    }
</style>
<script>
    export default {
        name: "picUploader",
        data() {
            return {
                imageUrl: '',
                uploadPercent:0,
                uploadStatus:''
            };
        },
        methods: {
            handlePicUploading(event,file){
                // console.log(event)
                this.uploadPercent=event.percent;
            },
            handlePicSuccess(res, file) {
                this.uploadStatus='success';
                this.imageUrl = URL.createObjectURL(file.raw);
                this.$emit('uploadSuccess');
            },
            handlePicError(){
                this.uploadStatus='exception';
            },
            beforePicUpload(file) {
                this.uploadPercent=0;
                this.uploadStatus='';
                const isJPG = file.type === 'image/jpeg';
                const isPng = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!(isJPG||isPng)) {
                    this.$message.error('上传海报图片格式错误！');
                }
                if (!isLt2M) {


                    this.$message.error('上传海报图片大小不能超过 2MB!');
                }


                return (isJPG||isPng)&&isLt2M;
            }
        }
    }
</script>

<style scoped>

</style>