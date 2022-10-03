<template>
  <div class="container">
    <form @submit.prevent="send">
      <div class="alert col-12 col-md-6 col-lg-4 m-auto mb-3"></div>
      <div class="form-group mb-3">
        <label for="email" class="col-12 col-md-6 col-lg-4 mb-2 text-white"
          >Email</label
        >
        <input
          v-model="email"
          type="email"
          name="email"
          id="email"
          class="col-12 col-md-6 col-lg-4"
        />
      </div>
      <div class="form-group">
        <label for="password" class="col-12 col-md-6 col-lg-4 mb-2 text-white"
          >Password</label
        >
        <input
          v-model="password"
          type="password"
          name="password"
          id="password"
          class="col-12 col-md-6 col-lg-4"
        />
      </div>
      <p id="error-data" class="mt-4"></p>
      <button type="submit" class="mt-4 col-5 col-md-2">LogIn</button>
      <p class="mt-5 text-white">
        Don't have an account
        <router-link to="/register">Register Now</router-link>
      </p>
    </form>
  </div>
</template>
<script>
import $ from "jquery";
export default {
  name: "LogInView",

  data: function () {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    send: function () {
      $.ajax({
        method: "POST",
        url: "http://localhost/ask-answer/src/php/login.php",
        data: {
          email: this.email,
          password: this.password,
        },
        dataType: "JSON",

        success: (data) => {
          $(".alert").text("");
          $(".alert").removeClass("alert-danger");
          if (this.email !== "" && this.password !== "") {
            if (
              data["email"] === this.email &&
              data["password"] === this.password
            ) {
              this.$store.commit("setUserName", data["username"]);
              this.$store.commit("setUserId", data["user_id"]);
              this.$store.commit("setUserImage", data["userImage"]);
              this.$store.commit("setAuthentication", true);
              this.$router.replace({ name: "forum" });
            } else {
              $(".alert").text("Email or Password is not correct");
              $(".alert").addClass("alert-danger");
            }
          } else {
            $(".alert").text("Enter your Email and Password");
            $(".alert").addClass("alert-danger");
          }
        },

        error: () => {
          console.log(Error("Couldnt send request"));
        },
      });
    },
  },
};
</script>

<style scoped lang="scss">
.container {
  margin-top: 25vh;
  label {
    display: block;
    text-align: left;
    margin: 0 auto;
  }
  input {
    height: 35px;
    outline: none;
    display: block;
    margin: 0 auto;
    padding: 5px;
  }

  #empty-data,
  #error-data {
    color: #61892f;
    font-weight: bold;
  }
  button {
    background-color: #86c232;
    border: none;
    outline: none;
    color: #fff;
    padding: 7px 11px;
    border-radius: 5px;
  }
}
</style>
