<template>
    <div id="main">
        <Header/> 
        <div class="content">
            <router-view></router-view>
        </div>
    </div> 
</template>

<script>
    import Header from './Header.vue';

    export default { 
        name: 'main-app',
        components: {Header} 
    }
</script>

<style lang="scss"> 
    @import 'bootstrap/dist/css/bootstrap.css';
    @import 'bootstrap-vue/dist/bootstrap-vue.css';
</style>
