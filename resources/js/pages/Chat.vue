<template>
  <div class="chat_container">
      <h1>Диалог с {{ penpal.name }} {{ penpal.surname }}:</h1>
      <div class="dialog">
      <div v-for="chat_message in messages" v-if="messages.length != 0">
          <div v-if="chat_message.is_received_message" class="other_user">
              <img :src="'/storage/profile_pics/'+chat_message.messager_pfp" alt="">
              <div class="text">
                  <div class="upper_line">
                      <p class="name">{{ chat_message.messager_name }} {{ chat_message.messager_surname }}</p>
                      <p class="date">{{ chat_message.time }}</p>
                  </div>
                  <p class="lower_line">
                      {{ chat_message.text }}
                  </p>
              </div>
          </div>
          <div v-else class="this_user">
              <img class="user_pfp_chat" :src="'/storage/profile_pics/'+chat_message.messager_pfp" alt="">
              <div class="text">
                  <div class="upper_line">
                      <p class="name">{{ chat_message.messager_name }} {{ chat_message.messager_surname }}</p>
                      <p class="date">{{ chat_message.time }}</p>
                  </div>
                  <p class="lower_line">
                      {{ chat_message.text }}
                  </p>
              </div>
          </div>
      </div>
      <div class="empty" v-else>
          <p>Вы ещё не начали переписку</p>
      </div>
      </div>
  </div>
  <div class="buttom_form">
      <textarea v-model="chat_message" placeholder="Ваше сообщение здесь" name="" id="" cols="30" rows="10"></textarea>
      <p class="message_err">{{ errors.message }}</p>
      <button  @click="send_message">Отправить</button>
  </div>
</template>




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
      this.$axios.get('http://127.0.0.1:8000/api/user/'+this.penpal_id).then(response => {
          this.penpal = response.data;
          console.log(response.data)
      })
      setInterval(()=>{
          this.$axios.get("http://127.0.0.1:8000/api/getmessages/"+this.penpal_id)
          .then((response) => {
              if(this.messages != response.data.data){
                  this.messages = response.data.data
              }
          })
      }, 5000)
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

