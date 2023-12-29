<template>
  <div class="container-fluid text-white overflow-auto" id="user-profile">
    <div
      class="text-capitalize fs-3 h-100 d-flex justify-content-center align-items-center"
      v-if="!isUserExist"
    >
      this user does not exist.
    </div>
    <div class="d-flex flex-column flex-md-row h-100" v-if="isUserExist">
      <div
        class="user-info col col-lg-4 col-xxl-3 border-end border-opacity-25 border-secondary"
      >
        <div class="vstack p-2">
          <img
            v-if="userImage"
            :src="'/server/uploads/' + userImage"
            width="200px"
            height="200px"
            class="rounded-3"
          />
          <div class="fs-3 fw-semibold">
            {{ username }}
          </div>
          <table class="table mt-3 table-borderless text-white">
            <tbody>
              <tr>
                <td>Username</td>
                <td>{{ username }}</td>
              </tr>
              <tr>
                <td>Date-Of-Birth</td>
                <td>{{ date }}</td>
              </tr>
            </tbody>
          </table>
          <div class="p-2 mt-3">
            <div class="mb-2 fs-2">About</div>
            <p class="fs-5">
              {{ about }}
            </p>
          </div>
          <sendMessage>
            <input
              type="text"
              disabled="true"
              :value="username"
              class="form-control text-bg-dark text-white shadow-none"
              id="username"
            />
          </sendMessage>
        </div>
      </div>
      <div class="col h-100">
        <div class="container-fluid h-100">
          <transition name="slideUp" appear>
            <div
              v-if="!isQuestionExist"
              class="h-100 d-flex justify-content-center align-items-center text-capitalize fs-3 overflow-hidden"
            >
              this user does not have any questions yet.
            </div>
          </transition>
          <transition-group
            tag="div"
            v-if="isQuestionExist"
            class="row p-2 questions row-cols-1 row-cols-lg-2 row-cols-xl-3 gy-3"
            style="overflow-y: scroll"
            name="fade"
            appear
          >
            <div
              class="col"
              v-for="question in questions"
              :key="question.question_id"
            >
              <div
                class="card p-2 rounded-5 text-bg-dark"
                style="height: 250px"
              >
                <div class="hstack" style="gap: 0 5px">
                  <img
                    :src="'/server/uploads/' + userImage"
                    width="100px"
                    height="100px"
                    class="rounded-circle"
                  />
                  <div>{{ username }}</div>
                </div>
                <div class="card-body">
                  <p
                    v-snip="{ lines: 2 }"
                    class="card-text"
                    style="height: 50px; white-space: pre-wrap"
                  >
                    {{ question.question }}
                  </p>
                  <router-link
                    :to="{ path: `/question/${question.question_id}` }"
                    class="view btn bg-gradient col-5 text-white"
                  >
                    View
                  </router-link>
                </div>
              </div>
            </div>
          </transition-group>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="profile">
    <div v-if="!isUserExist" class="user-not-exist">
      The User Doesn't Exist.
    </div>
    <div class="container" v-if="isUserExist">
      <div class="profile-details col-12 mx-auto">
        <div class="img col-6">
          <img
            v-if="userImage !== null"
            :src="require(`../server/uploads/${userImage}`)"
            alt=""
            width="100%"
            height="100%"
          />
        </div>
        <div class="user-box">
          <h1 class="text-white text-center">
            {{ username }}
          </h1>
          <button class="send" @click="showMessageBox">Send Message</button>
          <div
            class="chat col-6 col-md-4 col-lg-3 col-xl-3 col-xxl-2"
            id="chat"
          >
            <div class="username" @click="hideConversation">
              <h3>{{ username }}</h3>
              <button @click="closeMessageBox" id="close">X</button>
            </div>
            <div class="chat-details">
              <UsersMessages
                :messages="messages"
                :isMessagesExist="isMessagesExist"
              />
              <div class="textarea">
                <textarea
                  name="message"
                  placeholder="Enter your message"
                  class="col-12"
                  @keydown.enter.exact.prevent="sendMessage"
                  @keydown.enter.shift.exact.prevent
                  id="textarea"
                  type="text"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->
</template>

