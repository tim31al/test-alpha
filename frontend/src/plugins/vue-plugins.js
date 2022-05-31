import Vue from "vue";
import axios from "@/plugins/axios";

const plugins = {
  install(Vue) {
    Vue.mixin({
      computed: {
        $axios: () => axios,
      },
    });
  },
};

Vue.use(plugins);
