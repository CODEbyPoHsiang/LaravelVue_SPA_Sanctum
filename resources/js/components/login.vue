<template>
  <div>
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
        確認登入
      </button>

      <div class="card-body">component 名稱：login.vue 這裡是登入頁</div>
    </form>
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
  mounted() {
    console.log("這是login.vue");
  },
  methods: {
    login(e) {
      e.preventDefault();
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/login", {
            email: this.email,
            password: this.password,
          })
          .then((response) => {
            // console.log(response);
            if (response.request.status === 200) {
              localStorage.setItem("token", response.data.token);
              localStorage.setItem("email", response.data.email);
              localStorage.setItem("auth", "true");
              this.$emit("singin", "true");
              this.$router.push("/about");
              // return this.$router.push("/login");
            } else {
              alert(response.data.message);
            }

            // console.log(response.request.status);
            // console.log(response.data.token);
          })
          .catch((error) => {
            this.errors = error.response.data.errors;
          });
      });
    },
    logout() {
      axios
        .post("api/logout")
        .then((response) => {
          // localStorage.removeItem("auth");
          localStorage.removeItem("token");
          localStorage.removeItem("auth");
          localStorage.removeItem("email");
          this.$emit("singin", "false");
          this.$router.push("/login");
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
