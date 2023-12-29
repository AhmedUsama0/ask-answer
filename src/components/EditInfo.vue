<template>
  <div>
    <button
      type="button"
      class="btn text-bg-dark bg-gradient text-capitalize w-100 mt-5"
      data-bs-toggle="modal"
      data-bs-target="#exampleModal"
    >
      edit info
    </button>
    <div
      class="modal fade"
      id="exampleModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
          <div class="modal-header">
            <h1 class="modal-title text-capitalize">edit personal info</h1>
          </div>
          <div class="modal-body">
            <form class="row g-3" @submit.prevent>
              <div class="col-md-6">
                <label for="email" class="form-label text-capitalize"
                  >email</label
                >
                <input
                  ref="email"
                  type="email"
                  v-model="user.email"
                  class="form-control bg-dark text-white shadow-none"
                  id="email"
                />
                <span
                  class="text-danger d-block text-capitalize"
                  v-if="emailError"
                >
                  {{ emailError }}
                </span>
              </div>
              <div class="col-md-6">
                <label for="password" class="form-label text-capitalize"
                  >password</label
                >
                <input
                  type="password"
                  id="password"
                  v-model="user.password"
                  class="form-control bg-dark text-white shadow-none"
                />
                <span
                  class="text-danger d-block text-capitalize"
                  v-if="passwordError"
                >
                  {{ passwordError }}
                </span>
              </div>
              <div class="col-md-6">
                <label for="username" class="form-label text-capitalize"
                  >username</label
                >
                <input
                  ref="username"
                  type="text"
                  id="username"
                  v-model="user.username"
                  class="form-control bg-dark text-white shadow-none"
                  pattern="[A-Z]([A-Za-z0-9\s]+)"
                  minlength="3"
                />
                <div v-if="userNameErrors">
                  <span
                    v-for="error in userNameErrors"
                    :key="error"
                    class="text-danger d-block text-capitalize"
                  >
                    {{ error }}
                  </span>
                </div>
              </div>
              <div class="col-md-6">
                <label for="date" class="form-label text-capitalize"
                  >date of birth</label
                >
                <input
                  ref="date"
                  type="date"
                  id="date"
                  v-model="user.date"
                  class="form-control bg-dark text-white shadow-none"
                  max="2008-12-31"
                  min="1950-01-01"
                />
                <span
                  class="text-capitalize d-block text-danger"
                  v-if="dateError"
                >
                  {{ dateError }}
                </span>
              </div>
              <div class="col">
                <label for="about" class="form-label text-capitalize"
                  >about</label
                >
                <textarea
                  style="height: 200px; resize: none; caret-color: #fff"
                  type="text"
                  id="about"
                  v-model="user.about"
                  class="form-control bg-dark text-white shadow-none"
                />
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              style="background-color: gray"
              type="button"
              class="btn bg-gradient text-white"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button
              ref="update"
              :disabled="isUpdateDisabled"
              style="background-color: #394f8a"
              type="button"
              class="btn bg-gradient text-white"
              @click.prevent="update"
            >
              Update
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "EditInfo",
  data() {
    return {
      user: {
        user_id: this.$store.getters.getUserId,
        email: this.$store.getters.getUserEmail,
        password: this.$store.getters.getUserPassword,
        username: this.$store.getters.getUserName,
        about: this.$store.getters.getUserAbout,
        date: this.$store.getters.getUserDate,
      },
      emailError: "",
      userNameErrors: [],
      passwordError: "",
      dateError: "",
    };
  },
  computed: {
    isUpdateDisabled() {
      return (
        (this.user.email === this.$store.getters.getUserEmail &&
          this.user.password === this.$store.getters.getUserPassword &&
          this.user.username === this.$store.getters.getUserName &&
          this.user.about === this.$store.getters.getUserAbout &&
          this.user.date === this.$store.getters.getUserDate) ||
        this.user.username.length === 0 ||
        this.user.password.length === 0 ||
        this.user.email.length === 0 ||
        this.user.date.length === 0
      );
    },
  },
  methods: {
    async update() {
      if (this.validateUserInfo() === true) return;
      const response = await fetch("/server/api/Users/updateUserInfo.php", {
        method: "POST",
        body: JSON.stringify(this.user),
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      });
      const data = await response.json();
      if (data.error) {
        return;
      }
      this.$store.commit("setUserName", data.username);
      this.$store.commit("setUserPassword", data.password);
      this.$store.commit("setUserEmail", data.email);
      this.$store.commit("setUserDate", data.date);
      this.$store.commit("setUserAbout", data.about);
      this.$router.go();
    },
    validateUserInfo() {
      var isErrorExist = false;
      this.emailError = "";
      this.userNameErrors = [];
      this.passwordError = "";

      if (!this.$refs.email.checkValidity()) {
        isErrorExist = true;
        this.emailError = "wrong email format.";
      }

      if (!this.$refs.username.checkValidity()) {
        isErrorExist = true;
        this.userNameErrors.push("- username must starts with capital letter.");
        this.userNameErrors.push("- username must be three or more in length.");
        this.userNameErrors.push("- special characters are not allowed.");
      }
      if (this.user.password.length < 5) {
        isErrorExist = true;
        this.passwordError = "password must be at least five in length.";
      }

      if (!this.$refs.date.checkValidity()) {
        isErrorExist = true;
        this.dateError = "date must be between 01-01-1950 and 31-12-2005.";
      }
      return isErrorExist;
    },
  },
};
</script>

<style></style>
