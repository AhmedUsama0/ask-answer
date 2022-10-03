<template>
  <div class="container">
    <form @submit.prevent="send">
      <div class="alert col-12 col-md-6 col-lg-4 m-auto mb-3"></div>
      <div class="form-group mb-3">
        <label for="username" class="col-12 col-md-6 col-lg-4 mb-2 text-white"
          >Username</label
        >
        <input
          v-model="username"
          type="text"
          name="username"
          id="username"
          class="col-12 col-md-6 col-lg-4"
          data-parsely-pattern="/^[a-zA-Z]/"
        />
      </div>
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

      <div class="form-group mt-3">
        <label
          for="confirm-password"
          class="col-12 col-md-6 col-lg-4 mb-2 text-white"
          >Confirm Password</label
        >
        <input
          v-model="confirm_password"
          type="password"
          name="confirm-password"
          id="confirm-password"
          class="col-12 col-md-6 col-lg-4"
        />
      </div>
      <button type="submit" class="col-5 col-md-2 mt-4">Register</button>
      <p class="mt-5 text-white">
        Already have an account <router-link to="/login">LogIn</router-link>
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
      username: "",
      email: "",
      password: "",
      confirm_password: "",
    };
  },

  methods: {
    send: function () {
      $(".alert").text("");
      $(".alert").removeClass("alert-danger");
      $(".alert").removeClass("alert-success");

      if (
        this.username !== "" &&
        this.email !== "" &&
        this.password !== "" &&
        this.confirm_password !== ""
      ) {
        if (this.password === this.confirm_password) {
          $.ajax({
            method: "POST",
            url: "http://localhost/ask-answer/src/php/register.php",
            data: {
              username: this.username,
              email: this.email,
              password: this.password,
            },

            success: (data) => {
              try {
                data = JSON.parse(data);
              } catch (err) {
                console.log("Error is " + err.message);
              }
              if (data === "") {
                $(".alert").text("Registered Successfully");
                $(".alert").addClass("alert-success");
              } else {
                $(".alert").addClass("alert-danger");
                $(".alert").text(data["error"]);
              }
            },

            error: () => {
              console.log(Error("data is not sent"));
            },
          });
        } else {
          $(".alert").text("Password not matched");
          $(".alert").addClass("alert-danger");
        }
      } else {
        $(".alert").text("Please, provide your data");
        $(".alert").addClass("alert-danger");
      }
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

  #empty-error,
  #password-error {
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
