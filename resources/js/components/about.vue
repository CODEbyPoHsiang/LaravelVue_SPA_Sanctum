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
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem("token");
        const email =localStorage.getItem("email");
        axios.get(`/api/user/${email}`).then(response => {
            // console.log(response.data);
            this.user = response.data;
        });
    },
    methods: {
        logout() {
            axios
                .get('api/logout')
                .then(response => {

                    // localStorage.removeItem("auth");
                    localStorage.removeItem("token");
                    localStorage.removeItem("auth");
                    localStorage.removeItem("email");
                    this.$router.push("/login");
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
};
</script>