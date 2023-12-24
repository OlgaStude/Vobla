<template>
  <div class="row">
    <div class="" v-for="friend in friends">
      <a :href="$router.resolve({ name: 'userPage', params: { id: friend.friend_id } }).href"><img style="width: 300px;" :src="'/storage/profile_pics/'+friend.friend_avatar" alt=""></a>
      <a :href="$router.resolve({ name: 'userPage', params: { id: friend.friend_id } }).href"><p>{{ friend.friend_name }}</p></a>
      <a :href="$router.resolve({name: 'Chat', params: { id: friend.friend_id }}).href">Написать</a>

    </div>
  </div>
</template>

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
        this.friends = response.data.data;
      });
  },
  methods: {
   
  },
};
</script>