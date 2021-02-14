<template>
  <div>
    <form>
      <div>
        <label>帳號</label>
        <input type="text" v-model="name" />
        <span v-if="errors.name">
          {{ errors.name[0] }}
        </span>
      </div>
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

      <div>
        <label>再次確認密碼</label>
        <input type="password" v-model="password_confirmation" />
        <span v-if="errors.password_confirmation">
          {{ errors.password_confirmation[0] }}
        </span>
      </div>

      <!-- <button>確認登入</button> -->
      <button type="submit" class="btn btn-primary" @click="register">
        確認新增
      </button>

      <div class="card-body">component 名稱：register.vue 這裡是註冊頁</div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      name: "",
      password: "",
      password_confirmation: "",
      errors: [],
    };
  },
  mounted() {
    console.log("這是register.vue");
  },
  methods: {
    register(e) {
      e.preventDefault();
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/register", {
            email: this.email,
            name: this.name,
            password: this.password,
            password_confirmation: this.password_confirmation,
          })
          .then((response) => {
            // console.log(response);
            if (response.request.status === 200) {
                alert("註冊成功，請登入");
              this.$router.push("/login");
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
  },
};
</script>
