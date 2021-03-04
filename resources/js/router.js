import Vue from "vue";
import VueRouter from "vue-router";
import axios from "axios";

const originalPush = VueRouter.prototype.push;

VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err);
};

Vue.use(VueRouter);

//切換頁面
import Login from "./components/Login.vue";
import UserAbout from "./components/UserAbout.vue";
import Home from "./components/Home.vue";
import Register from "./components/Register.vue";

//組件切換
import AllProduct from "./components/products/AllProduct.vue";
import EditProduct from "./components/products/EditProduct.vue";
import CreateProduct from "./components/products/CreateProduct.vue";
// import QRcode from "./components/2fa_login/QRcode.vue";
// import otp2fa from "./components/2fa_login/otp2fa.vue";
import TaipeiUbikeMap from "./components/ubike/TaipeiUbikeMap.vue";
import TaichungUbikeMap from "./components/ubike/TaichungUbikeMap.vue";
import TaipeiUbikeMapNow from "./components/ubike/TaipeiUbikeMapNow.vue";
import TaichungUbikeMapNow from "./components/ubike/TaichungUbikeMapNow.vue";

import app from "./App.vue";

const router = new VueRouter({
    // mode: "history",
    // base: process.env.MIX_BASE_URL,
    routes: [
        {
            path: "/login",
            name: "login",
            component: Login,
            meta: { guestOnly: true }
        },

        {
            path: "/home",
            name: "home",
            component: Home,
            meta: { guestOnly: true }
        },
        {
            path: "/register",
            name: "register",
            component: Register,
            meta: { guestOnly: true }
        },
        {
            path: "/userabout",
            name: "userabout",
            component: UserAbout,
            meta: { authOnly: true }
        },
        {
            path: "/allproduct",
            name: "allproduct",
            component: AllProduct,
            meta: { authOnly: true }
        },
        {
            path: "/editproduct",
            name: "editproduct",
            component: EditProduct,
            meta: { authOnly: true }
        },
        {
            path: "/createproduct",
            name: "createproduct",
            component: CreateProduct,
            meta: { authOnly: true }
        },
        // {
        //     path: "/qrcode",
        //     name: "qrcode",
        //     component: QRcode,
        //     meta: { authOnly: true }
        // },
        // {
        //     path: "/otp2fa",
        //     name: "otp2fa",
        //     component: otp2fa,
        //     meta: { authOnly: true }
        // },
        {
            path: "/taipeimap",
            name: "taipeimap",
            component: TaipeiUbikeMap,
            meta: { authOnly: true }
        },
        {
            path: "/taichungmap",
            name: "taichungmap",
            component: TaichungUbikeMap,
            meta: { authOnly: true }
        },
        {
            path: "/taipeimapnow",
            name: "taipeimapnow",
            component: TaipeiUbikeMapNow,
            meta: { authOnly: true }
        },
        {
            path: "/taichungmapnow",
            name: "taichungmapnow",
            component: TaichungUbikeMapNow,
            meta: { authOnly: true }
        },
    ]
});

function isLoggedIn() {
    return sessionStorage.getItem("first_login");
}
function QRcodeScan() {
    return sessionStorage.getItem("qrcode_scan");
}
function OTP2fa() {
    return sessionStorage.getItem("otp2fa");
}
function Checklogin2FA() {
    return sessionStorage.getItem("auth");
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.authOnly)) {
        if (!isLoggedIn()) {
            next("/login");
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.guestOnly)) {
        if (QRcodeScan()) {
            next("/qrcode");
        } else if(OTP2fa()){
            next("/otp2fa");
        }else {
            next();
        }
    } else {
        next();
    }
});


export default router;
