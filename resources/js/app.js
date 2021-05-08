require('./bootstrap');

import VueRouter from "vue-router";
import Vuex from 'vuex'
import router from "./routes";
import index from "./index";
import moment from "moment";
import FatalError from "./shared/components/FatalError";
import Success from "./shared/components/Success";
import StarRating from "./shared/components/StarRating";
import ValidationErrors from "./shared/components/ValidationErrors";
import storeDefinition from "./store";

window.Vue = require('vue');
Vue.use(VueRouter);
Vue.use(Vuex);


Vue.filter("fromNow", value => moment(value).fromNow());
// グローバルコンポーネントを登録
Vue.component("star-rating", StarRating);
Vue.component("fatal-error", FatalError);
Vue.component("success", Success);
Vue.component("v-errors", ValidationErrors);

const store = new Vuex.Store(storeDefinition);

const app = new Vue({
  el: '#app',
  router,
  store,
  components: {
    "index" : index,
  },
  beforeCreate() {
    this.$store.dispatch("loadStoredState");
  }
});

