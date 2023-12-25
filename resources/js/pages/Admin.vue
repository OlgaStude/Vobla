<template>
  <div class="container">
    <div class="admin_div">
    <h1>Управление категориями</h1>
    <div class="admin_category_div">
    <input type="text" v-model="new_category" placeholder="Название категории">
    <p class="admin_error_add">{{ errors.name[0] }}</p>
    <p class="admin_error_add">{{ success_message }}</p>
    <button @click="createCategory($event)" class="category_add_btn">Добавить категорию</button>
    </div>
    <div class="admin_category_div">
    <select @change="onChangeCategory($event)" name="" id="lang_s">
        <option>Выберите категорию</option>
        <option v-for="category of categories" :value="category.name">{{ category.name }}</option>
    </select>
    <button @click="deleteCategory($event)" class="category_delete_btn">Удалить категорию</button>
    </div>
    <div class="admin_category_div">
    <input type="text" v-model="category_name">
    <p class="admin_error_delete">{{ errors.name_change[0] }}</p>
      <p class="admin_error_delete">{{ errors.name_not_chosen }}</p>
    <p class="admin_error_delete">{{ success_message_change }}</p>
      <button @click="updateCategory($event)" class="category_add_btn">Редактировать</button>
    </div>
  </div>
  </div>
</template>

<style>

.admin_div {
  text-align: center;
}

.admin_div h1 {
  margin-top: 6%;
}

.admin_category_div {
  display: flex;
  justify-content: center;
  margin-top: 4%;
}

.admin_category_div input {
  width: 508px;
  height: 54px;
  border: 2px solid black;
  background-color: #D9D9D9 0%;
  border-radius: 50px;
  font-size: 24px;
  padding: 1%;
  color: black;
}

.admin_category_div select {
  width: 508px;
  height: 54px;
  border: 2px solid black;
  background-color: #D9D9D9 0%;
  border-radius: 50px;
  font-size: 24px;
  padding-left: 1%;
  color: black; 
}

.category_add_btn {
  width: 263px;
  height: 50px;
  border: none;
  border-radius: 15px;
  font-size: 24px;
  background-color: #C1D7A4;
  margin-left: 5%;
}

.category_delete_btn {
  width: 263px;
  height: 50px;
  border: none;
  border-radius: 15px;
  font-size: 24px;
  background-color: #F4BAB9;
  margin-left: 5%;  
}

.admin_error_add {
  font-size: 20px;
  color: #36522A;
  position: absolute;
  margin-top: 4%;
  margin-right: 31%;
}

.admin_error_delete {
  font-size: 20px;
  color: #36522A;
  position: absolute;
  margin-top: 4%;
  margin-right: 25%;
}

</style>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      new_category: '',
      success_message: '',
      success_message_change: '',
      categories: [],
      category_name: '',
      old_name: '',
      errors: {
        name: [],
        name_change: [],
        name_not_chosen: ''
      }
    };
  },
  created() {
    this.$axios
      .get("http://127.0.0.1:8000/api/categories")
      .then((response) => {
        this.categories = response.data.data;
      });
  },
  methods: {
    createCategory(e) {
      e.preventDefault()
      this.errors = {
        name: [],
        name_change: [],
        name_not_chosen: ''
      }
      this.$axios.post("http://127.0.0.1:8000/api/addcategory",
        {
          name: this.new_category,
        }
      )
        .then((response) => {
          this.success_message = response.data.message
          this.new_category = ''
          this.$axios
          .get("http://127.0.0.1:8000/api/categories")
          .then((response) => {
            this.categories = response.data.data;
          });
        })
        .catch((err) => {
          console.log(err.response.data.errors)
          if (err.response.data.errors.name) {
            this.errors.name = err.response.data.errors.name;
          }

        });
    },
    onChangeCategory(e) {
      if (e.target.value != 'Выберите категорию') {
        this.category_name = e.target.value 
        this.old_name = e.target.value
      } else {
        this.category_name = ''
        this.old_name = ''
      }
    },
    updateCategory(e) {
      e.preventDefault()
      this.errors = {
        name: [],
        name_change: [],
        name_not_chosen: ''
      }
      this.success_message_change = ''
      this.$axios.post("http://127.0.0.1:8000/api/updatecategory",
        {
          name: this.category_name,
          old_name: this.old_name
        }
      )
        .then((response) => {
          this.success_message_change = response.data.message
          this.category_name = '',
            this.old_name = ''
          this.$axios
            .get("http://127.0.0.1:8000/api/categories")
            .then((response) => {
              this.categories = response.data.data;
            });
        })
        .catch((err) => {
          console.log(err.response.data.errors)
          if (err.response.data.errors.name) {
            if (err.response.data.errors.name[0] != 'Введите какое-нибудь значение') {
              this.errors.name_change = err.response.data.errors.name;
            } else {
              this.errors.name_not_chosen = 'Категория для обновления не выбрана'
            }
          }

        });
    },
    deleteCategory(e) {
      e.preventDefault()
      this.errors = {
        name: [],
        name_change: [],
        name_not_chosen: ''
      }
      this.success_message_change = ''
      this.$axios.post("http://127.0.0.1:8000/api/deletecategory",
        {
          name: this.old_name
        }
      )
        .then((response) => {
          this.success_message_change = response.data.message
          this.old_name = ''
          this.category_name = '',
          this.$axios
            .get("http://127.0.0.1:8000/api/categories")
            .then((response) => {
              this.categories = response.data.data;
            });
        })
        .catch((err) => {
          console.log(err.response.data.errors)
          if (err.response.data.errors.name) {
            if (err.response.data.errors.name[0] != 'Введите какое-нибудь значение') {
              this.errors.name_change = err.response.data.errors.name;
            } else {
              this.errors.name_not_chosen = 'Категория для удаления не выбрана'
            }
          }

        });
    }
  },
};
</script>