<template>
  <nav class="col-12">
    <div class="welcome" @click="showList">
      <img
        :src="require(`@/php/uploads/${userImage}`)"
        alt=""
        width="45px"
        height="45px"
      />
      <span>{{ username }}</span>
    </div>
    <ul class="profile col-3 col-md-1" id="profile">
      <li @click="redirect">profile</li>
      <li @click="logOut">logOut</li>
    </ul>
    <ul class="nav-links">
      <li>
        <router-link to="/myQuestions">MyQuestions</router-link>
      </li>
      <li>
        <router-link to="/askQuestion">AskQuestion</router-link>
      </li>
      <li>
        <router-link to="/forum">All Questions</router-link>
      </li>
    </ul>
  </nav>
</template>

<script>
import $ from "jquery";
export default {
  name: "NavBar",

  data() {
    return {
      username: this.$store.state.username,
      userImage: "male.png",
    };
  },

  mounted() {
    if (this.$store.state.userImage !== null) {
      this.userImage = this.$store.state.userImage;
    }
  },
  methods: {
    showList() {
      $("#profile").slideToggle("fast");
    },
    redirect() {
      this.$router.push({ name: "profile" });
    },
    logOut() {
      this.$store.commit("setAuthentication", false);
      this.$store.commit("setUserName", null);
      this.$store.commit("setUserImage", null);
      this.$store.commit("setUserId", null);
      this.$router.replace({ name: "LogIn" });
    },
  },
};
</script>

<style scoped lang="scss">
nav {
  padding: 13px 5px;
  background-color: #fff;
  display: flex;
  align-items: center;
  position: relative;
  .profile {
    position: absolute;
    z-index: 3;
    top: 65px;
    left: 0px;
    background-color: #474b4f;
    list-style-type: none;
    padding: 0px;
    display: none;

    li {
      text-align: left;
      padding: 10px;
      color: #fff;
      cursor: pointer;
      &:first-child {
        margin-bottom: 10px;
      }
      &:hover {
        background-color: #6b6e70;
      }
    }
  }
  .nav-links {
    text-align: center;
    margin: 0 auto;
    list-style-type: none;
    li {
      display: inline-block;
      a {
        padding: 5px;
        font-size: clamp(10px, 16px, 20px);
      }
    }
  }
  .welcome {
    float: left;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    img {
      border-radius: 50%;
    }
    span {
      float: left;
    }
  }
}

@media screen and (max-width: 400px) {
  nav {
    ul {
      li {
        a {
          font-size: 11px;
          padding: 2px;
        }
      }
    }
  }
}
</style>
