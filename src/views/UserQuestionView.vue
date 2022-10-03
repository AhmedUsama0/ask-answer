<template>
  <div class="question-details">
    <NavBar />
    <div class="container">
      <div class="questions col-12 col-lg-9 mx-auto mt-3">
        <div v-if="this.question !== null" class="col-12 mx-auto question">
          <img
            v-if="question['userImage'] !== null"
            :src="require(`@/php/uploads/${question['userImage']}`)"
            alt="userImage"
            height="50px"
            width="50px"
            class="userImage"
          />
          <span
            @click="viewProfile(question['user_id'])"
            style="cursor: pointer"
            >{{ question["username"] }}</span
          >
          <br />
          <br />
          {{ question["question"] }} <br />
        </div>

        <hr />
        <div class="textarea">
          <textarea
            placeholder="Enter your answer"
            name="question_id"
            class="col-12 card p-2"
            v-on:keyup.enter="send"
            v-model="answer"
          />
        </div>
        <div class="answers mt-5">
          <UsersAnswers
            :allAnswers="allAnswers"
            :user_id="user_id"
            :username="username"
            :viewProfile="viewProfile"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import NavBar from "@/components/NavBar.vue";
import UsersAnswers from "@/components/UsersAnswers.vue";
export default {
  name: "UserQuestionView",
  components: {
    NavBar,
    UsersAnswers,
  },

  data: function () {
    return {
      question: null,
      allAnswers: null,
      answer: "",
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
    viewProfile(user_id) {
      if (user_id === this.$store.state.user_id) {
        this.$router.push({ name: "profile" });
      } else {
        this.$router.push({ path: `/profile/${user_id}` });
      }
    },
    send() {
      if (this.answer !== "\n") {
        $.ajax({
          method: "POST",
          url: "http://localhost/ask-answer/src/php/createAnswer.php",
          data: {
            question_id: this.question_id,
            answer: this.answer,
            user_id: this.user_id,
          },
        });
        this.answer = "";
      }
    },

    getAnswers() {
      $.ajax({
        method: "GET",
        cache: true,
        url: "http://localhost/ask-answer/src/php/getAnswers.php",
        data: { question_id: this.question_id },
        //data type JSON convert the json we get from the server to javascript
        //object so we no longer need to parsing the response from the server
        //dataType specifies the expect type of data we recieved from the server
        dataType: "JSON",
        success: (data) => {
          //we no longer need try-catch block but we use it to be safe and to know
          //if there is any error
          try {
            this.allAnswers = data;
          } catch (err) {
            console.log("Error is " + err.message);
          }
        },
      });
    },
  },

  mounted() {
    setInterval(() => {
      this.getAnswers();
    }, 500);
  },
  created: function () {
    $.ajax({
      method: "GET",
      url: "http://localhost/ask-answer/src/php/getOneQuestion.php",
      data: { question_id: this.question_id },
      dataType: "JSON",
      success: (data) => {
        this.question = data;
      },
      error: () => {
        console.log("error");
      },
    });

    this.getAnswers();
  },
};
</script>

<style scoped lang="scss">
.questions {
  background-color: #474b4f;
  border-radius: 15px;
  min-height: 45vh;

  .question {
    color: #fff;
    text-align: left;
    padding: 13px 5px;
    min-height: 100px;

    .userImage {
      border-radius: 50%;
      margin-right: 5px;
    }
  }

  hr {
    height: 1px;
    background-color: #fff;
  }

  .textarea {
    padding: 10px;
    textarea {
      background-color: #6b6e70;
      color: #fff;
      outline: none;
      border: none;
      border-radius: 10px;
      &::placeholder {
        color: #fff;
      }
    }
  }
}
</style>
