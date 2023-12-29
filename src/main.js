import Vue from "vue";
import VueSnip from "vue-snip";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import "@/js/viewProfile";

Vue.config.productionTip = false;
Vue.use(VueSnip);
new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
