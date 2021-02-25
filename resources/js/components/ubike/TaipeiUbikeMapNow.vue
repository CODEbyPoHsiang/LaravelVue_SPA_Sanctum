<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <center>
          <div>
            <input type="text" v-model="keywords" placeholder="請輸入站名" />
          
          <button type="submit" class="btn btn-primary" @click="search">
            搜尋
          </button>
          <button type="submit" class="btn btn-primary" onClick="history.go()">
            重新整理
          </button>
          </div>
        </center>
        <br />
        <!-- 開始 -->
        <div class="card">
          <div class="card-body">
            <div class="card-header">即時站點資訊</div>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>區域</th>
                  <th>站名</th>
                  <th>可借車輛</th>
                  <th>可停空位</th>
                </tr>
              </thead>
              <tbody>
                <!-- <span v-if="errorskeywords"> -->
                <!-- </span> -->
                  <tr v-if="ubikes.length > 0">
                  <tr v-for="ubike in ubikes" :key="ubike.id">
                    <td>{{ ubike.sarea }}</td>
                    <td>{{ ubike.sna }}</td>
                    <td>{{ ubike.sbi }}</td>
                    <td>{{ ubike.bemp }}</td>
                  </tr>
                </tr>
              </tbody>
            </table>
            <div class="card-footer"></div>

            <!-- <span v-if="errorskeywords">  -->
            <center>
              <!-- 查無資料顯示 -->
              <tr>
                {{
                  errorskeywords
                }}
              </tr>
            </center>
          </div>
        </div>
        <!-- 底線-->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      keywords: null,
      ubikes: [],
      errorskeywords: "",
    };
  },
  watch: {
    keywords(after, before) {
      this.search();
    },
  },
  created() {
    // const url = 'http://10.249.33.229/~po-hsiang/LaravelVue_SPA_Sanctum/public/api/taipeiubikemap';
    axios.get("api/taipeiubikemap").then((response) => {
      console.log(response.data.retVal);
      this.ubikes = Object.keys(response.data.retVal).map(
        (key) => response.data.retVal[key]
      );
    });
  },
  methods: {
    deleteProduct(id) {
      let yes = confirm(`你確定刪除編號【${id}】的產品嗎？`);
      if (yes) {
        axios.get("/sanctum/csrf-cookie").then((response) => {
          console.log(response.config.headers.Authorization),
            axios.delete(`api/products/${id}`).then((response) => {
              let i = this.products.map((data) => data.id).indexOf(id);
              this.products.splice(i, 1);
            });
        });
      }
    },
    search() {
      axios
        .post("/api/taipeiubikemap_search", {
          keywords: this.keywords,
        })

        .then((response) => {
          // console.log(response);
          if (response.request.status === 200) {
            this.ubikes = response.data;
            this.errorskeywords = "";
          } else {
            this.ubikes = [];
            this.errorskeywords = response.data;
          }
        });
    },
  },
};
</script>
