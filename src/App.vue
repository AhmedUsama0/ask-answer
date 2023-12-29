<template>
  <div id="app">
    <NavBar v-if="this.$store.state.authenticated" />
    <!-- <transition name="slide" mode="out-in"> -->
    <router-view />
    <!-- </transition> -->
  </div>
</template>

<style lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Cairo:wght@400;500&family=Lato&family=Montserrat&family=Roboto:ital,wght@0,400;0,500;0,700;1,100;1,300&display=swap");
#app {
  font-family: "Cairo";
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
body {
  background-color: #1a2238 !important;
  overflow-x: hidden;
}
// .slide-enter {
//   transform: translateX(-2000px);
// }
// .slide-enter-to {
//   transform: translateX(0);
// }
// .slide-enter-active {
//   transition: all 0.4s ease;
// }

// .slide-leave {
//   transform: translateX(0);
// }
// .slide-leave-to {
//   transform: translateX(2000px);
// }

// .slide-leave-active {
//   transition: all 0.4s ease;
// }
</style>
<script>
import NavBar from "@/components/NavBar.vue";
export default {
  components: { NavBar },
};
</script>
