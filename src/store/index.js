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
    userEmail: null,
    date: null,
    about: null,
    password: null,
  },
  getters: {
    getUserName: (state) => state.username,
    getUserImage: (state) => state.userImage,
    getUserEmail: (state) => state.userEmail,
    getUserDate: (state) => state.date,
    getUserAbout: (state) => state.about,
    getUserPassword: (state) => state.password,
    getUserId: (state) => state.user_id,
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
    setUserEmail(state, userEmail) {
      state.userEmail = userEmail;
    },
    setUserDate(state, date) {
      state.date = date;
    },
    setUserAbout(state, about) {
      state.about = about;
    },
    setUserPassword(state, password) {
      state.password = password;
    },
  },
  actions: {},
  modules: {},
});
