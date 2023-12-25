<template>
<div class="container">
  <div class="chat_container">
        <div class="chat_friend_info">
      <img :src="'/storage/profile_pics/' + penpal.avatar" alt="" style="width: 125px; height: 125px; border-radius: 100%;" class="friend_chat_avatar"><p class="chat_friend_name">{{ penpal.name }}</p>
      </div>
      <hr>
      <div class="dialog">
      <div v-for="chat_message in messages" v-if="messages.length != 0">
          <div v-if="chat_message.is_received_message" class="other_user">
              <img :src="'/storage/profile_pics/'+chat_message.messager_pfp" style="width: 125px; height: 125px; border-radius: 100%;" alt="">
              <div class="text">
                  <div class="upper_line">
                      <p class="name_friend">{{ chat_message.messager_name }} {{ chat_message.messager_surname }}</p>
                      <p class="date_friend">{{ chat_message.time }}</p>
                  </div>
                  <p class="lower_line">
                      {{ chat_message.text }}
                  </p>
              </div>
          </div>
          <div v-else class="this_user">
                  <div class="text">
                  <div class="upper_line">
                      <p class="name_user">{{ chat_message.messager_name }}</p>
                      <p class="date_user">{{ chat_message.time }}</p>
                  </div>
                      <p class="lower_line">
                      {{ chat_message.text }}
                  </p>
                  </div>
              <img class="user_pfp_chat" :src="'/storage/profile_pics/' + chat_message.messager_pfp" style="width: 125px; height: 125px; border-radius: 100%;" alt="">
            </div>
      </div>
      <div class="empty" v-else>
          <p>Вы ещё не начали переписку</p>
      </div>
      </div>
  </div>
  <hr>
  <div class="buttom_form">
      <textarea v-model="chat_message" placeholder="Ваше сообщение здесь" name="" id="" cols="30" rows="10" class="chat_textarea"></textarea>
      <p class="message_err">{{ errors.message }}</p>
      <button  @click="send_message" class="send_msg_btn">Отправить</button>
  </div>
  </div>
</template>

<style>

.chat_friend_info {
    display: flex;
    column-gap: 5%;
    margin-top: 3%;
    margin-left: 5%;
    margin-bottom: 2%;
}

.chat_friend_name {
    font-size: 40px;
    margin-top: 2%;
}

hr {
    border: 3px solid black;
}

.dialog {
    height: 700px;
    text-overflow: ellipsis;
    overflow-y: scroll;
}

.chat_textarea {
    width: 1388px;
    height: 163px;
    border-radius: 50px;
    border: 2px solid black;
    padding: 1%;
    font-size: 24px;
    margin-left: 1%;
    margin-top: 0.5%;
}

.buttom_form {
    display: flex;
}

.send_msg_btn {
  width: 306px;
  height: 62px;
  border-radius: 15px;
  border: none;
  background-color: #C1D7A4;
  font-size: 24px;
  margin-left: auto;
  margin-right: auto;
  display: block;
  margin-top: 2%;
  cursor: pointer;
  margin-bottom: 4%;    
}

.this_user {
    display: flex;
    margin-top: 2%;
    position: relative;
    text-align: end;
    left: 80%;
}

.upper_line {
    display: flex;
    width: 200px;
    margin-left: 5%;
    margin-top: 5%;
}

.upper_line p {
    font-size: 24px;
}

.name_user {
    margin-right: 20%;
}

.lower_line {
    margin-left: 10%;
    margin-right: 10%;
    margin-top: 8%;
    font-size: 24px;
}

.other_user {
    display: flex;
    margin-top: 2%;
    margin-left: 5%;
}

.name_friend {
    margin-right: 20%;
}

</style>


<script>
import axios from "axios"
export default {
  name: "Chat",
  data() {
    return {
      penpal_id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1),
      chat_message: '',
      errors: {
          message: null
      },
      messages: null,
      penpal: null,
      invalid_user: false
    }
  },
  created() {
      console.log(this.penpal_id)
      this.$axios.get("http://127.0.0.1:8000/api/getmessages/"+this.penpal_id)
          .then((response) => {
              this.messages = response.data.data
          })
      setInterval(()=>{
          this.$axios.get("http://127.0.0.1:8000/api/getmessages/"+this.penpal_id)
          .then((response) => {
              if(this.messages != response.data.data){
                  this.messages = response.data.data
              }
          })
      }, 5000)
      this.$axios
            .post("http://127.0.0.1:8000/api/otheruserinfo",
                {
                    id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1)
                })
            .then((response) => {
                this.penpal = response.data;
                
            });
  },
  methods: {
        send_message() {
          this.errors = {
              message: null
          }
          this.$axios.post("http://127.0.0.1:8000/api/sendmessage", {
            message: this.chat_message,
            receiver_id: this.penpal_id,
          })
          .then((response) => {
              this.chat_message = ''
              this.$axios.get("http://127.0.0.1:8000/api/getmessages/"+this.penpal_id)
          .then((response) => {
              this.messages = response.data.data
              console.log(response.data)
          })
          })
          .catch((err) => {
            console.log(err);
            if (err.response.data.errors.message) {
              this.errors.message = err.response.data.errors.message[0];
            }
          });
      },
      
  },
  beforeRouteEnter(to, from, next) {
      if(!window.Laravel.user){
          return next("/");
      }
      if(window.Laravel.user.id == window.location.href.substring(window.location.href.lastIndexOf('/') + 1)){
          return next("user/" + window.Laravel.user.id);
      }
      axios.get('http://127.0.0.1:8000/api/user/'+window.location.href.substring(window.location.href.lastIndexOf('/') + 1)).then(response => {
          console.log("!!")
          if (response.data == 'no_user_found' ) {
              return next("user/" + window.Laravel.user.id);
          }
          next();
      })
  
},
};
</script>

