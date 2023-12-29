<template>
  <div
    class="container d-flex justify-content-center align-items-center vh-100"
  >
    <transition name="fade" appear>
      <form
        @submit.prevent
        class="rounded overflow-hidden p-sm-5 position-relative p-3 shadow text-white bg-gradient col col-md-9 col-lg-6 d-flex flex-column justify-content-center h-75"
      >
        <div
          class="position-absolute start-50 translate-middle fs-1 fw-bold text-capitalize"
          style="top: 20%; color: #ff6a3d; letter-spacing: 3px"
        >
          register
        </div>
        <div
          class="position-absolute w-75 translate-middle start-50"
          style="top: 50px"
        >
          <div class="progress" style="height: 3px">
            <div
              ref="progress_bar"
              class="progress-bar orange-stage"
              style="width: 0%"
              role="progressbar"
              aria-label="Progress"
              aria-valuenow="50"
              aria-valuemin="0"
              aria-valuemax="100"
            ></div>
          </div>
          <button
            type="button"
            class="position-absolute top-0 start-0 translate-middle btn btn-sm text-white rounded-pill orange-stage"
            style="width: 2rem; height: 2rem"
          >
            1
          </button>
          <button
            type="button"
            class="position-absolute top-0 start-50 translate-middle btn btn-sm rounded-pill text-white"
            :class="{
              'orange-stage': activeStep === 2 || activeStep === 3,
              'btn-secondary': activeStep === 1,
            }"
            style="width: 2rem; height: 2rem"
          >
            2
          </button>
          <button
            type="button"
            class="position-absolute top-0 start-100 translate-middle btn btn-sm rounded-pill text-white"
            :class="{
              'orange-stage': activeStep === 3,
              'btn-secondary': activeStep === 1 || activeStep === 2,
            }"
            style="width: 2rem; height: 2rem"
          >
            3
          </button>
        </div>
        <div
          class="alert text-bg-success bg-gradient col-12 col-xl-9 mx-auto text-capitalize text-center"
          v-if="isTimerEnabled"
        >
          registered successfully. redirect you in {{ timer }} seconds.
        </div>
        <div
          v-if="accountError"
          class="alert text-bg-danger bg-gradient col-12 col-xl-9 mx-auto text-center text-capitalize"
        >
          {{ accountError }}
        </div>
        <transition-group name="slide">
          <div class="form-group" v-if="activeStep === 1" :key="1">
            <div class="col-12 col-xl-9 mx-auto">
              <label class="form-label fs-5" for="username">Username</label>
              <input
                @change="validateUserName"
                ref="username"
                type="text"
                class="form-control text-bg-dark border-0 shadow-none"
                id="username"
                v-model="username"
                pattern="[A-Z]([A-Za-z0-9\s]+)"
                minlength="3"
              />
              <div v-if="userNameErrors">
                <span
                  class="text-danger d-block text-capitalize"
                  v-for="error in userNameErrors"
                  :key="error"
                  >{{ error }}</span
                >
              </div>
            </div>
            <div class="mt-3 col-12 col-xl-9 mx-auto">
              <label class="form-label fs-5" for="email">Email</label>
              <input
                @change="validateEmail"
                ref="email"
                type="email"
                class="form-control text-bg-dark border-0 shadow-none"
                id="email"
                v-model="email"
              />
              <span
                class="text-danger d-block text-capitalize"
                v-if="emailError"
                >{{ emailError }}</span
              >
            </div>
            <button
              :disabled="isContinueDisabled1"
              class="d-block btn text-capitalize text-white bg-gradient col-12 col-xl-9 mx-auto mt-4 fs-4"
              style="color: #394f8a"
              @click.prevent="goToStep(2)"
            >
              continue
            </button>
          </div>
          <div class="form-group" v-if="activeStep === 2" :key="2">
            <div class="col-12 col-xl-9 mx-auto">
              <label class="form-label fs-5" for="password">Password</label>
              <input
                type="password"
                class="form-control text-bg-dark border-0 shadow-none"
                id="password"
                v-model="password"
                @change="validatePassword"
              />
              <span
                class="text-danger d-block text-capitalize"
                v-if="passwordError"
                >{{ passwordError }}</span
              >
            </div>
            <div class="mt-3 col-12 col-xl-9 mx-auto">
              <label class="form-label fs-5" for="email"
                >Confirm Password</label
              >
              <input
                type="password"
                class="form-control text-bg-dark border-0 shadow-none"
                id="confirm_password"
                v-model="confirm_password"
                @change="validatePassword"
              />
              <span
                class="text-danger d-block text-capitalize"
                v-if="passwordError"
                >{{ passwordError }}</span
              >
            </div>
            <div
              class="row gap-2 justify-content-center col-12 col-xl-9 mx-auto mt-4"
            >
              <button
                class="btn text-capitalize btn-secondary col-5 bg-gradient"
                @click.prevent="goToStep(1)"
              >
                back
              </button>
              <button
                :disabled="isContinueDisabled2"
                class="btn text-capitalize text-white bg-gradient col-5"
                @click.prevent="goToStep(3)"
              >
                continue
              </button>
            </div>
          </div>
          <div class="form-group" v-if="activeStep === 3" :key="3">
            <div class="col-12 col-xl-9 mx-auto">
              <label class="form-label fs-5 text-capitalize" for="date"
                >date of birth</label
              >
              <input
                ref="date"
                type="date"
                class="form-control text-bg-dark border-0 shadow-none"
                id="date"
                v-model="date"
                max="2008-12-31"
                min="1950-01-01"
                @change="validateDate"
              />
              <span
                class="text-danger d-block text-capitalize"
                v-if="dateError"
                >{{ dateError }}</span
              >
            </div>
            <div class="mt-3 col-12 col-xl-9 mx-auto">
              <label class="form-label fs-5" for="about"
                >About (Optional)</label
              >
              <textarea
                style="resize: none; height: 100px"
                class="form-control text-bg-dark border-0 shadow-none"
                id="about"
                v-model="about"
              >
              </textarea>
            </div>
            <div
              class="row gap-2 justify-content-center col-12 col-xl-9 mx-auto mt-4"
            >
              <button
                :disabled="isTimerEnabled"
                class="btn text-capitalize btn-secondary col-5 bg-gradient"
                @click.prevent="goToStep(2)"
              >
                back
              </button>
              <button
                :disabled="isContinueDisabled3"
                class="btn text-capitalize text-white bg-gradient col-5"
                @click.prevent="goToStep()"
              >
                continue
              </button>
            </div>
          </div>
        </transition-group>
        <span
          class="text-white text-capitalize position-absolute bottom-0 start-50 translate-middle fs-5 col-12 text-center"
          >already have an account
          <router-link
            to="/login"
            class="text-decoration-none fw-bold"
            style="color: #ff6a3d"
            >LogIn</router-link
          ></span
        >
      </form>
    </transition>
  </div>
