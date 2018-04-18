<template>
    <div >
        <Header-component @loginSuccess="handleLoginSuccess" @logout="isLogin=false" :is-login="isLogin"></Header-component>
        <div class="app-main" style="clear: both;">
            <router-view></router-view>
        </div>
        <Footer-component></Footer-component>
    </div>
</template>
<style type="text/scss" scope="scope">
    .app-main{
        margin-top: 70px;
    }
</style>
<script type="text/javascript">
    import HeaderComponent from './header-component.vue';
    import FooterComponent from './footer-component.vue';

    export default {
        components: {
            'Header-component': HeaderComponent,
            'Footer-component': FooterComponent
        },
        data() {
            return {
                isLogin:false
            };
        },
        mounted() {
            axios.get(this.$apiAddress.getLoginStatus).then((response)=>{
               this.isLogin=true;
            }).catch((response)=>{
                // console.log(response)
            })
        },
        methods: {
            handleLoginSuccess(){
                this.isLogin=true;
            }
        }
    }
</script>