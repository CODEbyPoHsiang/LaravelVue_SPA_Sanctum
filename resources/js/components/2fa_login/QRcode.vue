<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">QRcode</div>
          <center>
            <div v-html="qrcode"></div>
            <div class="card-body">component 名稱：home.vue 這裡是QRcode</div>
            <div>請輸入OTP 六位數字</div>
            <div class="card-body">
              <input
                type="text"
                maxlength="6"
                v-model="one_time_password"
                oninput="this.value=this.value.replace(/[^0-9]/g,'');"
              />
            </div>
          </center>
          <div class="card-footer">
            <button 
              type="submit" 
              class="btn btn-primary"               
              @click="otpchk"
              >
                確認
              </button>
            <button
              type="button"
              @click="cancel"
              class="btn btn-danger pull-right"
            >
              取消
            </button>
          </div>
        </div>
        <!-- <button>確認登入</button> -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      qrcode: "",
      email:"",
      google2fa_secret: "",
      one_time_password: "",
      errors: [],
    };
  },
  methods: {
    otpchk(e) {
      e.preventDefault();
      axios
          .post("/api/google2fa_login", {
           google2fa_secret: localStorage.getItem('google2fa_secret'),
           email: localStorage.getItem('email'),
           one_time_password: this.one_time_password,
          }).then((response) => {
            console.log(response);
            switch (response.data.success) {
              case true:
                localStorage.setItem("token", response.data.login_token);
                localStorage.setItem("auth", "true");
                
                //emit 改變父層navbar元件
                this.$emit("singin", "true");

                this.$router.push("/userabout");
                break;
              case false:
                alert(response.data.message)
                break;
            }
      });
     },
    cancel() {
      // localStorage.removeItem("qrcode");
      // localStorage.removeItem("qrcode_scan");
      // localStorage.removeItem("first_login");
      // localStorage.removeItem("google2fa_secret");
      // localStorage.removeItem("email");
      localStorage.clear();
      this.$router.push("/login");
    },
  },
  mounted() {
    this.qrcode = localStorage.getItem("qrcode");
    // localStorage.removeItem("qrcode");
  },
};
</script>
