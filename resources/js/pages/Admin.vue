<template>
  <div class="row">
    <input type="text" v-model="new_category" placeholder="Навая категория">
    <p>{{ errors.name[0] }}</p>
    <p>{{ success_message }}</p>
    <button @click="createCategory($event)">Создать</button>
    <select @change="onChangeCategory($event)" name="" id="lang_s">
        <option>Выберите категорию</option>
        <option v-for="category of categories" :value="category.name">{{ category.name }}</option>
    </select>
    <input type="text" v-model="category_name">
    <p>{{ errors.name_change[0] }}</p>
      <p>{{ errors.name_not_chosen }}</p>
    <p>{{ success_message_change }}</p>
      <button @click="updateCategory($event)">Создать</button>
    </div>
</template>

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
    }
  },
};
</script>