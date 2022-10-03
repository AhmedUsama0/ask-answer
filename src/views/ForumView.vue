<template>
  <div class="forum">
    <NavBar />
    <div class="container">
      <div
        v-for="question in questions"
        :key="question.question_id"
        class="card col-12 col-md-10 col-lg-6 mt-3 mx-auto p-3 question"
      >
        <div @click="viewQuestion(question['question_id'])">
          {{ question.question }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import NavBar from "@/components/NavBar.vue";
export default {
  name: "ForumView",
  components: {
    NavBar,
  },
  data: function () {
    return {
      questions: {},
    };
  },

  methods: {
    viewQuestion: function (question_id) {
      this.$router.push({ path: `question/${question_id}` });
    },
  },
  mounted: function () {
    $.ajax({
      method: "GET",
      url: "http://localhost/ask-answer/src/php/allQuestions.php",
      success: (data) => {
        this.questions = JSON.parse(data);
      },

      error: () => {
        console.log(Error("error has occured"));
      },
    });
  },
};
</script>

<style scoped lang="scss">
.forum {
  .container {
    .question {
      cursor: pointer;
      background-color: #474b4f;
      color: #fff;
      text-align: left;
      padding: 10px 5px;
      border-radius: 10px;
      transition: 0.3s ease-in-out;
      &:hover {
        background-color: #6b6e70;
      }
    }
  }
}
</style>
