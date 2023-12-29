<template>
  <div class="container-fluid text-white overflow-auto" id="profile">
    <div
      v-if="isUploading"
      class="overlay position-absolute top-0 end-0 w-100 h-100 d-flex justify-content-center align-items-center"
      style="background-color: rgba(0, 0, 0, 0.5); z-index: 20"
    >
      <div
        style="z-index: 30; gap: 0 10px; width: 350px"
        class="loading text-bg-dark fs-4 col-2 p-3 d-flex justify-content-center align-items-center"
      >
        <div class="text-center">{{ status }}</div>
        <div
          v-if="status === 'Loading...'"
          class="spinner-border"
          role="status"
        ></div>
      </div>
    </div>
    <div class="d-flex flex-column flex-md-row h-100">
      <div
        class="user-info col col-lg-4 col-xxl-3 border-end border-opacity-25 border-secondary"
      >
        <div class="vstack p-2">
          <div class="position-relative" style="width: fit-content">
            <img
              :src="'/server/uploads/' + this.$store.getters.getUserImage"
              width="200px"
              height="200px"
              class="rounded-3"
            />
            <form
              class="position-absolute bottom-0 end-0 m-1"
              style="width: fit-content"
              id="myform"
              enctype="multipart/form-data"
            >
              <input
                type="hidden"
                :value="this.$store.getters.getUserId"
                name="user_id"
              />
              <label
                for="image-file"
                class="camera rounded-circle fs-5 d-flex justify-content-center align-items-center bg-dark bg-gradient"
                style="width: 35px; height: 35px"
                role="button"
              >
                <i class="fa fa-camera"></i>
              </label>
              <input
                id="image-file"
                name="img"
                type="file"
                accept="image/png,image/jpg,image/jpeg"
                hidden
                @change="upload"
              />
            </form>
          </div>
          <div class="fs-3 fw-semibold">
            {{ this.$store.getters.getUserName }}
          </div>
          <table class="table mt-3 table-borderless text-white">
            <tbody>
              <tr>
                <td>Email</td>
                <td>{{ this.$store.getters.getUserEmail }}</td>
              </tr>
              <tr>
                <td>Username</td>
                <td>{{ this.$store.getters.getUserName }}</td>
              </tr>
              <tr>
                <td>Date-Of-Birth</td>
                <td>{{ this.$store.getters.getUserDate }}</td>
              </tr>
            </tbody>
          </table>
          <div class="p-2 mt-3">
            <div class="mb-2 fs-2">About</div>
            <p class="fs-5">
              {{ this.$store.getters.getUserAbout }}
            </p>
          </div>
          <EditInfo />
        </div>
      </div>
      <div class="col h-100">
        <div class="container-fluid h-100">
          <transition name="slideUp" appear>
            <div
              v-if="!isQuestionExist"
              class="h-100 d-flex justify-content-center align-items-center text-capitalize fs-3 overflow-hidden"
            >
              you don't have any questions yet.
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
                    :to="{ path: `question/${question.question_id}` }"
                    class="view btn bg-gradient col-5 text-white"
                  >
                    View
                  </router-link>
                  <button
                    @click="deleteQuestion(question.question_id)"
                    class="btn bg-danger bg-gradient text-white mx-2 col-5"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </transition-group>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="profile">
    <div class="container">
      <div class="profile-details col-12 mx-auto">
        <div class="img col-6">
          <img
            :src="require(`../server/uploads/${this.$store.state.userImage}`)"
            alt=""
            width="100%"
            height="100%"
          />
        </div>
        <div class="username">
          <h1 class="text-white">{{ this.$store.state.username }}</h1>
          <form
            enctype="multipart/form-data"
            @submit.prevent="upload"
            id="myform"
          >
            <label for="img" class="img-upload col-12">
              <div class="img-logo">
                <span class="text-white">Upload an Image</span>
                <i class="fa fa-images fa-lg text-white"></i>
                <span class="text-white">{{ img_value }}</span>
              </div>
              <input
                @change="getImgValue"
                type="file"
                id="img"
                name="img"
                accept="image/png, image/jpg, image/jpeg"
              />
            </label>
            <input
              type="hidden"
              :value="this.$store.state.user_id"
              name="user_id"
            />
            <button type="submit" class="update">Update</button>
          </form>
        </div>
      </div>
      <div id="upload" v-if="isUploading">
        {{ status }}
      </div>
    </div>
  </div> -->
</template>

<script>
import destroy from "@/js/destroyQuestion";
import EditInfo from "@/components/EditInfo.vue";
import readUserQuestions from "@/js/readUserQuestions";
import setHeightOfRestOfPage from "@/js/setHeightOfRestOfPage";
export default {
  name: "ProfileView",
  components: {
    EditInfo,
  },
  data: function () {
    return {
      isUploading: false,
      status: "",
      questions: [],
      isQuestionExist: false,
      username: this.$store.getters.getUserName,
      userImage: this.$store.getters.getUserImage,
    };
  },

  methods: {
    upload: async function (e) {
      if (e.target.files[0]) {
        const myform = document.getElementById("myform");
        const formData = new FormData(myform);
        this.status = "Loading...";
        this.isUploading = true;
        const response = await fetch("/server/api/Images/updateImage.php", {
          method: "POST",
          body: formData,
        });
        const data = await response.json();

        if (data.userImage) this.$store.commit("setUserImage", data.userImage);

        if (data.error) this.status = data.error;

        setTimeout(() => {
          this.isUploading = false;
          this.$router.go();
        }, 2000);
      }
    },
    async readUserQuestions() {
      const questions = await readUserQuestions(this.$store.getters.getUserId);
      if (questions.error) {
        this.isQuestionExist = false;
      }
      if (questions.length) {
        this.isQuestionExist = true;
        this.questions = questions;
      }
    },
    async deleteQuestion(question_id) {
      await destroy(question_id);
      this.readUserQuestions();
    },
  },
  mounted() {
    setHeightOfRestOfPage("#profile");
    this.readUserQuestions();
  },
};
</script>

<style scoped lang="scss">
.camera {
  &:hover {
    background-color: rgba(255, 255, 255, 0.4) !important;
  }
}
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
