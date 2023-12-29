<template>
  <header
    class="p-2 border-bottom border-opacity-25 border-secondary"
    id="header"
  >
    <div
      class="d-flex justify-content-between align-items-md-center flex-column flex-md-row"
      style="gap: 40px 5px"
    >
      <div class="w-auto mx-auto mx-md-0">
        <div class="dropdown-center text-white">
          <div
            class="d-flex gap-2 align-items-center justify-content-center justify-content-md-start mb-2 mb-md-0 dropdown-toggle"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <img
              :src="'/server/uploads/' + this.$store.getters.getUserImage"
              width="50px"
              height="50px"
              class="rounded-circle"
            />
            <div class="text-white fw-bold">
              {{ this.$store.getters.getUserName }}
            </div>
          </div>
          <ul class="dropdown-menu bg-dark shadow text-white">
            <li
              class="dropdown-item text-white"
              @click="redirect"
              role="button"
            >
              Profile
            </li>
            <li class="dropdown-item text-white" @click="logOut" role="button">
              LogOut
            </li>
          </ul>
        </div>
      </div>
      <div class="position-relative col col-lg-5">
        <div class="position-relative">
          <input
            type="search"
            class="form-control text-bg-dark shadow-none"
            style="padding-left: 40px"
            placeholder="Search The Users"
            v-model="pattern"
          />
          <i
            class="fa fa-search text-white fs-4 position-absolute top-50 translate-middle"
            style="left: 20px"
          ></i>
        </div>
        <ul
          class="list-group position-absolute w-100 overflow-auto"
          style="z-index: 50; height: 400px"
          v-if="isUsersExist"
        >
          <li
            class="list-group-item text-bg-dark bg-gradient ps-1"
            v-for="user in users"
            :key="user.user_id"
            @click="showProfile(user.user_id)"
            role="button"
          >
            <div class="hstack" style="gap: 0 5px">
              <img
                :src="'/server/uploads/' + user.image"
                alt="userimage"
                class="rounded-circle"
                width="50px"
                height="50px"
              />
              <span>{{ user.username }}</span>
            </div>
          </li>
        </ul>
      </div>
      <nav class="nav fs-6 justify-content-between justify-content-md-center">
        <div class="position-relative">
          <router-link
            to="/messages"
            class="nav-link text-white fw-bold text-capitalize"
            >messages</router-link
          >
          <span
            class="position-absolute rounded-circle text-bg-danger top-0 start-0 d-flex justify-content-center align-items-center"
            style="width: 1.3em; height: 1.3em"
            >{{ numberOfUnReadMessages }}</span
          >
        </div>
        <AskQuestionView />
        <router-link
          to="/forum"
          class="nav-link text-white fw-bold text-capitalize"
          >allquestions</router-link
        >
      </nav>
    </div>
  </header>
</template>

<script>
import AskQuestionView from "@/views/AskQuestionView.vue";
import viewProfile from "@/js/viewProfile";
export default {
  name: "NavBar",
  components: {
    AskQuestionView,
  },
  data() {
    return {
      pattern: "",
      users: [],
      isUsersExist: false,
      numberOfUnReadMessages: 0,
    };
  },
  methods: {
    showProfile(user_id) {
      viewProfile(user_id);
    },
    async search() {
      if (!this.pattern.trim()) {
        this.isUsersExist = false;
        return;
      }

      const response = await fetch("/server/api/Users/searchUsers.php", {
        method: "POST",
        body: JSON.stringify({ pattern: this.pattern.trim() }),
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      });

      const users = await response.json();

      if (users.length === 0) {
        this.isUsersExist = false;
        return;
      }

      this.isUsersExist = true;

      this.users = users;
    },
    redirect() {
      if (this.$router.currentRoute.name === "profile") {
        this.$router.go();
        return;
      }
      this.$router.push({ name: "profile" });
    },
    logOut() {
      this.$store.commit("setAuthentication", false);
      this.$store.commit("setUserName", null);
      this.$store.commit("setUserImage", null);
      this.$store.commit("setUserId", null);
      this.$router.replace({ name: "LogIn" });
    },
    getNumberOfUnReadMessages() {
      fetch("/server/api/Messages/getNumberOfUnReadMessages.php", {
        method: "POST",
        body: JSON.stringify({ receiver: this.$store.getters.getUserId }),
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      })
        .then((response) => response.json())
        .then(
          (data) => (this.numberOfUnReadMessages = data.numberOfUnReadMessages)
        );
    },
  },
  watch: {
    pattern() {
      this.search();
    },
  },
  mounted() {
    this.getNumberOfUnReadMessages();
    setInterval(() => {
      this.getNumberOfUnReadMessages();
    }, 1000);
  },
};
</script>

<style lang="scss">
.list-group-item:hover {
  background-color: #394f8a !important;
}
.nav-link {
  &.router-link-exact-active {
    color: #ff6a3d !important;
  }
}
.dropdown-item:hover {
  background-color: #394f8a !important;
}
::-webkit-scrollbar {
  width: 10px;
}
::-webkit-scrollbar-thumb {
  background-color: #394f8a !important;
}

::-webkit-scrollbar-track {
  background-color: gray !important;
}
</style>
