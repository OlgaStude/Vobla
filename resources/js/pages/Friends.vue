<template>
  <div class="container">
    <input type="text" v-model="search" @keyup="search_friend" class="friends_search">
    <div class="friends_list_div">
    <div>
    <h1>Список друзей</h1>
    <div v-for="friend in friends" class="friends_div">
      <a :href="$router.resolve({ name: 'userPage', params: { id: friend.friend_id } }).href"><img style="width: 125px; height: 125px; border-radius: 100%;" :src="'/storage/profile_pics/'+friend.friend_avatar" alt=""></a>
      <a :href="$router.resolve({ name: 'userPage', params: { id: friend.friend_id } }).href"><p class="friend_name">{{ friend.friend_name }}</p></a>
    </div>
    </div>
    <div>
    <h1>Заявки в друзья</h1>
    <div v-for="request in friend_requests" id="requests" class="friends_request_div">
      <a :href="$router.resolve({ name: 'userPage', params: { id: request.user_id } }).href"><img style="width: 125px; height: 125px; border-radius: 100%;" :src="'/storage/profile_pics/'+request.user_avatar" alt=""></a>
      <div class="friends_request_div2">
      <p class="friends_request_name">{{ request.user_name }}</p>
      <p class="god_will">Хочет добавить вас в друзья</p>
      </div>
      <div class="friends_request_div3">
      <button @click="request_response(true, request.user_id)" class="request_accept_btn">Принять</button>
      <button @click="request_response(false, request.user_id)" class="request_decline_btn">Отклонить</button>
      </div>
    </div>
    </div>
    </div>
  </div>
</template>

<style>

.friends_search {
  width: 487px;
  height: 54px;
  border: 2px solid black;
  border-radius: 50px;
  font-size: 24px;
  color: black;
  padding: 1%;
  margin-left: 10%;
  margin-top: 3%;
}

.friends_list_div {
  display: grid;
  grid-template-columns: 1fr 1fr;
  margin-top: 2%;
  margin-left: 10%;
}

.friends_div {
  display: flex;
  margin-top: 4%;
  height: 160px;
}

.friend_name {
  margin-top: 40px;
  margin-left: 40px;
  color: black;
  font-size: 40px;
}

.friends_request_div {
  display: flex;
  margin-top: 4%;
  column-gap: 2%;
}

.friends_request_div2 {
  margin-top: 4%;
  margin-left: 5%;
}

.god_will {
  font-size: 20px
}

.friends_request_name {
  font-size: 40px;
}

.friends_request_div3 {
  margin-left: 2%;
}

.request_accept_btn {
  width: 263px;
  height: 50px;
  border: none;
  border-radius: 15px;
  font-size: 24px;
  background-color: #C1D7A4;
  margin-top: 5%;
  cursor: pointer;
}

.request_decline_btn {
  width: 263px;
  height: 50px;
  border: none;
  border-radius: 15px;
  font-size: 24px;
  background-color: #F4BAB9;
  margin-top: 5%;
  cursor: pointer;
}

</style>

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
        this.$axios
        .get("http://127.0.0.1:8000/api/getfriends")
        .then((response) => {
          this.friends = [];
          response.data.data.forEach(friend => {
            if (friend.friend_name.toLowerCase().includes(this.search.toLowerCase())) {
              this.friends.push(friend)
            }
          });
        });
      });
      });
    },
  },
};
</script>