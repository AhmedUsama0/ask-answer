<template>
  <div class="profile">
    <NavBar />
    <div class="container">
      <div class="profile-details col-12 mx-auto">
        <div class="img col-6">
          <img
            v-if="userImage !== null"
            :src="require(`../php/uploads/${userImage}`)"
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
              <UsersMessages :messages="messages" />
              <div class="textarea">
                <textarea
                  name="message"
                  class="col-12"
                  @keyup.enter="sendMessage"
                  v-model="message"
                  id="textarea"
                  type="text"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import NavBar from "@/components/NavBar.vue";
import UsersMessages from "@/components/UsersMessages.vue";
export default {
  name: "UserProfileView",
  components: {
    NavBar,
    UsersMessages,
  },
  data() {
    return {
      userImage: null,
      username: null,
      message: null,
      messages: Array,
    };
  },

  computed: {
    user_id() {
      return this.$route.params.user_id;
    },
  },

  methods: {
    showConversation() {
      $("#chat").css({
        "min-height": "300px",
        height: "unset",
        overflow: "unset",
        "border-radius": "15px",
      });
      $(".username").css("border-radius", "15px 15px 0px 0px");
    },
    hideConversation() {
      if ($("#chat").css("min-height") === "300px") {
        $("#chat").css({
          "min-height": "0px",
          height: "40px",
          overflow: "hidden",
          "border-radius": "0px",
        });
        $(".username").css("border-radius", "0px");
      } else {
        this.showConversation();
      }
    },
    // autoScrollToBottom() {
    //   //the height of the scroll element which increases when more elements
    //   //added to it
    //   const scrollHeight = $("#messages").prop("scrollHeight");
    //   //scrollTop define the distance to leave from top
    //   //or the height to scroll from top
    //   //so we let it scroll the height equals to scrollHeight
    //   $("#messages").scrollTop(scrollHeight);
    // },
    showMessageBox() {
      $("#chat").show();
      this.showConversation();
    },

    closeMessageBox() {
      $("#chat").hide();
    },
    sendMessage() {
      if (this.message !== "\n") {
        $.ajax({
          method: "POST",
          url: "http://localhost/ask-answer/src/php/createMessage.php",
          data: {
            message: this.message,
            from: this.$store.state.username,
            to: this.username,
          },
        });
        this.message = "";
      }
    },

    getMessages() {
      $.ajax({
        method: "POST",
        url: "http://localhost/ask-answer/src/php/getMessages.php",
        data: {
          from: this.$store.state.username,
          to: this.username,
        },
        dataType: "JSON",

        success: (data) => {
          try {
            this.messages = data;
          } catch (err) {
            console.log("Error is " + err.message);
          }
        },
      });
    },
  },
  mounted() {
    // $(document).ready(function () {
    //   $("#chat").hide();
    // });
    $.ajax({
      method: "GET",
      url: "http://localhost/ask-answer/src/php/getUserProfileData.php",
      data: { user_id: this.user_id },
      dataType: "JSON",
      success: (data) => {
        this.username = data["username"];
        this.userImage = data["userImage"];
      },
    });
    setInterval(() => {
      this.getMessages();
    }, 500);
  },
};
</script>

<style scoped lang="scss">
.profile {
  .container {
    .profile-details {
      display: flex;
      justify-content: space-evenly;
      margin-top: 8vh;
      gap: 10px;

      .img {
        img {
          border-radius: 50px;
        }
      }

      .user-box {
        h1 {
          font-size: 50px;
          text-align: left;
        }
        .send {
          display: block;
          margin: 100px auto;
          border: none;
          outline: none;
          color: #fff;
          background-color: #86c232;
          padding: 8px 11px;
          border-radius: 10px;
        }

        .chat {
          // border-radius: 15px;
          display: none;
          // flex-direction: column;
          // justify-content: space-between;
          // min-height: 300px;
          position: fixed;
          bottom: 0px;
          right: 10px;
          .username {
            background-color: #474b4f;
            border-radius: 15px 15px 0px 0px;
            color: #fff;
            padding: 5px 10px;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;

            #close {
              border: none;
              outline: none;
              background: unset;
              color: #fff;
              font-size: 25px;
            }
          }

          .chat-details {
            .textarea {
              textarea {
                outline: none;
                border: none;
                display: block;
                height: 35px;
                border-radius: 0px 0px 15px 15px;
                padding: 5px;
              }
            }
          }
        }
      }
    }
  }
}
</style>