</template>
<script>
export default {
  name: "LogInView",

  data: function () {
    return {
      username: "",
      email: "",
      password: "",
      confirm_password: "",
      date: "",
      about: "",
      activeStep: 1,
      userNameErrors: [],
      accountError: "",
      emailError: "",
      passwordError: "",
      dateError: "",
      timer: 3,
      isTimerEnabled: false,
      timerInterval: null,
    };
  },

  computed: {
    isContinueDisabled1() {
      return (
        this.username.length === 0 ||
        this.email.length === 0 ||
        this.$refs.username.checkValidity() === false ||
        this.$refs.email.checkValidity() === false
      );
    },
    isContinueDisabled2() {
      return (
        this.password.length < 5 ||
        this.confirm_password.length < 5 ||
        this.password !== this.confirm_password
      );
    },
    isContinueDisabled3() {
      return (
        this.date.length === 0 ||
        this.$refs.date.checkValidity() === false ||
        this.isTimerEnabled === true
      );
    },
  },
  methods: {
    async goToStep(step = null) {
      this.errors = [];

      if (step === 1) {
        this.activeStep = step;
        this.$refs.progress_bar.style.width = "0%";
        return;
      }

      if (step === 2) {
        //get back from step 3
        if (this.activeStep === 3) {
          this.activeStep = step;
          this.$refs.progress_bar.style.width = "50%";
          return;
        }

        //move to step 2 from step 1
        const isUserExist = await this.isUserExist();

        if (isUserExist === false) {
          this.activeStep = step;
          this.$refs.progress_bar.style.width = "50%";
        }

        return;
      }

      if (step === 3) {
        this.activeStep = step;

        this.$refs.progress_bar.style.width = "100%";

        return;
      }
      this.registerNewUser();
    },
    async isUserExist() {
      this.accountError = "";
      this.emailError = "";
      this.userNameErrors = [];
      const response = await fetch("/server/api/Users/register.php", {
        method: "POST",
        body: JSON.stringify({
          username: this.username.trim(),
          email: this.email.trim(),
        }),
        headers: {
          "Content-type": "application/json",
          Accept: "application/json",
        },
      });

      const data = await response.json();
      if (data.accountError) {
        this.accountError = data.accountError;
        return true;
      }
      if (data.emailError && data.userNameError) {
        this.emailError = data.emailError;
        this.userNameErrors.push(data.userNameError);
        return true;
      }
      if (data.emailError) {
        this.emailError = data.emailError;
        return true;
      }
      if (data.userNameError) {
        this.userNameErrors.push(data.userNameError);
        return true;
      }

      if (data.success) return false;
    },
    validateUserName() {
      if (!this.$refs.username.checkValidity()) {
        this.userNameErrors.push("- Username must starts with capital letter.");
        this.userNameErrors.push("- Username must be three or more in length.");
        this.userNameErrors.push("- Special characters are not allowed.");
        this.accountError = "";
        return false;
      }
      this.userNameErrors = [];
    },
    validateEmail() {
      if (!this.$refs.email.checkValidity()) {
        this.emailError = "wrong email format";
        this.accountError = "";

        return;
      }
      this.emailError = "";
    },
    validatePassword() {
      if (this.password !== this.confirm_password) {
        this.passwordError = "passwords don't match each other.";
        this.accountError = "";
        return false;
      }
      this.passwordError = "";
    },
    validateDate() {
      if (!this.$refs.date.checkValidity()) {
        this.dateError = "date must be between 01-01-1950 and 31-12-2005.";
        this.accountError = "";

        return;
      }
      this.dateError = "";
    },
    async registerNewUser() {
      const response = await fetch("/server/api/Users/register.php", {
        method: "POST",
        body: JSON.stringify({
          username: this.username.trim(),
          email: this.email.trim(),
          password: this.password,
          date: this.date,
          about: this.about,
        }),
        headers: {
          "Content-type": "application/json",
          Accept: "application/json",
        },
      });

      const data = await response.json();

      if (data.success) {
        this.isTimerEnabled = true;
      }
    },
  },

  watch: {
    isTimerEnabled(currentValue) {
      if (currentValue) {
        this.timerInterval = setInterval(() => {
          this.timer--;
        }, 1000);
      }
    },
    timer(timer) {
      if (timer === 0) {
        clearInterval(this.timerInterval);
        this.$router.replace({ name: "LogIn" });
      }
    },
  },
};
</script>

<style>
.orange-stage {
  background-color: #ff6a3d !important;
}
.slide-enter,
.slide-leave-to {
  opacity: 0;
  -webkit-transform: translateY(50px);
  -moz-transform: translateY(50px);
  -o-transform: translateY(50px);
  transform: translateY(50px);
}
.slide-enter-active,
.slide-leave-active,
.slide-move {
  -webkit-transition: all 0.7s ease;
  -moz-transition: all 0.7s ease;
  -o-transition: all 0.7s ease;
  transition: all 0.7s ease;
}
.slide-leave-active {
  position: absolute;
}

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
