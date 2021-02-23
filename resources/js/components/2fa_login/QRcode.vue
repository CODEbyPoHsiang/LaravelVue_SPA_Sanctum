<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">QRcode</div>
          <br/>

<center>
          <div v-html="qrcode"></div>
          <div class="card-body">component 名稱：home.vue 這裡是QRcode</div>
          <div >請輸入OTP 六位數字</div>
          <div class="card-body"><input type="text" maxlength="6" oninput="this.value=this.value.replace(/[^0-9]/g,'');" /></div>
          </center>
          </br>
          </br>
          
          
           
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">
        確認
      </button>
        <button type="button" @click="cancel" class="btn btn-danger pull-right">取消</button>
</div>
          </div>
          <!-- <button>確認登入</button> -->
     
        </div>
       
 
      </div>
    </div>
  </div>
</template>

<script>
export default {
     data() {
        return {
            qrcode: "",
            errors: []
        };
    },
     methods: {
    cancel(){
       localStorage.removeItem("qrcode");
       localStorage.removeItem("qrcode_scan");
       localStorage.removeItem("first_login");
       this.$router.push("/login");
    }

  },
  mounted() {
    this.qrcode =localStorage.getItem("qrcode");
    // localStorage.removeItem("qrcode");
  },
};
</script>