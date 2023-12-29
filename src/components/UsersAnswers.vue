<template>
  <div class="overflow-hidden">
    <transition name="popup" appear>
      <div class="text-capitalize fs-2 p-3 text-center" v-if="!isAnswersExist">
        this question has no answers.
      </div>
    </transition>
    <div class="vstack gap-3 mt-2" v-if="isAnswersExist">
      <transition-group name="fade" appear>
        <div
          class="card-body position-relative border-bottom border-secondary"
          v-for="answer in allAnswers"
          :key="answer.answer_id"
        >
          <div
            class="hstack gap-2"
            role="button"
            style="width: fit-content"
            @click="showProfile(answer.user_id)"
          >
            <img
              :src="'/server/uploads/' + answer.image"
              width="50px"
              height="50px"
              class="rounded-circle"
            />
            <div>{{ answer.username }}</div>
          </div>
          <div class="card-text mt-2" style="white-space: pre-wrap">
            {{ answer.answer }}
          </div>
          <div
            class="dropup position-absolute top-50 end-0 translate-middle"
            v-if="answer.username === username"
          >
            <button
              type="button"
              class="dots btn text-white fs-3 shadow-none pt-0"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              ...
            </button>
            <ul class="dropdown-menu bg-dark">
              <li
                class="dropdown-item text-white"
                role="button"
                @click="deleteAnswer(answer.answer_id)"
              >
                Delete
              </li>
            </ul>
          </div>
        </div>
      </transition-group>
    </div>
  </div>
  <!-- <div class="answers">
    <transition name="popup" appear>
      <div class="answers-not-exist" v-if="!isAnswersExist">
        This Question Has No Answers Yet.
      </div>
    </transition>
    <div v-if="isAnswersExist">
      <transition-group name="fade" tag="div" class="answers-container">
        <div
          v-for="answer in allAnswers"
          :key="answer.answer_id"
          class="col-12 mt-1 answer"
        >
          <div>
            <img
              :src="require(`@/server/uploads/${answer.image}`)"
              alt="userImageAnswer"
              height="50px"
              width="50px"
              class="userImageAnswer"
            /><span
              style="cursor: pointer"
              @click="viewProfile(answer.user_id)"
              >{{ answer.username }}</span
            >
            <br />
            <p>{{ answer.answer }}</p>
            <div></div>
          </div>
          <button
            class="delete-answer"
            v-if="answer.username === username"
            @click="deleteAnswer(answer.answer_id)"
          >
            X
          </button>
        </div>
      </transition-group>
    </div>
  </div> -->
</template>

<script>
import viewProfile from "@/js/viewProfile";
export default {
  name: "UsersAnswers",
  props: ["allAnswers", "user_id", "username", "isAnswersExist"],
  methods: {
    showProfile(user_id) {
      viewProfile(user_id);
    },
    deleteAnswer: function (answer_id) {
      fetch("/server/api/Answers/deleteAnswer.php", {
        method: "POST",
        body: JSON.stringify({
          answer_id: answer_id,
        }),
        headers: {
          "Content-type": "application/json",
        },
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.dots:focus {
  border-color: #fff !important;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
  -webkit-transform: translateX(30px);
  -moz-transform: translateX(30px);
  -o-transform: translateX(30px);
  transform: translateX(30px);
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

.popup-enter {
  opacity: 0;
  transform: translateY(100px);
}

.popup-enter-to {
  opacity: 1;
  transform: translateY(0px);
}
.popup-enter-active {
  transition: all 0.5s ease;
}
</style>
