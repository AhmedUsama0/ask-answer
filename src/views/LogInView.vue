<template>
  <div
    class="container d-flex justify-content-center align-items-center vh-100"
  >
    <transition name="fade" appear>
      <form
        @submit.prevent
        class="rounded p-sm-5 position-relative p-3 shadow text-white bg-gradient col col-md-9 col-lg-6 d-flex flex-column justify-content-center h-75"
      >
        <div
          class="position-absolute start-50 translate-middle fs-1 fw-bold text-capitalize"
          style="top: 20%; color: #ff6a3d; letter-spacing: 3px"
        >
          login
        </div>
        <div
          class="alert text-bg-danger bg-gradient text-center text-capitalize col-12 col-xl-9 mx-auto"
          v-if="this.accountError"
        >
          {{ accountError }}
        </div>
        <div class="form-group">
          <div class="col-12 col-xl-9 mx-auto">
            <label class="form-label fs-5" for="email">Email</label>
            <input
              ref="email"
              type="email"
              class="form-control text-bg-dark border-0 shadow-none"
              id="email"
              v-model="email"
              @change="validateEmail"
            />
            <span
              v-if="this.emailError"
              class="text-danger d-block text-capitalize"
            >
              {{ emailError }}
            </span>
          </div>
          <div class="mt-3 col-12 col-xl-9 mx-auto">
            <label class="form-label fs-5" for="password">Password</label>
            <input
              type="password"
              class="form-control text-bg-dark border-0 shadow-none"
              id="password"
              ref="password"
              v-model="password"
              minlength="5"
              @change="validatePassword"
            />
            <span
              v-if="this.passwordError"
              class="text-danger d-block text-capitalize"
            >
              {{ passwordError }}
            </span>
          </div>
          <button
            :disabled="isLoginDisabled"
            class="d-block btn text-capitalize text-white bg-gradient col-12 col-xl-9 mx-auto mt-4 fs-4"
            style="color: #394f8a"
            @click.prevent="login"
          >
            login
          </button>
        </div>
        <span
          class="text-white text-capitalize position-absolute bottom-0 start-50 translate-middle fs-5 col-12 text-center"
          >don't have an account
          <router-link
            to="/register"
            class="text-decoration-none fw-bold text-capitalize"
            style="color: #ff6a3d"
            >register now</router-link
          ></span
        >
      </form>
    </transition>
  </div>
  <!-- <div class="container">
    <form @submit.prevent="send" autocomplete="off">
      <div class="alert col-12 col-md-6 col-lg-4 m-auto mb-3"></div>
      <div class="form-group mb-5">
        <label for="email" class="col-12 col-md-6 col-lg-4 mb-2 text-white"
          >Email</label
        >
        <input
          v-model="email"
          type="email"
          class="col-12 col-md-6 col-lg-4"
          required
        />
      </div>
      <div class="form-group mb-5">
        <label for="password" class="col-12 col-md-6 col-lg-4 mb-2 text-white"
          >Password</label
        >
        <input
          v-model="password"
          type="password"
          minlength="5"
          class="col-12 col-md-6 col-lg-4"
          required
        />
      </div>
      <button type="submit" class="mt-4 col-5 col-md-2">LogIn</button>
      <p class="mt-5 text-white">
        Don't have an account
        <router-link to="/register">Register Now</router-link>
      </p>
    </form>
  </div> -->
</template>
<script>
export default {
  name: "LogInView",

  data: function () {
    return {
      email: "",
      password: "",
      emailError: "",
      accountError: "",
      passwordError: "",
    };
  },
  computed: {
    isLoginDisabled() {
      return (
        this.email.length === 0 ||
        this.password.length < 5 ||
        this.$refs.email.checkValidity() === false
      );
    },
  },
  methods: {
    async checkForLoginUser() {
      this.accountError = "";
      this.passwordError = "";
      const response = await fetch("/server/api/Users/login.php", {
        method: "POST",
        body: JSON.stringify({
          email: this.email,
          password: this.password,
        }),
        headers: {
          "Content-type": "application/json",
          Accept: "application/json",
        },
      });

      const data = await response.json();

      if (data.accountError) {
        this.accountError = data.accountError;
        return false;
      }
      if (data.passwordError) {
        this.passwordError = data.passwordError;
        return false;
      }

      return data;
    },
    validateEmail() {
      if (!this.$refs.email.checkValidity()) {
        this.emailError = "wrong email format.";
        return;
      }
      this.emailError = "";
    },
    validatePassword() {
      if (this.password.length < 5) {
        this.passwordError = "password must be at least five in length.";
        return;
      }
      this.passwordError = "";
    },
    async login() {
      const loginUser = await this.checkForLoginUser();

      if (loginUser === false) return;

      this.$store.commit("setUserName", loginUser.username);
      this.$store.commit("setUserPassword", loginUser.password);
      this.$store.commit("setUserId", loginUser.user_id);
      this.$store.commit("setUserImage", loginUser.userImage);
      this.$store.commit("setAuthentication", true);
      this.$store.commit("setUserEmail", loginUser.email);
      this.$store.commit("setUserDate", loginUser.date);
      this.$store.commit("setUserAbout", loginUser.about);
      this.$router.replace({ name: "forum" });
    },
  },
};
</script>

<style>
.fade-enter {
  opacity: 0;
  transform: translateY(100px);
}
.fade-enter-active {
  transition: all 0.5s ease;
}
::-ms-reveal {
  filter: invert(100%);
}
</style>
