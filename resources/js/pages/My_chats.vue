<template>
  <div class="container">
    <h1 class="chat_list_title">Список чатов</h1>
    <div class="chat_list_div">
    <div v-for="friend in friends" class="friend_chat_div">
      <a :href="$router.resolve({ name: 'userPage', params: { id: friend.friend_id } }).href"><img style="width: 125px; height: 125px; border-radius: 100%;" :src="'/storage/profile_pics/'+friend.friend_avatar" alt=""></a>
      <a :href="$router.resolve({ name: 'userPage', params: { id: friend.friend_id } }).href"><p>{{ friend.friend_name }}</p></a>
      <a :href="$router.resolve({name: 'Chat', params: { id: friend.friend_id }}).href"><button class="friend_write_btn">Написать</button></a>
    </div>
    </div>
  </div>
</template>

<style>

.chat_list_div {
  display: grid;
  grid-template-columns: 1fr 1fr;
  margin-left: 15%;
  margin-top: 4%;
}

.friend_chat_div {
  display: flex;
  column-gap: 5%;
}

.friend_chat_div p {
  margin-top: 45%;
  font-size: 40px;
  color: black;
}

.chat_list_title {
  text-align: center;
  margin-top: 4%;

}

.friend_write_btn {
  width: 263px;
  height: 50px;
  border: none;
  border-radius: 15px;
  font-size: 24px;
  background-color: #C1D7A4;
  margin-top: 5%;
  cursor: pointer;
  margin-top: 15%;  
  margin-left: 15%;
}

</style>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      friends: [],
      search: ''
    };
  },
  created() {
    this.$axios
      .get("http://127.0.0.1:8000/api/getchats")
        .then((response) => {
          console.log(response.data)
        for (let i = 0; i < response.data.data.length; i++) {
          if (response.data.data[i] === null) {
            response.data.data.splice(i, 1)
          }
        }
        this.friends = response.data.data;
      });
  },
  methods: {
   
  },
};
</script>