<template>
  <div class="ask-question">
    <NavBar />
    <div class="container">
      <div class="textarea mt-5">
        <textarea
          placeholder="Enter Your Question"
          name="question"
          class="col-12 mx-auto card p-2"
          rows="10"
          v-model="question"
        />
        <button class="ask mt-1" @click="send">Ask</button>
      </div>
    </div>
  </div>
</template>

<script>
import NavBar from "@/components/NavBar.vue";
import $ from "jquery";
export default {
  name: "AskQuestionView",
  components: {
    NavBar,
  },
  data: function () {
    return {
      question: "",
    };
  },

  methods: {
    send: function () {
      if (this.question !== "") {
        $.ajax({
          method: "POST",
          url: "http://localhost/ask-answer/src/php/createQuestion.php",
          data: {
            user_id: this.$store.state.user_id,
            question: this.question,
          },
          success: () => {
            this.$router.replace({ name: "myQuestions" });
          },

          error: (error) => {
            console.log(Error("Error is " + error.message));
          },
        });
      }
    },
  },
};
</script>

<style scoped lang="scss">
.ask {
  display: block;
  border: none;
  outline: none;
  padding: 7px 12px;
  width: 100px;
  color: #fff;
  background-color: #86c232;
  text-transform: capitalize;
  font-size: 18px;
  border-radius: 5px;
}

textarea {
  background-color: #474b4f;
  color: #fff;
  outline: none;
  border: none;
  border-radius: 10px;
}
textarea::placeholder {
  color: #fff;
}
</style>
