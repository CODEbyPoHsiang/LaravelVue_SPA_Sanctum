require("./bootstrap");
 
window.Vue = require("vue");
 
import Vue from "vue";
import router from "./router";
import App from './App.vue';


//游標focus套件
import autofocus from 'vue-autofocus-directive';
Vue.directive('autofocus', autofocus);

// import 'bootstrap/dist/css/bootstrap.min.css'




 
const app = new Vue({
    el: "#app",
    router: router,
    render: h => h(App),
});