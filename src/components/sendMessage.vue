<template>
  <div>
    <button
      type="button"
      class="btn btn-primary text-capitalize text-bg-dark border-0 bg-gradient w-100"
      data-bs-toggle="modal"
      data-bs-target="#sendMessage"
    >
      send message
    </button>

    <div
      class="modal fade"
      id="sendMessage"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark">
          <div class="modal-header">
            <h1 class="modal-title fs-5 text-capitalize" id="exampleModalLabel">
              send message
            </h1>
          </div>
          <div class="modal-body">
            <div class="col-12">
              <label for="username" class="form-label text-capitalize"
                >username</label
              >
              <slot></slot>
            </div>
            <div class="col-12 mt-3">
              <label for="message" class="form-label text-capitalize"
                >message</label
              >
              <textarea
                class="form-control text-bg-dark shadow-none"
                id="message"
                v-model="message"
                style="height: 200px; resize: none"
              />
            </div>
          </div>
          <div class="modal-footer">
            <button
              style="background-color: gray"
              type="button"
              ref="close"
              class="btn text-capitalize bg-gradient text-white"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button
              style="background-color: #394f8a"
              type="button"
              class="btn text-capitalize bg-gradient text-white"
              @click="sendMessage"
            >
              send
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import sendMessage from "@/js/sendMessage";
export default {
  data() {
    return {
      message: "",
    };
  },

  methods: {
    async sendMessage() {
      sendMessage(
        this.$store.getters.getUserId,
        this.$route.params.user_id,
        this.message
      ).then(() => {
        this.$router.replace({ name: "messages" });
        this.$refs.close.click();
      });
    },
  },
};
</script>
