import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);

export default new Vuex.Store({
  plugins: [
    createPersistedState({
      //save the state in localStorage instead of sessionStorage
      storage: window.localStorage,
    }),
  ],
  state: {
    authenticated: false,
    username: null,
    userImage: null,
    user_id: null,
  },
  getters: {
    // getUsername: (state) => {
    //   return state.username;
    // },
  },
  mutations: {
    setAuthentication(state, status) {
      state.authenticated = status;
    },

    setUserName(state, username) {
      state.username = username;
    },

    setUserImage(state, userImage) {
      state.userImage = userImage;
    },
    setUserId(state, user_id) {
      state.user_id = user_id;
    },
  },
  actions: {},
  modules: {},
});
