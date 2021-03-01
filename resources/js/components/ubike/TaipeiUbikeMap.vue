/* eslint-disable no-console */
<template>
  <div id="app">
    <div class="container-fluid">
        <div class="row no-gutters">
          <!-- 選擇地區 -->
          <div  id="sidebar" class="toolbox col-sm-3 p-2 bg-dark">
            <!-- 測試 -->
            <nav class="navbar navbar-default bg-dark">
              <span>站點地圖</span>
              <div>
                <!-- 即時站點資料 -->
                <router-link
                  to="/taipeimapnow"
                  type="button"
                  class="btn btn-sm btn-warning navbar-right"
                  >即時站點資料</router-link
                >
              </div>
            </nav>

            <div class="form-group d-flex">
              <label for="city" class="col-form-label mr-2 text-right"
                >縣市</label
              >
              <div class="flex-fill">
                <!-- v-model依照選取改變顯示選項 -->
                <select id="city" class="form-control" v-model="select.city">
                  <!-- 製作下拉選單 -->
                  <!-- 不能直接寫option.value="{{ cityName.districts.name }}" -->
                  <option
                    v-bind:value="city.name"
                    v-bind:key="city.name"
                    v-for="city in cityName"
                  >
                    <!-- v-for做其他縣市 -->
                    {{ city.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group d-flex">
              <label for="dist" class="col-form-label mr-2 text-right"
                >區域</label
              >
              <div class="flex-fill">
                <select id="dist" class="form-control" v-model="select.dist">
                  <!-- 製作下拉選單 -->
                  <!-- v-bind:簡寫為: -->
                  <option
                    :value="dist.name"
                    :key="dist.name"
                    v-for="dist in cityName.find(
                      (city) => city.name === select.city
                    ).districts"
                  >
                    {{ dist.name }}
                  </option>
                </select>
              </div>
            </div>
            <!-- 搜尋功能 -->
            <p>站名搜尋</p>
            <div>
              <input
                type="text"
                v-model="keywords"
                placeholder="請輸入關鍵字搜尋"
                class="form-control-lm"
              />

              <label>
                <button
                  type="submit"
                  class="btn btn-primary col-form-label text-right"
                  @click="search"
                >
                  搜尋
                </button>
              </label>

              <!-- 搜尋結果 -->
              <div v-if="search_click === 'true'" id="list">
                <div class="card">
                  <div class="card-body">
                    <div class="card-header">搜尋結果</div>
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>區域</th>
                          <th>站名</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="search_data in search_datas"
                          :key="search_data.sno"
                        >
                          <td>{{ search_data.sarea }}</td>
                          <td>{{ search_data.sna }}</td>
                          <td>
                            <button
                              class="btn btn-info"
                              @click="mapInfo(search_data.sno)"
                            >
                              詳細
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="card-footer"></div>
                  </div>
                </div>
              </div>
            </div>
            <br />
          </div>

          <!-- 顯示地圖和 UBike 站點 -->
          <div class="col-sm-9">
            <div id="map"></div>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
import L from "leaflet"; // OSM不像是google map可以直接標記點位，需要其他套件
import cityName from "./assets/cityName.json";
export default {
  name: "App",
  data: () => ({
    cityName,
    select: {
      city: "臺北市",
      dist: "中正區",
    },
    ubikes: [],
    search_datas: [],
    //search_click 是點擊後改面顯示搜尋結果的效果
    search_click: "",
    keywords: null,
    OSMap: [],
  }),
  created: {
    addToList(sno) {
      axios.get(`api/taipeiubikemap_full_match/${sno}`).then((response) => {
        console.log(response.data);
      });
    },
  },
  computed: {
    // 重新計算ubikes，避免呈現過多markerLayer
    youbikes() {
      return this.ubikes.filter((bike) => bike.sarea === this.select.dist);
    },
  },
  watch: {
    // 監聽youbikes是否重新計算過，重新計算時執行addMarkers()方法
    youbikes() {
      this.updateMap();
    },
  },
  methods: {
    updateMap() {
      // remove markers
      this.OSMap.eachLayer((layer) => {
        // 移除先前查詢的標記，避免重複顯示
        if (layer instanceof L.Marker) {
          this.OSMap.removeLayer(layer);
        }
      });
      // add markers
      this.youbikes.forEach((bike) => {
        L.marker([bike.lat, bike.lng])
          .bindPopup(
            `<p><strong style="font-size: 20px;">${bike.sna}&nbsp
              </strong></p>
            <strong style="font-size: 16px; color: #d45345;">可租借車輛剩餘：${bike.sbi} 台</strong><br>
            可停空位剩餘: ${bike.bemp}

            <br>
            <br>
            <small>

            最後更新時間: ${bike.mday}

            </small>`
          )
          .addTo(this.OSMap); // 新增標記到地圖
      });
      // move to new center
      this.cityName[0].districts.find((dist) => {
        if (dist.name === this.select.dist) {
          // this.OSMap.panTo(new L.LatLng(dist.latitude, dist.longitude)); // 直接平移
          this.OSMap.flyTo(new L.LatLng(dist.latitude, dist.longitude, 20)); // 飛越效果，數字為過程中縮放級數
        }
        return dist.name === this.select.dist;
      });
    },
    search() {
      axios
        .post("/api/taipeiubikemap_search", {
          keywords: this.keywords,
        })
        .then((response) => {
          this.search_datas = response.data;
          console.log(response.data);
          // const sarea = response.data[0].sarea;
          //  this.response.data[0].find((dist) => {
          if (response.request.status === 202) {
            this.search_click = "false";
            alert(response.data);
          }
          if (response.request.status === 200) {
            this.search_click = "true";
            this.search_datas = response.data;
            console.log(this.searh_click);
            //原本從這裡飛越
            // if (this.keywords === response.data[0].sna) {
            //   // this.OSMap.panTo(new L.LatLng(dist.latitude, dist.longitude)); // 直接平移

            //   this.OSMap.flyTo(
            //     new L.LatLng(response.data[0].lat, response.data[0].lng, 14)
            //   ); // 飛越效果，數字為過程中縮放級數
            //   L.marker([response.data[0].lat, response.data[0].lng])
            //     .bindPopup(
            //       `<p><strong style="font-size: 20px;">${response.data[0].sna}</strong></p>
            // <strong style="font-size: 16px; color: #d45345;">可租借車輛剩餘：${response.data[0].sbi} 台</strong><br>
            // 可停空位剩餘: ${response.data[0].bemp}<br>
            // <small>最後更新時間: ${response.data[0].mday}</small>`
            //     )
            //     .addTo(this.OSMap); // 新增標記到地圖
            // }
          }
          // return dist.name === sarea;
          // });
        });
    },

    mapInfo(sno) {
      //因為清單是列表，若點了要在點下一個要先清除
      // remove markers
      this.OSMap.eachLayer((layer) => {
        // 移除先前查詢的標記，避免重複顯示
        if (layer instanceof L.Marker) {
          this.OSMap.removeLayer(layer);
        }
      });
      axios.get(`api/taipeiubikemap_full_match/${sno}`).then((response) => {
        console.log(response.data);
        this.OSMap.flyTo(
          new L.LatLng(response.data[0].lat, response.data[0].lng, 14)
        ); // 飛越效果，數字為過程中縮放級數
        L.marker([response.data[0].lat, response.data[0].lng])
          .addTo(this.OSMap)
          .bindPopup(
            `<p><strong style="font-size: 20px;">${response.data[0].sna}&nbsp
            </strong></p>
            <strong style="font-size: 16px; color: #d45345;">可租借車輛剩餘：${response.data[0].sbi} 台</strong><br>
            可停空位剩餘: ${response.data[0].bemp}

            <br>
            <br>
            <small>
            最後更新時間: ${response.data[0].mday}
            </small>`
          )
          .openPopup(); // 新增標記到地圖
      });
    },
  },
  created() {
    // const url = 'http://10.249.33.229/~po-hsiang/LaravelVue_SPA_Sanctum/public/api/taipeiubikemap';
    axios.get("api/taipeiubikemap").then((response) => {
      console.log(response.data);
      this.ubikes = Object.keys(response.data.retVal).map(
        (key) => response.data.retVal[key]
      );
    });
  },
  mounted() {
    // initalize
    this.OSMap = L.map("map", {
      center: [25.041956, 121.508791],
      zoom: 18,
    });
    // add tile to map
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution:
        'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 18,
    }).addTo(this.OSMap);
    // this.setView();
  },
};
</script>

<style lang="scss" scoped>
// @import 'bootstrap/scss/bootstrap';
#map {
  height: 100vh;
  position: relative;
}
#sidebar {
    height: 100vh;

  // border-right: 1px solid black;
  overflow-y: auto;
  // padding: 0;
  // position: fixed;
  // top: 0;
  // left: 0;
  // bottom: 0;
}
</style>