<template>
   <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">建立產品</div>
          <div class="card-body">component 名稱： CreateProduct.vue</div>
          <div class="card-body">
        <center>
        <form @submit.prevent="addProduct">
          <div >
            <label>品名</label>
            <input type="text"  v-model="product.name" />
          </div>
          <div >
            <label>價格</label>
            <input type="text" v-model="product.detail" />
          </div>
          <button type="submit" class="btn btn-primary">新增</button>
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
      product: {},
    };
  },
  methods: {
    addProduct() {
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("api/products", this.product)
          .then((response) => this.$router.push({ name: "allproduct" }))
          .catch((err) => console.log(err))
          .finally(() => (this.loading = false));
      });
    },
  },
};
</script>
