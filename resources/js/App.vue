<template>
  <div class="container">
    <div class="navbar-bav" v-if="isLogged">
      <router-link to="/dashboard">Dashboard</router-link>
      <router-link v-if="user.is_admin" to="/admin">Админ</router-link>
      <a href="#" @click="logout">logout</a>
      <a :href="$router.resolve({ name: 'userPage', params: { id: user.id } }).href">Личка</a>
      </div>
    <div class="" v-else>
      <router-link to="/">Home</router-link>
      <router-link to="/login">Login</router-link>
      <router-link to="/registration">Register</router-link>
    </div>

    <router-view></router-view>
  </div>
</template>

<script>
export default {
  name: "App",
  data() {
    return {
      isLogged: false,
      user: []
    };
  },
  created() {
    if (IsLogged) {
      this.isLogged = true;
      this.user = window.Laravel.user
    }
  },
  methods: {
    logout(e) {
      e.preventDefault();
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .post("/api/logout")
          .then((response) => {
              window.location.href = "/";

          })
          .catch(function (error) {
            console.error(error);
          });
      });
    },
  },
};
</script>