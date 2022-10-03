<template>
  <div class="messages">
    <div v-for="message in messages" :key="message['messages']" class="message">
      <div v-if="message['from'] === username" class="myMessage">
        {{ message["from"] }}: {{ message["message"] }}
      </div>

      <div v-else class="userMessage">
        {{ message["from"] }}: {{ message["message"] }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: this.$store.state.username,
    };
  },
  name: "UsersMessages",
  props: ["messages"],
};
</script>

<style lang="scss" scoped>
.chat-details {
  .messages {
    overflow-y: scroll;
    max-height: 300px;
    min-height: 290px;
    background-color: #474b4f;
    display: flex;
    //reverse the sorting of elements. elements above be down and
    // elements down be above. also I sorted the answers in DESC order
    // in database and with these two steps I dont need any more for
    // autoscrolling
    //specially column-reverse fix this problem. and also use sorting
    // in database fixed the order of answers
    flex-direction: column-reverse;
    .message {
      padding: 5px;
      .myMessage {
        background-color: gray;
        border-radius: 3px 15px 15px 15px;
        padding: 10px;
        color: #fff;
        text-align: left;
        word-wrap: break-word;
      }
      .userMessage {
        background-color: #38393a;
        border-radius: 3px 15px 15px 15px;
        padding: 10px;
        color: #fff;
        text-align: left;
        word-wrap: break-word;
      }
    }
  }
}
</style>
