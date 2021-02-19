<template>
 <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">編輯產品</div>

          <div class="card-body">component 名稱：EditProduct.vue </div>
          <div class="card-body">
        <center>
        <form @submit.prevent="updateProduct">
                    <div>
            <label>品名</label>
            <input type="text"  v-model="product.name" />
            </div>
          <div >

            <label>價格</label>
            <input type="text"  v-model="product.detail" />
                                  </div >

          <button type="submit" class="btn btn-primary">編輯</button>
        </form>
        </center>
          </div>
        </div>
      </div>
    </div>
  </div>

  
</template>

<div>
    <h3 class="text-center">編輯產品</h3>
    <div class="row">
      <div class="col-md-6">
        <form @submit.prevent="updateProduct">
          <div class="form-group">
            <label>編號</label>
           {{ product.id }}
          </div>
          <div class="form-group">
            <label>品名</label>
            <input type="text" class="form-control" v-model="product.name" />
          </div>
          <div class="form-group">
            <label>價格</label>
            <input type="text" class="form-control" v-model="product.detail" />
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>

<script>
export default {
  data() {
    return {
      product: {},
          };
  },
  created() {
    axios.get("/sanctum/csrf-cookie").then((response) => {
      axios.get(`api/products/${this.$route.query.id}`).then((res) => {
        console.log(res);
        this.product = res.data;
      });
    });
  },
  methods: {
    updateProduct() {
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .patch(`api/products/${this.$route.query.id}`, this.product)
          .then((response) => {

             if (response.request.status === 200) {
            this.$router.push({ name: "allproduct" });
             }else{
                             alert(response.data.message);

             }
          })
           .catch((error) => {
            this.errors = error.response.data.errors;
          });
      });
    },
  },
};
</script>
