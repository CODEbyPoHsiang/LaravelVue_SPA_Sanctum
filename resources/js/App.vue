<template>
  <div class="vue-tempalte">
    <div class="text-center" style="margin: 20px 0px 20px 0px">
      <span class="text-white"
        ><h3>Laravel SPA with Vue (Sanctum) CURD</h3> </span
      >
    </div>

    <nav class="navbar navbar-expand-lg navbar-primary  bg-primary ">
      <div class="collapse navbar-collapse">
        <!-- for logged-in user-->
        <div class="navbar-nav" v-if="isLoggedIn === 'true'">
          <router-link to="/allproduct" class="nav-item nav-link"
            >產品清單</router-link
          >
          <router-link to="/userabout" class="nav-item nav-link"
            >使用者資料</router-link
          >
          <router-link to="/taipeimap" class="nav-item nav-link"
            >台北UbikeMap</router-link
          >
          <router-link to="/taichungmap" class="nav-item nav-link"
            >台中UbikeMap</router-link
          >

          <button
            type="button"
            @click="logout"
            class="btn btn-danger pull-right"
          >
            登出
          </button>
        </div>
        <!-- for non-logged user-->
        <div class="navbar-nav" v-else>
          <router-link to="/home" class="nav-item nav-link">Home</router-link>
          <router-link to="/login" class="nav-item nav-link">Login</router-link>
          <router-link to="/register" class="nav-item nav-link"
            >Register</router-link
          >
        </div>
      </div>
    </nav>
    <br />
    <router-view @singin="singin" />
  </div>
</template>

<script>
export default {
  data() {
    return {
      email:"",
      isLoggedIn: "false",
    };
  },
  created() {
    if (window.sessionStorage.getItem("auth") === "true") {
      this.isLoggedIn = "true";
    }
  },
  //     computed: {
  //   isLoggedIn() {
  //     return localStorage.getItem("auth");
  //   }
  // },
  methods: {
    logout() {
      axios
        .post("api/logout",{
            email: sessionStorage.getItem("email"),
          })
        .then((response) => {
          sessionStorage.clear();

          //登出把寫在表頭的
          axios.defaults.headers.common["Authorization"] ="" ;

          // localStorage.removeItem("first_login");
          // localStorage.removeItem("qrcode");
          // localStorage.removeItem("qrcode_scan");
          // localStorage.removeItem("otp2fa");
          // localStorage.removeItem("qrcode");
          // localStorage.removeItem("google2fa_secret");
          // localStorage.removeItem("token");
          // localStorage.removeItem("auth");
          // localStorage.removeItem("email");
          document.cookie = `token=`;
          this.isLoggedIn = "false";
          this.$router.push("/login");
        })
        .catch((error) => {
          console.log(error);
        });
    },
    singin(para) {
      this.isLoggedIn = para;
    },
    handleStorageChange() {
      // axios.post(`/api/remove_password/${localStorage.getItem("email")}`).then((response) => {
      // console.log(response.data);
      sessionStorage.clear();
      this.isLoggedIn = "false";
      this.$router.push("/login");
      // });
    },
  },
  ready() {
    window.addEventListener("storage", this.handleStorageChange);
  },
  beforeDestroy() {
    window.removeEventListener("storage", this.handleStorageChange);
  },
  mounted() {
    window.addEventListener("storage", this.handleStorageChange);
  },
 
};
</script>

