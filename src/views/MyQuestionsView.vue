<template>
  <div class="myQuestions">
    <NavBar />
    <div class="container">
      <div
        v-for="question in questions"
        :key="question['question_id']"
        class="card myquestion col-12 col-md-10 col-lg-6 mt-3 mx-auto p-3"
      >
        <div
          class="text-white col-11"
          @click="openQuestion(question['question_id'])"
        >
          {{ question["question"] }}
        </div>
        <button class="delete" @click="destroy(question['question_id'])">
          X
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import NavBar from "@/components/NavBar.vue";
export default {
  name: "MyQuestionsView",
  components: {
    NavBar,
  },
  data() {
    return {
      questions: {},
    };
  },

  methods: {
    openQuestion(question_id) {
      this.$router.push({ path: `/question/${question_id}` });
    },
    destroy(question_id) {
      $.ajax({
        method: "POST",
        url: "http://localhost/ask-answer/src/php/deleteQuestion.php",
        data: { question_id: question_id },
        success: () => {
          console.log("question is deleted");
        },
        error: () => {
          console.log("couldnt delete the data");
        },
      });
    },

    getMyQuestions() {
      $.ajax({
        method: "POST",
        url: "http://localhost/ask-answer/src/php/myQuestions.php",
        data: {
          user_id: this.$store.state.user_id,
        },
        dataType: "JSON",
        success: (data) => {
          try {
            this.questions = data;
          } catch (err) {
            console.log("Error: ", err.message);
          }
        },

        error: () => {
          console.log(Error("Couldn't send the request"));
        },
      });
    },
  },

  created() {
    this.getMyQuestions();
  },

  mounted() {
    setInterval(() => {
      this.getMyQuestions();
    }, 500);
  },
};
</script>

<style scoped lang="scss">
.myQuestions {
  .container {
    .myquestion {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      background-color: #474b4f;
      color: #fff;
      text-align: left;
      padding: 10px 5px;
      border-radius: 10px;
      transition: 0.3s ease-in-out;
      &:hover {
        background-color: #6b6e70;
      }

      .delete {
        border: none;
        outline: none;
        background-color: unset;
        color: #fff;
        font-size: 20px;
        padding: 2px 5px;

        &:hover::after {
          content: "Delete Question";
          position: absolute;
          right: 7%;
          border-radius: 10px;
          padding: 5px;
          font-size: 13px;
          color: #fff;
          background-color: #222629;
        }
      }
      div {
        cursor: pointer;
      }
    }
  }
}
</style>