<script>
import readUserQuestions from "@/js/readUserQuestions";
import sendMessage from "@/components/sendMessage.vue";
export default {
  name: "UserProfileView",
  components: {
    sendMessage,
  },
  data() {
    return {
      userImage: null,
      username: "",
      date: null,
      about: "",
      messages: [],
      questions: [],
      interval: null,
      isUserExist: true,
      isQuestionExist: false,
    };
  },

  methods: {
    setHeightOfProfile() {
      const headerHeight = document.querySelector("#header").offsetHeight;
      const windowHeight = window.innerHeight;
      const profileHeight = windowHeight - headerHeight;
      document
        .querySelector("#user-profile")
        .setAttribute("style", `height: ${profileHeight}px`);
    },
    async readUserData() {
      const response = await fetch("/server/api/Users/readUserData.php", {
        method: "POST",
        body: JSON.stringify({ user_id: this.$route.params.user_id }),
        headers: {
          "Content-type": "application/json",
          Accept: "application/json",
        },
      });
      const data = await response.json();
      if (data.error) {
        this.isUserExist = false;
        return;
      }
      this.isUserExist = true;
      this.username = data.username;
      this.userImage = data.image;
      this.date = data.date;
      this.about = data.about;
      this.readUserQuestions();
    },
    async readUserQuestions() {
      const questions = await readUserQuestions(this.$route.params.user_id);
      if (questions.error) {
        this.isQuestionExist = false;
      }
      if (questions.length) {
        this.isQuestionExist = true;
        this.questions = questions;
      }
    },
  },
  watch: {
    "$route.params.user_id"() {
      this.readUserData();
    },
  },
  async mounted() {
    this.setHeightOfProfile();
    this.readUserData();
  },

  destroyed() {
    clearInterval(this.interval);
  },
};
</script>

<style scoped lang="scss">
// .profile {
//   .user-not-exist {
//     height: 70vh;
//     display: flex;
//     justify-content: center;
//     align-items: center;
//     color: #fff;
//     font-size: 40px;
//   }
//   .container {
//     .profile-details {
//       display: flex;
//       justify-content: space-evenly;
//       margin-top: 8vh;
//       gap: 10px;

//       .img {
//         img {
//           border-radius: 50px;
//         }
//         .img-error {
//           height: 100%;
//           color: #fff;
//           font-size: 35px;
//           display: flex;
//           justify-content: center;
//           align-items: center;
//         }
//       }

//       .user-box {
//         display: flex;
//         flex-direction: column;
//         justify-content: space-between;
//         h1 {
//           font-size: 50px;
//           text-align: left;
//         }
//         .send {
//           display: block;
//           margin: 100px auto;
//           border: none;
//           outline: none;
//           color: #fff;
//           background-color: #63baab;
//           padding: 8px 11px;
//           border-radius: 10px;
//         }

//         .chat {
//           // border-radius: 15px;
//           display: none;
//           // flex-direction: column;
//           // justify-content: space-between;
//           // min-height: 300px;
//           position: fixed;
//           bottom: 0px;
//           right: 10px;
//           .username {
//             background-color: #474b4f;
//             border-radius: 15px 15px 0px 0px;
//             color: #fff;
//             padding: 5px 10px;
//             text-align: left;
//             display: flex;
//             justify-content: space-between;
//             align-items: center;
//             cursor: pointer;

//             #close {
//               border: none;
//               outline: none;
//               background: unset;
//               color: #fff;
//               font-size: 25px;
//             }
//           }

//           .chat-details {
//             .textarea {
//               textarea {
//                 outline: none;
//                 border: none;
//                 display: block;
//                 height: 35px;
//                 border-radius: 0px 0px 15px 15px;
//                 padding: 5px;
//                 resize: none;
//               }
//             }
//           }
//         }
//       }
//     }
//   }
// }
.questions {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.questions::-webkit-scrollbar {
  display: none;
}
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

.slideUp-enter,
.slideUp-leave-to {
  opacity: 0;
  -webkit-transform: translateY(30px);
  -moz-transform: translateY(30px);
  -o-transform: translateY(30px);
  transform: translateY(30px);
}
.slideUp-enter-active,
.slideUp-leave-active {
  -webkit-transition: all 0.5s ease;
  -moz-transition: all 0.5s ease;
  -o-transition: all 0.5s ease;
  transition: all 0.5s ease;
}

@media screen and (max-width: 767px) {
  .user-info {
    border: none !important;
  }
}
</style>
