<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">OTP</div>

  <div class="card-body">component 名稱：OTP
    <center>
            <div >請輸入OTP 六位數字</div>
            <input type="text" maxlength="6" oninput="this.value=this.value.replace(/[^0-9]/g,'');" />
            </center>
</div>

        
                  <div class="card-footer">
        <button type="button" @click="cancel" class="btn btn-danger pull-right">取消</button>
</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    cancel(){
       localStorage.removeItem("otp2fa");
       localStorage.removeItem("first_login");
       this.$router.push("/login");
    }

  },
  mounted() {
    console.log("這是otp.vue");
  },
};
</script>
