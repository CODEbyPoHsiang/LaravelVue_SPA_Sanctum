<template>
  <div>
    <h3 class="text-center">產品清單</h3>
    <br />

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>品名</th>
          <th>價格</th>
          <th>建立時間</th>
          <th>更新時間</th>
          <th colspan="2">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.id }}</td>
          <td>{{ product.name }}</td>
          <td>{{ product.detail }}</td>
          <td>{{ product.created_at }}</td>
          <td>{{ product.updated_at }}</td>
          <td colspan="2">
            <div class="btn-group" role="group">
              <router-link
                :to="{ name: 'editproduct', params: { id: product.id } }"
                class="btn btn-primary"
                >編輯</router-link
              >&nbsp;&nbsp;
              <button class="btn btn-danger" @click="deleteProduct(product.id)">
                刪除
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],
    };
  },
  created() {
    axios.get("/sanctum/csrf-cookie").then((response) => {
      console.log(response.config.headers.Authorization),
        axios.get("api/products").then((response) => {
          this.products = response.data;
        });
    });
  },
  methods: {
    deleteProduct(id) {
      axios.get("/sanctum/csrf-cookie").then((response) => {
        console.log(response.config.headers.Authorization),
          axios.delete(`api/products/${id}`).then((response) => {
            let i = this.products.map((data) => data.id).indexOf(id);
            this.products.splice(i, 1);
          });
      });
    },
  },
};
</script>
