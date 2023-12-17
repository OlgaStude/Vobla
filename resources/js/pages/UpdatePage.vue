<template>
  <div class="row">
    <div v-for="i in index">
      <input @click="removeCategory($event)" v-if="categories[i - 1].users" checked type="checkbox" :value="categories[i - 1].name" name="category" :id="'box_' + i">
      <input @click="addCategory($event)" v-else type="checkbox" :value="categories[i - 1].name" name="category" :id="'box_' + i">
      <label :for="'box_' + i">{{ categories[i - 1].name }}</label> 
    </div>
    <p>{{ category_success }}</p>
    <input type="text" v-model="user.name">
    <p>{{ errors.name }}</p>
    <input type="file" ref="avatar">
    <p>{{ errors.avatar[0] }}</p>
    <input type="text" v-model="personal.login">
    <p>{{ errors.login[0] }}</p>
    <p>{{ update_success }}</p>
    <button @click="update($event)">Обновить</button>
    <input type="password" v-model="password_old">
    <p>{{ errors.password_old }}</p>
    <input type="password" v-model="password_new">
    <p>{{ errors.password_new[0] }}</p>
      <p>{{ password_success }}</p>
    <button @click="changepassword($event)">Обновить</button>
  </div>
</template>

<script>

export default {
  name: "Dashboard",
  data() {
    return {
      user: [],
      categories: [],
      index: 0,
      personal: window.Laravel.user,
      login: window.Laravel.user.login,
      password_old: '',
      password_new: '',
      password_success: '',
      category_success: '',
      update_success: '',
      errors: {
        name: null,
        avatar: [],
        login: [],
        password_old: null,
        password_new: [],
      },
    };
  },
  created() {
    this.$axios
      .get("http://127.0.0.1:8000/api/userinfo")
      .then((response) => {
        this.user = response.data;

      });
      this.$axios
      .get("http://127.0.0.1:8000/api/categories")
        .then((response) => {
        console.log(response.data)
        this.categories = response.data.data;
        this.index = this.categories.length
      });
  },
  methods: {
    update(e) {
      e.preventDefault()
      this.errors = {
        name: null,
        avatar: [],
        login: [],
        password_old: null,
        password_new: [],
      }
      this.update_success = ''
      if (!/^([A-Za-zА-Яа-я \-\_]+)$/.test(this.user.name)) {
        this.errors.name = 'В имени могут быть только буквы, дефис и пробелы'
      };
      let sendlogin = ''
      if (this.personal.login == this.login) {
        sendlogin = 'nonewlogin'
      } else {
        sendlogin = this.personal.login
      }
      this.$axios.post("http://127.0.0.1:8000/api/updateuser",
        {
          name: this.user.name,
          avatar: this.$refs.avatar.files[0],
          login: sendlogin,
        },
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      )
        .then((response) => {
          this.update_success = 'Данные обновлены'
          console.log(response.data);
          this.$axios
            .get("http://127.0.0.1:8000/api/userinfo")
            .then((response) => {
              this.user = response.data;
            });
        })
        .catch((err) => {
          if (err.response.data.errors.avatar) {
            this.errors.avatar = err.response.data.errors.avatar;
          }
          if (err.response.data.errors.login) {
            this.errors.login = err.response.data.errors.login;
          }

        });
    },
    changepassword(e) {
      e.preventDefault()
      this.errors = {
        name: null,
        avatar: [],
        login: [],
        password_old: null,
        password_new: [],
      }
      this.$axios.post("http://127.0.0.1:8000/api/changepassword",
        {
          password: this.password_old,
          new_password: this.password_new
        },
      )
        .then((response) => {
          console.log(response.data)
          if (response.data.status == 400) {
            this.errors.password_old = response.data.message
            this.personal.login = this.login
          } else {
            this.password_new = ''
            this.password_old = ''
            this.password_success = 'Пароль был обновлён'
          }
        })
        .catch((err) => {
           if (err.response.data.errors.new_password) {
            this.errors.password_new = err.response.data.errors.new_password;
          }

        });
    },
    addCategory(e) {
      this.category_success = ''
       this.$axios.post("http://127.0.0.1:8000/api/useraddcategory",
        {
          name: e.target.value,
        },
      )
         .then((response) => {
          this.$axios
            .get("http://127.0.0.1:8000/api/categories")
            .then((response) => {
              console.log(response.data)
              this.categories = response.data.data;
              this.index = this.categories.length
            });
         this.category_success = 'категория была добавлена в ваши категории'
        })
        .catch((err) => {
          

        });
    },
    removeCategory(e) {
      this.category_success = ''
      this.$axios.post("http://127.0.0.1:8000/api/userremovecategory",
        {
          name: e.target.value,
        },
      )
        .then((response) => {
          this.categories = []
          this.$axios
            .get("http://127.0.0.1:8000/api/categories")
            .then((response) => {
              console.log(response.data)
              this.categories = response.data.data;
              this.index = this.categories.length
            });
          this.category_success = 'категория была убрана из ваших категорий'
        })
        .catch((err) => {


        });
    }
  },
};
</script>

