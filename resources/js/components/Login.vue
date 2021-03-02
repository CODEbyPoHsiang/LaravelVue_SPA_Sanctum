<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">登入</div>
          <div class="card-body">
            <center>
              <form>
                <div>
                  <label>信箱</label>
                  <input type="text" v-model="email" />
                  <span v-if="errors.email">
                    {{ errors.email[0] }}
                  </span>
                </div>

                <div>
                  <label>密碼</label>
                  <input type="password" v-model="password" />
                  <span v-if="errors.password">
                    {{ errors.password[0] }}
                  </span>
                </div>

                <!-- <button>確認登入</button> -->
                <button type="submit" class="btn btn-primary" @click="login">
                  登入
                </button>
              </form>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: "",
      errors: [],
    };
  },
  methods: {
    login(e) {
      e.preventDefault();
      // axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/login", {
            email: this.email,
            password: this.password,
          })
          .then((response) => {
            console.log(response);

            switch (response.data.success) {
              case "getcode":
                sessionStorage.setItem("qrcode", response.data.QR_code);
                // localStorage.setItem("email", response.data.email);
                sessionStorage.setItem("first_login", "true");
                sessionStorage.setItem("qrcode_scan", "true");
                sessionStorage.setItem("email", response.data.email);
                sessionStorage.setItem(
                  "google2fa_secret",
                  response.data.google2fa_secret
                );
                // document.cookie = `token = ${response.data.token}`;
                // this.$emit("singin", "true");
                this.$router.push("/qrcode");
                break;
              case "toConfirmTwoFa":
                sessionStorage.setItem("first_login", "true");
                sessionStorage.setItem("otp2fa", "true");
                sessionStorage.setItem("email", response.data.email);
                sessionStorage.setItem(
                  "google2fa_secret",
                  response.data.google2fa_secret
                );
                // document.cookie = `token = ${response.data.token}`;
                // this.$emit("singin", "true");
                this.$router.push("/otp2fa");
                break;
              case false:
                alert(response.data.message);
                break;
            }

            // if (response.data.success === "getcode") {
            //   localStorage.setItem("qrcode", response.data.QR_code);
            //   // localStorage.setItem("email", response.data.email);
            //   localStorage.setItem("auth", "true");
            //   localStorage.setItem("qrcode_scan", "true");
            //   // document.cookie = `token = ${response.data.token}`;
            //   // this.$emit("singin", "true");
            //   this.$router.push("/qrcode");
            //   // return this.$router.push("/login");
            // }
            //  if (response.data.success === "toConfirmTwoFa") {
            //   // localStorage.setItem("qrcode", response.data.QR_code);
            //   // localStorage.setItem("email", response.data.email);
            //   localStorage.setItem("auth", "true");
            //   localStorage.setItem("otp2fa", "true");
            //   // document.cookie = `token = ${response.data.token}`;
            //   // this.$emit("singin", "true");
            //   this.$router.push("/otp2fa");
            //   // return this.$router.push("/login");
            // }
            // else {
            //   alert(response.data.message);
            // }
            // // console.log(response.request.status);
            // // console.log(response.data.token);
          });
      // });
    },
    logout() {
      axios
        .post("api/logout")
        .then((response) => {
          sessionStorage.removeItem("token");
          sessionStorage.removeItem("auth");
          sessionStorage.removeItem("email");
          // document.cookie = `token = `;
          this.$emit("singin", "false");
          this.$router.push("/login");
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    console.log("這是login.vue");
  },
};
</script>
