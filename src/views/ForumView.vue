<template>
  <div class="container mt-5">
    <div
      class="d-flex justify-content-center align-items-center text-white fs-2 text-capitalize"
      style="height: 70vh"
      v-if="!isQuestionsExist"
    >
      there are no questions yet.
    </div>
    <transition-group
      appear
      name="fade"
      tag="div"
      class="row row-cols-1 row-cols-md-3"
      style="gap: 10px 0"
      v-if="isQuestionsExist"
    >
      <div
        class="col"
        v-for="question in questions"
        :key="question.question_id"
      >
        <div class="card question bg-dark rounded-4 shadow-lg">
          <div
            style="width: fit-content"
            class="hstack"
            role="button"
            @click="showProfile(question.user_id)"
          >
            <img
              :src="'/server/uploads/' + question.image"
              width="80px"
              height="80px"
              class="m-2 rounded-circle"
            />
            <div class="text-white">{{ question.username }}</div>
          </div>
          <div class="card-body">
            <p
              class="card-text text-white"
              style="white-space: pre-wrap; height: 50px"
              v-snip="{ lines: 2 }"
            >
              {{ question.question }}
            </p>
            <router-link
              :to="{ path: `question/${question.question_id}` }"
              class="view btn bg-gradient col-5 text-white"
            >
              View
            </router-link>
            <button
              @click="deleteQuestion(question.question_id)"
              class="btn bg-danger bg-gradient text-white mx-2 col-5"
              v-if="question.username === username"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
import destroy from "@/js/destroyQuestion";
import viewProfile from "@/js/viewProfile";
export default {
  name: "ForumView",
  data: function () {
    return {
      questions: {},
      isQuestionsExist: false,
      username: this.$store.state.username,
    };
  },
  methods: {
    showProfile(user_id) {
      viewProfile(user_id);
    },
    async deleteQuestion(question_id) {
      await destroy(question_id);
      this.readAllQuestions();
    },
    async readAllQuestions() {
      const response = await fetch(
        "/server/api/Questions/readAllQuestions.php",
        {
          method: "GET",
          headers: {
            Accept: "application/json",
          },
        }
      );
      const questions = await response.json();
      if (questions.error) {
        this.isQuestionsExist = false;
        return;
      }
      if (questions.length) {
        this.isQuestionsExist = true;
        this.questions = questions;
      }
    },
  },
  mounted() {
    this.readAllQuestions();
  },
};
</script>

<style lang="scss" scoped>
.view {
  background-color: #394f8a !important;
  &:focus {
    background-color: #394f8a !important;
  }
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
  -webkit-transform: translateY(30px);
  -moz-transform: translateY(30px);
  -o-transform: translateY(30px);
  transform: translateY(30px);
}

.fade-enter-active,
.fade-leave-active,
.fade-move {
  -webkit-transition: all 0.5s ease;
  -moz-transition: all 0.5s ease;
  -o-transition: all 0.5s ease;
  transition: all 0.5s ease;
}

.fade-leave-active {
  position: absolute;
}
</style>
