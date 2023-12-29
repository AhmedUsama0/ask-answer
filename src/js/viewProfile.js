import store from "@/store";
import router from "@/router";
export default function viewProfile(user_id) {
  if (
    router.currentRoute.name === "profile" &&
    user_id === store.getters.getUserId
  ) {
    router.go();
    return;
  }

  if (user_id === store.getters.getUserId) {
    router.push({ name: "profile" });
    return;
  }

  router.push({ path: `/profile/${user_id}` });
}
