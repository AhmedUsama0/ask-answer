<template>
  <div class="container-fluid" id="messages">
    <div class="row h-100">
      <div
        class="col-6 h-100 col-md-4 text-bg-dark border-end border-secondary border-opacity-25"
      >
        <div class="container pt-3 h-100">
          <div class="position-relative">
            <i
              class="fa-solid fa-magnifying-glass position-absolute text-white top-50 translate-middle fs-6"
              style="left: 15px"
            ></i>
            <input
              type="search"
              class="form-control text-bg-dark shadow-none"
              placeholder="Search Conversations"
              style="padding-left: 28px"
              v-model="pattern"
            />
          </div>
          <div
            class="messages mt-3 overflow-auto"
            style="height: calc(100% - 60px)"
          >
            <div
              v-if="!areConversationsExist"
              class="h-100 text-capitalize fs-5 d-flex justify-content-center align-items-center"
            >
              you don't have conversations yet.
            </div>
            <div v-if="areConversationsExist" class="vstack gap-4">
              <div
                v-for="conversation in conversationsFilter"
                :key="conversation.message_id"
              >
                <div
                  role="button"
                  class="conversation hstack gap-2"
                  v-if="conversation.sender === myId"
                  @click="
                    openConversation(
                      conversation.receiver,
                      conversation.receiver_name,
                      conversation.receiver_image,
                      $event
                    )
                  "
                >
                  <img
                    :src="'/server/uploads/' + conversation.receiver_image"
                    alt="userImage"
                    class="rounded-circle"
                    width="80px"
                    height="80px"
                  />
                  <div class="pt-2">
                    <span style="font-size: 1.2em">{{
                      conversation.receiver_name
                    }}</span>
                    <p
                      class="text-nowrap overflow-hidden"
                      style="width: 100px; text-overflow: ellipsis"
                    >
                      {{ conversation.message }}
                    </p>
                  </div>
                </div>
                <div
                  role="button"
                  class="conversation hstack gap-2 p-2"
                  v-else
                  :class="{ 'bg-primary': !conversation.is_read }"
                  @click="
                    openConversation(
                      conversation.sender,
                      conversation.sender_name,
                      conversation.sender_image,
                      $event
                    )
                  "
                >
                  <img
                    :src="'/server/uploads/' + conversation.sender_image"
                    alt="userImage"
                    class="rounded-circle"
                    width="80px"
                    height="80px"
                  />
                  <div class="pt-2">
                    <span style="font-size: 1.2em">{{
                      conversation.sender_name
                    }}</span>
                    <p
                      class="text-nowrap overflow-hidden"
                      style="width: 100px; text-overflow: ellipsis"
                    >
                      {{ conversation.message }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-8 h-100 text-bg-dark p-0">
        <div
          class="text-bg-dark shadow-sm hstack gap-2 p-2"
          style="height: 66px"
          v-if="this.receiver"
        >
          <img
            v-if="conversationImage"
            :src="'/server/uploads/' + conversationImage"
            alt="userImage"
            class="rounded-circle"
            width="50px"
            height="50px"
          />
          <span v-if="conversationName">{{ conversationName }}</span>
        </div>
        <div
          class="container-fluid"
          style="height: calc(100% - 66px)"
          ref="messagesContainer"
        >
          <div
            class="messages-box p-2 gap-3 d-flex flex-column-reverse overflow-auto"
            ref="messagesBox"
          >
            <div
              v-for="message in messages"
              :key="message.message_id"
              class="hstack gap-1"
            >
              <img
                :src="'/server/uploads/' + message.sender_image"
                alt="userImage"
                class="rounded-circle"
                width="50px"
                height="50px"
              />
              <div
                style="white-space: pre-wrap"
                class="rounded-2 p-2"
                :class="{
                  'text-bg-secondary': message.sender === myId,
                  'text-bg-primary': message.sender !== myId,
                }"
              >
                {{ message.message }}
              </div>
            </div>
          </div>
          <div class="position-relative" v-if="this.receiver">
            <i
              class="fa-solid fa-paper-plane position-absolute top-50 translate-middle end-0 fs-5"
              @click="sendMessage"
            ></i>
            <textarea
              style="height: 20px; resize: none"
              type="text"
              class="form-control shadow-none text-bg-dark"
              placeholder="Send a Message"
              v-model="message"
              @keydown.enter.exact.prevent="sendMessage"
              @keydown.enter.shift.exact.prevent="message += '\n'"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import setHeightOfRestOfPage from "@/js/setHeightOfRestOfPage";
