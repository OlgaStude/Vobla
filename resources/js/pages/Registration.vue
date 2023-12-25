<template>
  <div class="container">
    <div class="register_div">
      <h1>Регистрация</h1>
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
        <p class="interest_category">Интересующие категории</p>
        <div class="register_category_div">
        <div v-for="i in index" class="register_category_div2">
          <input type="checkbox" :value="categories[i - 1].name" name="category" :id="'box_' + i" class="register_category_checkbox">
          <label :for="'box_' + i">{{ categories[i - 1].name }}</label>
        </div>
        </div>
        <input type="file" id="avatar" ref="avatar">
        <p>{{ errors.avatar[0] }}</p>
        <input @click="checkmark" type="checkbox" id="check" class="input_checkbox">
        <label class="checkbox_label" for="check">Я согласен на обработку персональных данных</label>
        <button class="register_btn" id="send_btn" disabled @click="register">Зарегистрироваться</button>
      </form>
    </div>
  </div>
</template>

<style>


.container {
  width: 1920px;
  margin-left: auto;
  margin-right: auto;
}

.register_div {
  text-align: center;
}

.register_div h1 {
  margin-top: 10%;

}

form input {
  width: 508px;
  height: 54px;
  border: 2px solid black;
  border-radius: 50px;
  background-color: #D9D9D9 0%;
  font-size: 24px;
  color: black;
  padding: 1%;
  margin-top: 2%;
}

.input_checkbox {
  width: 25px;
  height: 25px;
}

.checkbox_label {
  font-family: text;
  font-size: 24px;
  margin-left: 1%;
}

.register_btn {
  width: 306px;
  height: 62px;
  border-radius: 15px;
  border: none;
  background-color: #C1D7A4;
  font-size: 24px;
  margin-left: auto;
  margin-right: auto;
  display: block;
  margin-top: 2%;
  cursor: pointer;
  margin-bottom: 4%;
}

form p {
  font-size: 20px;
  color: #36522A;
  text-align: start;
  margin-left: 38%;
  margin-top: 0.5%;
}

.register_category_div {
  text-align: start;
  display: flex;
  justify-content: center;
  margin-left: 42.5%;
  margin-top: 1%;
  width: 250px;
  text-overflow: ellipsis;
  overflow-x: scroll;
  padding-left: 3%;
  padding-bottom: 1%;
}

.register_category_div2 {
  display: flex;
  align-items: center;
  flex-direction: column;
  margin-left: 10%;
}

.register_category_div2 input {
  width: 25px;
  height: 25px;
  border: 2px solid black;
}

.register_category_div2 label {
  font-size: 20px;
  font-family: text;
}

.interest_category {
  font-size: 24px;
  color: black;
  margin-top: 4%;
  margin-left: 42.5%
}

.register_category_div::-webkit-scrollbar {
            height: 2px;
        }
        .register_category_div::-webkit-scrollbar-track {
        background: #e8e8e8; 
        }
        .register_category_div::-webkit-scrollbar-thumb {
        background: #1D1A0F; 
        }

</style>

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