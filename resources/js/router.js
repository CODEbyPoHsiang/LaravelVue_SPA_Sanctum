import Vue from "vue";
import VueRouter from "vue-router";
import axios from "axios";

const originalPush = VueRouter.prototype.push;

VueRouter.prototype.push = function push(location) {
  return originalPush.call(this, location).catch((err) => err);
};

Vue.use(VueRouter);

//切換頁面
import login from "./components/login.vue";
import about from "./components/about.vue";
import home from "./components/home.vue";
import register from "./components/register.vue";


//組件切換
import AllProduct from "./components/products/AllProduct.vue";
import EditProduct from "./components/products/EditProduct.vue";
import CreateProduct from "./components/products/CreateProduct.vue";
import QRcode from "./components/2fa_login/QRcode.vue";
import otp2fa from "./components/2fa_login/otp2fa.vue";

import app from "./App.vue";

const router = new VueRouter({
  // mode: "history",
  // base: process.env.MIX_BASE_URL,
  routes: [
    {
      path: "/login",
      name: "login",
      component: login,
      meta: { guestOnly: true },
    },

    {
      path: "/home",
      name: "home",
      component: home,
    },
    {
      path: "/register",
      name: "register",
      component: register,
    },
    {
      path: "/about",
      name: "about",
      component: about,
      meta: { authOnly: true },
    },
    {
      path: "/allproduct",
      name: "allproduct",
      component: AllProduct,
      meta: { authOnly: true },
    },
    {
      path: "/editproduct",
      name: "EditProduct",
      component: EditProduct,
      meta: { authOnly: true },
    },
    {
      path: "/createproduct",
      name: "createproduct",
      component: CreateProduct,
      meta: { authOnly: true },
    },
    {
      path: "/createproduct",
      name: "createproduct",
      component: CreateProduct,
      meta: { authOnly: true },
    },
    {
      path: "/qrcode",
      name: "qrcode",
      component: QRcode,
      meta: { authOnly: true },
    },
    {
      path: "/otp2fa",
      name: "otp2fa",
      component: otp2fa,
      meta: { authOnly: true },
    },
  ],
});

function isLoggedIn() {
  return localStorage.getItem("first_login");
}
function QRcodeScan() {
  return localStorage.getItem("qrcode_scan");
}
function OTP2fa() {
  return localStorage.getItem("otp2fa");
}

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.authOnly)) {
    if (!isLoggedIn()) {
      console.log(isLoggedIn());
      next("/login");
    } else {
      next();
    }
  } else if (to.matched.some((record) => record.meta.guestOnly)) {
    if (QRcodeScan()) {
      next("/qrcode");
    } else {
      next();
    }
    if (OTP2fa()) {
      next("/otp2fa");
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;