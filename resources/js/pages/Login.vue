<template>
  <div class="container">
    <form>
      <label for="email">Логин</label>
      <input
        type="text"
        id="login"
        v-model="login"
        required
        autofocus
        placeholder="enter login"
      />
      <strong>{{ errors.login[0] }}</strong>

      <label for="password">Пароль</label>
      <input
        type="password"
        id="password"
        v-model="password"
        required
        autofocus
        placeholder="enter password"
      />
    <strong>{{ errors.password[0] }}</strong>
      </form>
      <strong>{{ errors.error }}</strong>

    <button type="submit" @click="handleSubmit">Log in</button>

    <div>
      not with us yet??
      <router-link to="/registration">J O I N I N</router-link>
    </div>
  </div>
</template>

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