<template>
  <div>
    <li
      class="nav-link text-white fw-bold"
      data-bs-toggle="modal"
      data-bs-target="#staticBackdrop"
      role="button"
    >
      AskQuestion
    </li>

    <div
      class="modal fade"
      id="staticBackdrop"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="staticBackdropLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
          <div class="modal-header">
            <h1
              class="modal-title text-capitalize fs-5 text-white"
              id="staticBackdropLabel"
            >
              new question
            </h1>
          </div>
          <div class="modal-body">
            <div class="form-floating">
              <textarea
                class="question form-control bg-dark text-white shadow-none"
                id="question"
                placeholder="your question here"
                style="height: 200px; resize: none; caret-color: #fff"
                v-model="question"
              ></textarea>
              <label for="question" class="text-capitalize text-white"
                >your question</label
              >
            </div>
          </div>
          <div class="modal-footer">
            <button
              style="background-color: gray"
              type="button"
              class="btn bg-gradient text-white"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button
              style="background-color: #394f8a"
              type="button"
              class="btn bg-gradient text-white"
              @click="createQuestion"
            >
              Ask
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="ask-question">
    <div class="error" v-if="error">
      Something Wrong Occured. Try Again Later.
    </div>
    <div class="container">
      <div class="textarea mt-5">
        <textarea
          placeholder="Enter Your Question"
          name="question"
          class="col-12 mx-auto card p-2"
          rows="10"
          v-model="question"
          @keydown.enter.exact.prevent="question += '\n'"
        />
        <button class="ask mt-1" @click="createQuestion">Ask</button>
      </div>
    </div>
  </div> -->
</template>

<script>
export default {
  name: "AskQuestionView",
  data: function () {
    return {
      question: "",
      error: false,
    };
  },

  methods: {
    createQuestion: function () {
      if (this.question.trim()) {
        fetch("/server/api/Questions/createQuestion.php", {
          method: "POST",
          body: JSON.stringify({
            user_id: this.$store.state.user_id,
            question: this.question,
          }),
        })
          .then(() => {
            if (this.$route.name === "profile") this.$router.go();
            else {
              this.$router.replace({ name: "profile" });
              this.$router.go();
            }
            this.question = "";
          })
          .catch(() => {
            this.error = true;
            setTimeout(() => {
              this.error = false;
            }, 3000);
          });
      }
    },
  },
};
</script>

<style scoped lang="scss">
.question:focus {
  border-color: #fff;
}
.error {
  position: absolute;
  background-color: #7f6899 !important;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  color: #fff;
  border-radius: 10px;
  padding: 15px;
  width: 250px;
  z-index: 50;
}
</style>