import sendMessage from "@/js/sendMessage";
export default {
  data() {
    return {
      conversations: [],
      areConversationsExist: false,
      messages: [],
      message: "",
      receiver: null,
      conversationName: "",
      conversationImage: "",
      myId: this.$store.getters.getUserId,
      messagesInterval: null,
      conversationsInterval: null,
      pattern: "",
    };
  },
  methods: {
    async readConversations() {
      const response = await fetch(
        "/server/api/Messages/readConversations.php",
        {
          method: "POST",
          body: JSON.stringify({
            user_id: this.$store.getters.getUserId,
          }),
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
        }
      );
      const conversations = await response.json();
      if (conversations.error) {
        this.areConversationsExist = false;
        return;
      }
      if (conversations.length) {
        this.areConversationsExist = true;
        this.conversations = conversations;
      }
      this.conversationsInterval = setTimeout(() => {
        this.readConversations();
      }, 1000);
    },
    async openConversation(
      receiver,
      conversationName,
      conversationImage,
      event
    ) {
      if (event.target.classList.contains("bg-primary")) {
        await this.setMessageAsRead(receiver);
      }
      clearTimeout(this.messagesInterval);
      this.setConversationReceiver(
        receiver,
        conversationName,
        conversationImage
      );
      this.getMessages();
    },
    async getMessages() {
      const response = await fetch("/server/api/Messages/readMessages.php", {
        method: "POST",
        body: JSON.stringify({
          sender: this.$store.getters.getUserId,
          receiver: this.receiver,
        }),
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      });
      const messages = await response.json();
      this.messages = messages;
      this.messagesInterval = setTimeout(() => {
        this.getMessages();
      }, 1000);
    },
    async sendMessage() {
      await sendMessage(
        this.$store.getters.getUserId,
        this.receiver,
        this.message
      );
      this.message = "";
    },
    setHeightOfMessagesBox() {
      const messagesContainer = this.$refs.messagesContainer.offsetHeight;
      const messageInput = 38;
      const messagesBox = messagesContainer - messageInput;
      this.$refs.messagesBox.setAttribute(
        "style",
        `height: ${messagesBox - 10}px`
      );
    },
    setConversationReceiver(receiver, conversationName, conversationImage) {
      this.conversationName = conversationName;
      this.conversationImage = conversationImage;
      this.receiver = receiver;
    },
    async setMessageAsRead(receiver) {
      await fetch("/server/api/Messages/updateMessageStatus.php", {
        method: "POST",
        body: JSON.stringify({
          sender: receiver,
          receiver: this.$store.getters.getUserId,
        }),
        headers: {
          "Content-Type": "application/json",
        },
      });
    },
  },
  computed: {
    conversationsFilter() {
      return this.conversations.filter((conversation) => {
        if (conversation.sender === this.myId) {
          return conversation.receiver_name.startsWith(this.capitalizePattern);
        }
        return conversation.sender_name.startsWith(this.capitalizePattern);
      });
    },
    capitalizePattern() {
      return this.pattern !== ""
        ? this.pattern.charAt(0).toUpperCase() + this.pattern.slice(1)
        : "";
    },
  },
  async mounted() {
    setHeightOfRestOfPage("#messages");
    this.setHeightOfMessagesBox();
    await this.readConversations();
    document.querySelector(".conversation").click();
  },
  beforeRouteLeave(to, from, next) {
    clearTimeout(this.messagesInterval);
    clearTimeout(this.conversationsInterval);
    next();
  },
};
</script>

<style></style>
