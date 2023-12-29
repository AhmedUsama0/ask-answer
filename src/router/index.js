import Vue from "vue";
import VueRouter from "vue-router";
import store from "@/store/index.js";
import LogInView from "@/views/LogInView.vue";
import ForumView from "@/views/ForumView.vue";
import RegisterView from "@/views/RegisterView.vue";
import AskQuestionView from "@/views/AskQuestionView.vue";
import ProfileView from "@/views/ProfileView.vue";
import UserProfileView from "@/views/UserProfileView.vue";
import MessagesView from "@/views/MessagesView.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    redirect: {
      name: "LogIn",
    },
  },
  {
    path: "/login",
    name: "LogIn",
    component: LogInView,
    beforeEnter: (to, from, next) => {
      if (store.state.authenticated === true) {
        next("/forum");
      } else {
        next();
      }
    },
  },

  {
    path: "/question/:id",
    name: "question",
    component: () => import("@/views/UserQuestionView.vue"),
    beforeEnter: (to, from, next) => {
      if (store.state.authenticated === false) {
        next("/login");
      } else {
        next();
      }
    },
  },

  {
    path: "/forum",
    name: "forum",
    component: ForumView,
    beforeEnter: (to, from, next) => {
      if (store.state.authenticated === false) {
        next("/login");
      } else {
        next();
      }
    },
  },

  {
    path: "/profile",
    name: "profile",
    component: ProfileView,
    beforeEnter: (to, from, next) => {
      if (store.state.authenticated === false) {
        next("/login");
      } else {
        next();
      }
    },
  },

  {
    //we could use props and set it to true if we dont want user_id to appear in the url
    // and we pass the value of this prop in push method and we recived it in antoher component using props as well
    path: "/profile/:user_id",
    name: "userProfile",
    component: UserProfileView,
    beforeEnter: (to, from, next) => {
      if (store.state.authenticated === false) {
        next("/login");
      } else {
        next();
      }
    },
  },

  {
    path: "/askQuestion",
    name: "askQuestion",
    component: AskQuestionView,
    beforeEnter: (to, from, next) => {
      if (store.state.authenticated === false) {
        next("/login");
      } else {
        next();
      }
    },
  },

  {
    path: "/register",
    name: "register",
    component: RegisterView,
    beforeEnter: (to, from, next) => {
      if (store.state.authenticated === true) {
        next("/forum");
      } else {
        next();
      }
    },
  },

  {
    path: "/messages",
    name: "messages",
    component: MessagesView,
    beforeEnter: (to, from, next) => {
      if (store.state.authenticated === false) next("/login");
      else next();
    },
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
