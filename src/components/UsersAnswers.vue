<template>
  <div class="answers">
    <div
      v-for="answer in allAnswers"
      :key="answer['answer_id']"
      class="col-12 mt-1 answer"
    >
      <div>
        <img
          :src="require(`@/php/uploads/${answer['userImage']}`)"
          alt="userImageAnswer"
          height="50px"
          width="50px"
          class="userImageAnswer"
        /><span
          style="cursor: pointer"
          @click="viewProfile(answer['user_id'])"
          >{{ answer["username"] }}</span
        >
        <br />
        <p>{{ answer["answer"] }}</p>
        <div></div>
      </div>
      <button
        class="delete-answer"
        v-if="answer['username'] === username"
        @click="deleteAnswer(answer['answer_id'])"
      >
        X
      </button>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
export default {
  name: "UsersAnswers",
  props: ["allAnswers", "user_id", "username", "viewProfile"],

  methods: {
    deleteAnswer: function (answer_id) {
      $.ajax({
        method: "POST",
        url: "http://localhost/ask-answer/src/php/deleteAnswer.php",
        data: { answer_id: answer_id },
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.answers {
  padding: 10px;
  .answer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-align: left;
    padding: 10px;
    background-color: #6b6e70;
    color: #fff;
    border-radius: 10px;

    .delete-answer {
      border: none;
      outline: none;
      background-color: unset;
      color: #fff;
      font-size: 20px;
      &:hover::after {
        content: "Delete Answer";
        position: absolute;
        right: 27%;
        border-radius: 10px;
        padding: 5px;
        font-size: 13px;
        color: #fff;
        background-color: #222629;
      }
    }

    .userImageAnswer {
      border-radius: 50%;
      margin-right: 3px;
    }

    p {
      margin: 0;
      margin-top: 10px;
    }
  }
}
</style>
