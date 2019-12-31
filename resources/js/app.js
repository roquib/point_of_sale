/**
 * Root of vue js
 */

require("./bootstrap");
// vue instance initial
window.Vue = require("vue");

import PurchaseInvoice from "./components/Admin/Purchases/invoice";
//  import necessary components
import TestModal from "./components/partials/modal.vue";

// import all route = routes.js file
import Routes from "./routes";

// main.js
import "./filters.js";
import { Form, HasError, AlertError } from "vform";
import VueProgressBar from "vue-progressbar";

import swal from "sweetalert2";
// autocomplete input field
import VueSelect from "vue-cool-select";
// vue router
import VueRouter from "vue-router";

// vue js ui element
import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";

// vuejs modal
// import VModal from "vue-js-modal";
Vue.component("modal", TestModal);
Vue.component("purchase-invoice", PurchaseInvoice);

window.Form = Form;
window.Fire = new Vue();
window.swal = swal;

// initial vue router
Vue.use(VueRouter);

Vue.use(ElementUI);

// set router
const router = new VueRouter({
    routes: Routes,
    mode: "history",
    history: true
});

// set dynamic page title
router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    next();
});

// vuejs input field error dispaly
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

// vuejs pagination
Vue.component("pagination", require("laravel-vue-pagination"));

Vue.use(VueProgressBar, {
    color: "rgb(143, 255, 199)",
    failedColor: "red",
    height: "5px"
});

Vue.use(VueSelect, {
    theme: "bootstrap" // or 'material-design'
});

const toast = swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000
});
window.toast = toast;

// main vue
const app = new Vue({
    el: "#vue-user",
    router
});
