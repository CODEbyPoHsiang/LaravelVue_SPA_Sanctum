<template>
    <div>
        <h3 class="text-center">建立產品</h3>
        <div class="row">
            <div class="col-md-6">
                <form @submit.prevent="addProduct">
                    <div class="form-group">
                        <label>品名</label>
                        <input type="text" class="form-control" v-model="product.name">
                    </div>
                    <div class="form-group">
                        <label>價格</label>
                        <input type="text" class="form-control" v-model="product.detail">
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</template>
 
<script>
    export default {
        data() {
            return {
                product: {}
            }
        },
        methods: {
            addProduct() {
                axios
                    .post('api/products', this.product)
                    .then(response => (
                        this.$router.push({ name: 'allproduct' })
                    ))
                    .catch(err => console.log(err))
                    .finally(() => this.loading = false)
            }
        }
    }
</script>