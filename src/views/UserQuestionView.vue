<template>
  <div class="container mt-5">
    <transition name="fade" appear>
      <div
        class="d-flex justify-content-center align-items-center text-capitalize text-white fs-2"
        style="height: 70vh"
        v-if="!isQuestionExist"
      >
        this question doesn't exist.
      </div>
      <div
        class="card bg-dark text-white pt-2"
        style="min-height: 300px"
        v-if="isQuestionExist"
      >
        <div
          class="hstack gap-2"
          @click="showProfile(question.user_id)"
          role="button"
          style="width: fit-content"
        >
          <img
            :src="'/server/uploads/' + question.image"
            width="50px"
            height="50px"
            class="ms-3 rounded-circle"
          />
          <div>{{ question.username }}</div>
        </div>
        <div class="card-body">
          <div class="card-text" style="white-space: pre-wrap">
            {{ question.question }}
          </div>
          <div class="form-floating mt-4 text-dark">
            <textarea
              class="form-control bg-dark shadow-none text-white"
              placeholder="Leave an answer here"
              id="floatingTextarea"
              style="resize: none"
              v-model="answer"
              @keydown.enter.exact.prevent="send"
              @keydown.enter.shift.exact.prevent="answer += '\n'"
            ></textarea>
            <label for="floatingTextarea" class="text-capitalize text-white"
              >Enter your answer here</label
            >
          </div>
        </div>
        <hr />
        <UsersAnswers
          :allAnswers="allAnswers"
          :user_id="user_id"
          :username="username"
          :isAnswersExist="isAnswersExist"
        />
      </div>
    </transition>
  </div>
</template>

<script>
import UsersAnswers from "@/components/UsersAnswers.vue";
import viewProfile from "@/js/viewProfile";
export default {
  name: "UserQuestionView",
  components: {
    UsersAnswers,
  },

  data: function () {
    return {
      question: [],
      allAnswers: [],
      answer: "",
      interval: null,
      isQuestionExist: false,
      isAnswersExist: true,
    };
  },

  computed: {
    username() {
      return this.$store.state.username;
    },
    question_id() {
      return this.$route.params.id;
    },

    user_id() {
      return this.$store.state.user_id;
    },
  },

  methods: {
    showProfile(user_id) {
      viewProfile(user_id);
    },
    send() {
      if (this.answer.trim()) {
        fetch("/server/api/Answers/createAnswer.php", {
          method: "POST",
          body: JSON.stringify({
            question_id: this.question_id,
            answer: this.answer.trim(),
            user_id: this.user_id,
          }),
          headers: {
            "Content-type": "application/json",
          },
        });
        this.answer = "";
      }
    },

    async getAnswers() {
      const response = await fetch("/server/api/Answers/readAnswers.php", {
        method: "POST",
        body: JSON.stringify({
          question_id: this.question_id,
        }),
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
        },
      });
      const answers = await response.json();
      if (answers.error) {
        this.isAnswersExist = false;
      }
      if (answers.length) {
        this.isAnswersExist = true;
        this.allAnswers = answers;
      }
    },
    async getQuestion() {
      const response = await fetch(
        "/server/api/Questions/readOneQuestion.php",
        {
          method: "POST",
          body: JSON.stringify({
            question_id: this.question_id,
          }),
          headers: {
            Accept: "application/json",
            "Content-type": "application/json",
          },
        }
      );
      const question = await response.json();
      if (question.error) {
        this.isQuestionExist = false;
      }
      if (question.question) {
        this.isQuestionExist = true;
        this.question = question;
        this.interval = setInterval(() => {
          this.getAnswers();
        }, 500);
      }
    },
  },

  mounted() {
    this.getQuestion();
  },
  destroyed() {
    clearInterval(this.interval);
  },
};
</script>

<style scoped lang="scss">
textarea {
  caret-color: #fff !important;
}
.form-control:focus {
  border-color: #fff !important;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
  -webkit-transform: translateY(100px);
  -moz-transform: translateY(100px);
  -o-transform: translateY(100px);
  transform: translateY(100px);
}

.fade-enter-active,
.fade-leave-active {
  -webkit-transition: all 0.7s ease;
  -moz-transition: all 0.7s ease;
  -o-transition: all 0.7s ease;
  transition: all 0.7s ease;
}
@media screen and (min-width: 1400px) {
  .container {
    max-width: 1140px;
  }
}
</style>
