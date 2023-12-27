<template>
<div class="container">
    <div class="login_div">
    <h1>Авторизация</h1>
    <form>
      <input
        type="text"
        id="login"
        v-model="login"
        required
        autofocus
        placeholder="Логин"
      />
      <p>{{ errors.login[0] }}</p>
      <input
        type="password"
        id="password"
        v-model="password"
        required
        autofocus
        placeholder="Пароль"
      />
      <p>{{ errors.password[0] }}</p>
      </form>
      <p class="login_error">{{ errors.error }}</p>

    <button class="login_btn" type="submit" @click="handleSubmit">Войти</button>
  </div>
  </div>
</template>

<style>
.login_div {
  text-align: center;
}

.login_div h1 {
  margin-top: 10%;

}

.login_btn {
  width: 263px;
  height: 50px;
  border-radius: 15px;
  border: none;
  font-size: 24px;
  background-color: #C1D7A4;
  margin-top: 2%;
  cursor: pointer;
}

.login_error {
  font-size: 20px;
  color: #36522A;
  margin-top: 0.5%;
}

@media (max-width: 320px) {

  .login_error {
    font-size: 6px;
  }

  .login_btn {
    width: 74px;
    height: 14px;
    font-size: 8px;
  }
}

</style>

<script>
export default {
  name: "Login",
  data() {
    return {
      login: "",
      password: "",
      errors: {
        'password': [],
        'login': [],
        'error': null
      },
    };
  },
  created() {},
  methods: {
    handleSubmit(e) {
      e.preventDefault();
      this.errors = {
        'password': [],
        'login': [],
        'error': null
      },
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios
          .post("api/login", {
            login: this.login,
            password: this.password,
          })
          .then((response) => {
            console.log(response.data)
            if (response.data.status == 200) {
              window.location.href = "/user/" + response.data.user_id;
            } else {
              this.errors.error = response.data.message;
            }
          })
         .catch((err) => {
              if (err.response.data.errors.password) {
                this.errors.password = err.response.data.errors.password;
              }
              if (err.response.data.errors.login) {
                this.errors.login = err.response.data.errors.login;
              }

            });
      });
    },
  },
  beforeRouteEnter(to, from, next) {
    if (IsLogged) {
      return next("user/" + window.Laravel.user.id);
    }
    next();
  },
};
</script>