<template>
  <div class="container">
     <div class="text-center" style="margin: 20px 0px 20px 0px;">
       <img src="https://camo.githubusercontent.com/aab17e69c1befd00860da2dc9582fbde3890b236df062b784c9b33c9e986b008/68747470733a2f2f6370352e736770312e63646e2e6469676974616c6f6365616e7370616365732e636f6d2f7a6f726f2f6c6172617675652d63646e2f6c6172617675652d6c6f676f2d6c696e652e706e67">
        </div>
                            <div class="card-header">父層測試區</div>
                                <br />
                                     <div class="card-body">
                        component 名稱：App.vue
                        這裡是父層                   </div>

                               

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="collapse navbar-collapse">
        <div class="navbar-nav">
                <div class="navbar-nav" >
                      <button @click="goToHome()">首頁</button>
                      <button @click="goToLogin()">登入</button>
                      <button @click="goToAppVue()">返回父層</button>
                      </div>
        </div>
      </div>
    </nav>
    <br />
    <hr>
     <div class="card-body"> component 元件變動區：</div>
<br />
    <router-view> </router-view>
  </div>
</template>
 
<script>
export default {
 
        
       
    methods: {

    goToHome() {
      this.$router.push({name: 'home'})    // Routing doesn't work
        console.log("按了【首頁】按鈕")
    },
    goToLogin() {
      this.$router.push({name: 'login'})    // Routing doesn't work
      console.log("按了【登入】按鈕")
    },
    goToAppVue() {
      this.$router.push('/')    // Routing doesn't work
      console.log("按了【返回父層】按鈕")
    },    

  },
};
</script>