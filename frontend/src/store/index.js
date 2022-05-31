import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    perPage: 5
  },
  mutations: {
    setPerPage(state, value) {
      state.perPage = value
    }
  }
})
