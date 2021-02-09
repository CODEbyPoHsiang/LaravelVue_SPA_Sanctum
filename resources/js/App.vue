<template>
  <div class="container">
    <div class="text-center" style="margin: 20px 0px 20px 0px">
      <a href="https://shouts.dev/" target="_blank"
        ><img src="https://i.imgur.com/Nt3kJXa.png" /></a
      ><br />
      <span class="text-secondary"
        >Laravel SPA with Vue 3, Auth (Sanctum), CURD Example</span
      >
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="collapse navbar-collapse">
        <!-- for logged-in user-->
        <div class="navbar-nav" v-if="isLoggedIn === 'true'">
          <router-link to="/allproduct" class="nav-item nav-link"
            >產品清單</router-link
          >
          <router-link to="/createproduct" class="nav-item nav-link"
            >建立產品</router-link
          >

          <router-link to="/about" class="nav-item nav-link"
            >使用者資料</router-link
          >
          <button type="button" @click="logout">登出</button>
        </div>
        <!-- for non-logged user-->
        <div class="navbar-nav" v-else>
          <router-link to="/home" class="nav-item nav-link">Home</router-link>
          <router-link to="/login" class="nav-item nav-link">login</router-link>
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
      isLoggedIn: "false",
    };
  },
  created() {
    if (window.localStorage.getItem("auth") === "true") {
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
        .post("api/logout")
        .then((response) => {
          // localStorage.removeItem("auth");
          localStorage.removeItem("token");
          localStorage.removeItem("auth");
          localStorage.removeItem("email");
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
  },
};
</script>
