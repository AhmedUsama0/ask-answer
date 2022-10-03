<template>
  <div class="profile">
    <NavBar />
    <div class="container">
      <div class="profile-details col-12 mx-auto">
        <div class="img col-6">
          <img
            :src="require(`../php/uploads/${userImage}`)"
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
              type="text"
              hidden
              :value="this.$store.state.user_id"
              name="user_id"
            />
            <button type="submit" class="update">Update</button>
          </form>
          <div id="upload" class="col-2 p-3">Updating..</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavBar from "@/components/NavBar.vue";
import $ from "jquery";
export default {
  name: "ProfileView",

  data: function () {
    return {
      img_value: null,
      userImage: "male.png",
    };
  },

  components: {
    NavBar,
  },
  mounted: function () {
    if (this.$store.state.userImage !== null) {
      this.userImage = this.$store.state.userImage;
    }
  },

  methods: {
    getImgValue: function () {
      this.img_value = document.getElementById("img").files[0].name;
    },
    upload: function () {
      if ($("#img").val() !== "") {
        const myform = document.getElementById("myform");
        var formData = new FormData(myform);
        $("#upload").show();
        setTimeout(() => {
          $.ajax({
            method: "POST",
            url: "http://localhost/ask-answer/src/php/uploadImage.php",
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: (data) => {
              data = JSON.parse(data);
              if (data["userImage"] !== undefined) {
                this.$store.commit("setUserImage", data["userImage"]);
                this.userImage = data["userImage"];
                $("#upload").hide();
              } else {
                $("#upload").text(data["error"]);
                setTimeout(() => {
                  $("#upload").animate({ opacity: 0.5 }, "slow", "linear");
                  $("#upload").animate({ opacity: 0 }, "slow", "linear");
                }, 2000);
              }
            },

            error: () => {
              console.log(Error("Error is"));
            },
          });
        }, 2000);
      }
    },
  },
};
</script>

<style scoped lang="scss">
.profile {
  .container {
    .profile-details {
      display: flex;
      justify-content: space-around;
      margin-top: 8vh;

      .img {
        img {
          border-radius: 50px;
        }
      }

      .username {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
        #myform {
          display: flex;
          height: 50%;
          flex-direction: column;
          justify-content: space-between;
        }
        .update {
          display: block;
          padding: 8px 50px;
          margin: 30px auto 0 auto;
          border-radius: 10px;
          border: none;
          outline: none;
          color: #fff;
          background-color: #86c232;
        }

        h1 {
          font-size: 50px;
          text-align: left;
        }
        .img-upload {
          display: block;
          .img-logo {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 10px;
            align-items: center;
            // cursor: pointer;
            width: 200px;
            i {
              cursor: pointer;
            }
            span {
              display: block;
              word-wrap: break-word;
              max-width: 100%;
            }
          }
          input[type="file"] {
            display: none;
          }
        }

        #upload {
          position: absolute;
          background-color: gray;
          left: 40%;
          color: #fff;
          border-radius: 10px;
          display: none;
        }
      }
    }
  }
}
</style>
