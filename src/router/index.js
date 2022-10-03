import Vue from "vue";
import VueRouter from "vue-router";
import store from "@/store/index.js";
import LogInView from "@/views/LogInView.vue";
import ForumView from "@/views/ForumView.vue";
import RegisterView from "@/views/RegisterView.vue";
import MyQuestionsView from "@/views/MyQuestionsView.vue";
import UserQuestionView from "@/views/UserQuestionView.vue";
import AskQuestionView from "@/views/AskQuestionView.vue";
import ProfileView from "@/views/ProfileView.vue";
import UserProfileView from "@/views/UserProfileView.vue";

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
    component: UserQuestionView,
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
    path: "/myQuestions",
    name: "myQuestions",
    component: MyQuestionsView,
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
    path: "/about",
    name: "about",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/AboutView.vue"),
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
