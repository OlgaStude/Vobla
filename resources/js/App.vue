<template>
  <div class="container">
    <div class="navbar">
    <div class="navbar-bav" v-if="isLogged">
      <div class="logo_div">
      <a href="/"><img src="/storage/img/logo.png"></a>
      <p>Vobla</p>
      </div>
      <div class="user_nav_div">
      <a href="/chats">Диалоги</a>
      <a href="/friends">Друзья</a>
      <a href="/admin" v-if="is_admin == 1">Админ</a>
      <a href="#" @click="logout">Выйти</a>
      <a :href="$router.resolve({ name: 'userPage', params: { id: user.id } }).href"><img :src="'/storage/profile_pics/' + user.avatar" class="user_avatar"></a>
      </div>
      </div>
    <div class="" v-else>
      <div class="logo_div">
      <a href="/"><img class="logo" src="/storage/img/logo.png"></a>
      <p>Vobla</p>
      </div>
      <div class="login_register_div">
      <a href="/login" class="login_nav">Авторизация</a>
      <a href="/registration">Регистрация</a>
      </div>

    </div>
    </div>
    <router-view></router-view>
  </div>
</template>

<style>
body {
  background: url(/storage/img/background.png);
  background-size: 100%;
  background-position-x: center;
}

a {
  text-decoration: none;
}

.navbar {
  background-color: #EBB3C0;
  height: 165px;
}

.login_register_div {
  text-align: end;
  position: relative;
  bottom: 5vh;
}

.login_register_div a {
  margin-right: 3%;
  font-size: 32px;
  color: black;
}

.logo_div {
  font-size: 32px;
  position: relative;
  margin-left: 2%;
  top: 2vh;
}

.user_avatar {
  width: 80px;
  height: 80px;
  border-radius: 100%;
  position: relative;
  top: 2vh;
}

.user_nav_div {
  text-align: end;
  position: relative;
  bottom: 6vh;
}

.user_nav_div a {
  margin-right: 6%;
  font-size: 32px;
  color: black;
  position: relative;
  bottom: 3vh;
}

@media (max-width: 320px) {

body {
  background: url(/storage/img/background_mb.png);
  background-size: 100%;
  background-position-x: center;
}

.navbar {
  height: 69px;
}

.logo_div img {
  width: 25px;
  height: 25px;
}

.logo_div p {
  font-size: 12px;
  position: relative;
  bottom: 1vh;
}

.login_register_div {
  bottom: 6vh;

}

.login_register_div a {
  font-size: 10px;
}

.user_nav_div a {
  font-size: 10px;
  bottom: 4vh;
}

.user_avatar {
  width: 30px;
  height: 30px;
}

}

</style>

<script>
export default {
  name: "App",
  data() {
    return {
      isLogged: false,
      user: [],
      is_admin: false
    };
  },
  created() {
    if (IsLogged) {
      this.isLogged = true;
      this.is_admin = window.Laravel.user.is_admin
      this.$axios
        .get("http://127.0.0.1:8000/api/userinfo")
        .then((response) => {
          this.user = response.data;
          console.log(this.user)

        });
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