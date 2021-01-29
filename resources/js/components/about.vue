<template>
    <div>
        <p>使用者名稱：{{ user.name }}</p>
        <p>使用者信箱：{{ user.email }}</p>
        <button type="button" @click="logout">登出</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: ""
        };
    },
    mounted() {
        axios.get("/api/user").then(response => {
            this.user = response.data;
        });
    },
    methods: {
        logout() {
            axios
                .post("api/logout")
                .then(response => {
                    console.log(response);
                    localStorage.removeItem("auth");
                    this.$router.push("/login");
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>