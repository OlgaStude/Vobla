<template>
  <div class="row">
    <form action="">
      <input type="text" id="name" v-model="name" placeholder="Имя и фамилия">
      <p>{{ errors.name }}</p>
      <input type="text" id="login" v-model="login" placeholder="Логин">
      <p>{{ errors.login[0] }}</p>
      <input type="text" id="email" v-model="email" placeholder="Эл.почта">
      <p>{{ errors.email }}</p>
      <input type="password" id="password" v-model="password" placeholder="Пароль">
      <p>{{ errors.password[0] }}</p>
      <input type="password" id="password_repeat" v-model="password_repeat" placeholder="Подтвердите пароль">
      <p>{{ errors.password_repeat }}</p>
      <div v-for="i in index">
        <input type="checkbox" :value="categories[i - 1].name" name="category" :id="'box_'+i">
        <label :for="'box_' + i">{{ categories[i - 1].name }}</label> 
      </div>
      <input type="file" id="avatar" ref="avatar">
      <p>{{ errors.avatar[0] }}</p>
      <input @click="checkmark" type="checkbox" id="check">
      <label for="check">Я согласен на обработку персональных данных</label>
      <button id="send_btn" disabled @click="register">Регистрация</button>
    </form>
  </div>
</template>

<script>
export default {
  name: "Registration",
  data() {
    return {
      name: '',
      login: '',
      email: '',
      password: '',
      password_repeat: '',
      categories: [],
      categories_send: [],
      errors: {
        name: null,
        login: [],
        email: null,
        password: [],
        password_repeat: null,
        avatar: []
      },
      index: 3
    };
  },
  created() {
     this.$axios
      .get("http://127.0.0.1:8000/api/categories")
      .then((response) => {
        console.log(response.data.data)
        this.categories = response.data.data;
        this.index = this.categories.length
      });
  },
  methods: {
    register(e) {
      e.preventDefault()
      this.errors = {
        name: null,
        login: [],
        email: null,
        password: [],
        password_repeat: null,
        avatar: []
      }
      this.categories_send = []
      
      if (!/^([A-Za-zА-Яа-я \-\_]+)$/.test(this.name)) {
        this.errors.name = 'В имени могут быть только буквы, дефис и пробелы'
        console.log(this.errors.name)
      };
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
        this.errors.email = 'Некорректный формат'
      };
      if (this.password != this.password_repeat) {
        this.errors.password_repeat = 'Пароли не совпадают'
      }
      let ele = document.getElementsByName("category");
      for (var i = 0; i < ele.length; i++){
        if (ele[i].checked) {
          this.categories_send.push(ele[i].value)
        }
      }
      this.$axios.get("/sanctum/csrf-cookie").then((response) => {
        this.$axios.post("api/register",
          {
            name: this.name,
            login: this.login,
            email: this.email,
            password: this.password,
            avatar: this.$refs.avatar.files[0],
            categories: this.categories_send
          },
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
          .then((response) => {
            console.log(response);
              window.location.href = "/user/" + response.data.user_id;

          })
          .catch((err) => {
            if (err.response.data.errors.email) {
              this.errors.email = err.response.data.errors.email[0];
            }
            if (err.response.data.errors.password) {
              this.errors.password = err.response.data.errors.password;
            }
            if (err.response.data.errors.login) {
              this.errors.login = err.response.data.errors.login;
            }
            if (err.response.data.errors.avatar) {
              this.errors.avatar = err.response.data.errors.avatar;
            }
            
          });
      });
  

    },
     checkmark() {
      if (!this.is_checked) {
        document.getElementById("send_btn").removeAttribute("disabled");
      } else {
        document
          .getElementById("send_btn")
          .setAttribute("disabled", true);
      }
    },
  },
  beforeRouteEnter(to, from, next) {
    if (IsLogged) {
      return next("user/" + window.Laravel.user.id);
    }
    next();
  },
  beforeRouteEnter(to, from, next) {
    if (IsLogged) {
      return next("user/" + window.Laravel.user.id);
    }
    next();
  },
};
</script>