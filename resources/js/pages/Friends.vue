<template>
  <div class="row">
    <input type="text" v-model="search" @keyup="search_friend">
    <div class="" v-for="friend in friends">
      <a :href="$router.resolve({ name: 'userPage', params: { id: friend.friend_id } }).href"><img style="width: 300px;" :src="'/storage/profile_pics/'+friend.friend_avatar" alt=""></a>
      <a :href="$router.resolve({ name: 'userPage', params: { id: friend.friend_id } }).href"><p>{{ friend.friend_name }}</p></a>
    </div>
    <div v-for="request in friend_requests" id="requests">
      <a :href="$router.resolve({ name: 'userPage', params: { id: request.user_id } }).href"><img style="width: 300px;" :src="'/storage/profile_pics/'+request.user_avatar" alt=""></a>
      <p>{{ request.user_name }}</p>
      <p>Хочет добавить вас в друзья</p>
      <button @click="request_response(true, request.user_id)">Принять</button>
      <button @click="request_response(false, request.user_id)">Отклонить</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      friends: [],
      friend_requests: [],
      search: ''
    };
  },
  created() {
    this.$axios
      .get("http://127.0.0.1:8000/api/getfriends")
        .then((response) => {
        console.log(response.data)
        this.friends = response.data.data;
      });
      this.$axios
      .get("http://127.0.0.1:8000/api/friendsrequests")
        .then((response) => {
        console.log(response.data.data)
        this.friend_requests = response.data.data;
      });
  },
  methods: {
    search_friend(){
      this.$axios
      .get("http://127.0.0.1:8000/api/getfriends")
        .then((response) => {
        this.friends = [];
        response.data.data.forEach(friend => {
          if(friend.friend_name.toLowerCase().includes(this.search.toLowerCase())){
            this.friends.push(friend)
          }
        });
      });
    },
    request_response(user_response, id){
      this.$axios
      .post("http://127.0.0.1:8000/api/respondtorequest",
      {
          id: id,
          accepted: user_response
        })
        .then((response) => {
          this.$axios
      .get("http://127.0.0.1:8000/api/friendsrequests")
        .then((response) => {
        console.log(response.data.data)
        this.friend_requests = response.data.data;
      });
      });
    },
  },
};
</script>