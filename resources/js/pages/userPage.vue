<template>
  <div class="row">
    <a v-if="user.users_id == page_owner.users_id" href="/updateinfo">Редактировать информацию
    </a>

  <div v-if="user.users_id == page_owner.users_id" id="create_posts_div">
    
    <div class="div_border" placeholder="Веедите текст поста" @keyup="image_delete($event)" @paste="link_ut($event)" id="img-from-local-storage" contenteditable="true">
      Веедите текст поста
    </div>
  <p>{{ errors.body }}</p>
  <!-- <iframe src="https://www.youtube.com/embed/27szVGikYH4??showinfo=0&controls=0&modestbranding=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen width="560" height="315"
    ></iframe> -->
    <p>{{ errors.image }}</p>
    <input @change="save_img" ref="img" type='file' id="bannerImg">
    <select @change="onChangeCategory($event)" name="" id="lang_s">
      <option>Выберите категорию</option>
      <option v-for="category of categories" :value="category.name">{{ category.name }}</option>
  </select>
  <p>{{ errors.category_name }}</p>
  <p>{{ success_message }}</p>
  <button @click="makePost">Создать пост</button>

    
  </div>
  <div v-for="post of posts">
    <select class="save_change_btn" @change="onChangeCategoryUpdate($event)" name="" id="lang_s">
      <option v-for="category of categories" :value="category.name">{{ category.name }}</option>
    </select>
    <dt class="dt_modify div_border" @keyup="image_delete_modify($event)" contenteditable="true" v-html="old_body"></dt>
    <dt class="dt_not_modify" v-html="post.body"></dt>
    <input class="save_change_btn" @change="save_img_modify($event, post.imgs)" ref="img_modify" type='file' id="modifyImg">
    <button class="save_change_btn" @click="save_change($event, post.id)">Сохранить</button>
    <button class="modify_btn" @click="modify_post($event, post.imgs, post.category_name)">Редактировать</button>
    <button class="save_change_btn" @click="cancel_modify($event)">Отменить</button>
    <button @click="delete_post($event, post.id)">Удалить</button>
  </div>
  </div>
</template>

<style>
.div_border{
  border: 1px solid black;
}
.save_change_btn{
  display: none;
}
.dt_modify{
  display: none;
}
.dt_not_modify{
  display: block;
}


</style>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      imgs: [],
      img_names: [],
      modify_imgs: [],
      modify_imgs_names: [],
      modify_new_imgs: [],
      categories: [],
      posts: [],
      index: 0,
      category_name: '',
      category_name_update: '',
      old_body: '',
      is_in_update: false,
      errors: {
        image: null,
        body: null,
        category_name: null,
        modify_image: null
      },
      success_message: '',
      user: [],
      page_owner: []
    };
  },
  created() {
    this.$axios
      .get("http://127.0.0.1:8000/api/categories")
        .then((response) => {
        console.log(response.data)
        this.categories = response.data.data;
        this.index = this.categories.length
      });
      this.$axios
      .post("http://127.0.0.1:8000/api/getpostsuserpage",
      {
          id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1)
        })
        .then((response) => {
        console.log(response.data.data)
        this.posts = response.data.data
      });
      localStorage.setItem('image', '');
      this.$axios
      .get("http://127.0.0.1:8000/api/userinfo")
      .then((response) => {
        this.user = response.data;

      });
      this.$axios
      .post("http://127.0.0.1:8000/api/otheruserinfo",
      {
          id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1)
        })
      .then((response) => {
        this.page_owner = response.data;

      });
  },
  methods: {
    save_img(){
      this.errors.image = ''

      if(this.imgs.length < 5){

      const image = this.$refs.img.files[0];
      this.img_index++;
      this.imgs.push(image)
    let reader = new FileReader();

    reader.addEventListener('load', () => {
      
      localStorage.setItem('image', reader.result);
    });

    if (image) {
      reader.readAsDataURL(image);
    }
    let newImage = document.createElement(
      'img',
    );
    const br = document.createElement(
      'br',
    );
    
    setTimeout(() => {
      newImage.src = localStorage.getItem('image');

    newImage.style.width = 300+'px'
    document.getElementById('img-from-local-storage').append(br)
    document.getElementById('img-from-local-storage').append(newImage)
    this.img_names.push(document.getElementById('img-from-local-storage').children[document.getElementById('img-from-local-storage').children.length - 1].src)
    localStorage.setItem('image', '');
  }, "1000");
    
}else{
this.errors.image = 'Превышен лимит фотографий в посте'
}
    },
    image_delete(e){

      this.img_names.forEach((name, i) => {
        if(!document.getElementById('img-from-local-storage').innerHTML.includes(name.slice(0, 300))){
          this.imgs.splice(i,1)
          this.img_names.splice(i,1)
        }
      
      });
      console.log(this.img_names)
    },
    link_ut(e){
      if((e.clipboardData).getData("text").includes('www.')){
        e.preventDefault()
        document.getElementById('img-from-local-storage').innerHTML
        let paste = (e.clipboardData).getData("text");
        paste = paste.replace('/watch?v=', '/embed/');
        if(paste.includes('&')){
          paste = paste.replace(paste.slice(paste.indexOf('&')), '')
        }
        const selection = window.getSelection();
        if (!selection.rangeCount) return;
        selection.deleteFromDocument();
        let iframe = document.createElement('iframe');
        iframe.src = paste
        selection.getRangeAt(0).insertNode(iframe);
        selection.collapseToEnd();
      }
    },
    onChangeCategory(e) {
      if (e.target.value != 'Выберите категорию') {
        this.category_name = e.target.value 
      } else {
        this.category_name = ''
      }
    },
    onChangeCategoryUpdate(e) {
        this.category_name_update = e.target.value 
    },
    makePost(e){
      e.preventDefault()
      this.success_message = ''
      this.errors = {
        body: null,
        category_name: null
      }
      let body = document.getElementById('img-from-local-storage').innerHTML
      this.$axios.post("http://127.0.0.1:8000/api/makepost",
        {
          body: body,
          category_name: this.category_name,
          imgs: this.imgs
        },
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
      )
        .then((response) => {
          document.getElementById('img-from-local-storage').innerHTML = 'Введите текст поста'
          this.success_message = 'Пост был создан'
          this.$axios
      .post("http://127.0.0.1:8000/api/getpostsuserpage",
      {
          id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1)
        })
        .then((response) => {
        console.log(response.data.data)
        this.posts = response.data.data
      });
        })
        .catch((err) => {
          if (err.response.data.errors.body) {
            this.errors.body = err.response.data.errors.body[0];
          }
          if (err.response.data.errors.category_name) {
            this.errors.category_name = err.response.data.errors.category_name[0];
          }

        });
    },
    delete_post(e, id){
      e.preventDefault()
      this.success_message_change = ''
      this.$axios.post("http://127.0.0.1:8000/api/deletepost",
        {
          id: id
        }
      )
        .then((response) => {
          this.$axios
      .post("http://127.0.0.1:8000/api/getpostsuserpage",
      {
          id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1)
        })
        .then((response) => {
        console.log(response.data.data)
        this.posts = response.data.data
      });
        })
    },
    modify_post(e, imgs, category_name){
      document.querySelectorAll('.save_change_btn').forEach(el => {
        el.style.display = 'none'
      })
      document.querySelectorAll('.dt_modify').forEach(el => {
        el.style.display = 'none'
      })
      document.querySelectorAll('.modify_btn').forEach(el => {
        el.style.display = 'inline-block'
      })
      document.querySelectorAll('.dt_not_modify').forEach(el => {
        el.style.display = 'block'
      })
      this.modify_imgs = imgs
      this.modify_new_imgs = []
      this.category_name_update = category_name
      this.old_body = e.target.previousElementSibling.previousElementSibling.previousElementSibling.innerHTML
      e.target.style.display = 'none'
      e.target.nextElementSibling.style.display = 'inline-block'
      e.target.previousElementSibling.style.display = 'inline-block'
      e.target.previousElementSibling.previousElementSibling.style.display = 'inline-block'
      e.target.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.style.display = 'inline-block'
      e.target.previousElementSibling.previousElementSibling.previousElementSibling.previousElementSibling.style.display = 'block'
      e.target.previousElementSibling.previousElementSibling.previousElementSibling.style.display = 'none'

    },
    cancel_modify(e){
      document.querySelectorAll('.save_change_btn').forEach(el => {
        el.style.display = 'none'
      })
      document.querySelectorAll('.dt_modify').forEach(el => {
        el.style.display = 'none'
      })
      document.querySelectorAll('.modify_btn').forEach(el => {
        el.style.display = 'inline-block'
      })
      document.querySelectorAll('.dt_not_modify').forEach(el => {
        el.style.display = 'block'
      })
      this.modify_imgs = []
      this.modify_new_imgs = []
      this.category_name_update = ''
      this.old_body = ''
    },
    image_delete_modify(e){
      this.modify_imgs.forEach((name, i) => {
        if(!e.target.innerHTML.includes(name)){
          this.modify_imgs.splice(i,1)
        }
      });
      this.modify_imgs_names.forEach((name, i) => {
        if(!e.target.innerHTML.includes(name)){
          this.modify_imgs_names.splice(i,1)
        }
      });
      console.log(this.modify_imgs)
      console.log(this.modify_imgs_names)
    },
    save_img_modify(e, imgs){
      if(this.modify_new_imgs.length + this.modify_imgs.length < 5){

        const image = e.target.files[0];
        this.modify_new_imgs.push(image)
        let reader = new FileReader();

        reader.addEventListener('load', () => {

          localStorage.setItem('new_image', reader.result);
        });

        if (image) {
          reader.readAsDataURL(image);
        }
        let newImage = document.createElement(
          'img',
        );
        const br = document.createElement(
          'br',
        );

        setTimeout(() => {
          newImage.src = localStorage.getItem('new_image');

          newImage.style.width = 300+'px'
          e.target.previousElementSibling.previousElementSibling.append(br)
          e.target.previousElementSibling.previousElementSibling.append(newImage)
          this.modify_imgs_names.push(e.target.previousElementSibling.previousElementSibling.children[e.target.previousElementSibling.children.length - 1].src)
          localStorage.setItem('new_image', '');
          console.log(this.modify_imgs_names)
        }, "1000");

        }else{
          this.errors.modify_image = 'Превышен лимит фотографий в посте'
        }
    },
    save_change(e, id){
      e.preventDefault()
      this.$axios.post("http://127.0.0.1:8000/api/updatepost",
        {
          body: e.target.previousElementSibling.previousElementSibling.previousElementSibling.innerHTML,
          category_name: this.category_name_update,
          imgs: this.modify_new_imgs,
          old_imgs: this.modify_imgs,
          id: id
        },
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
      )
        .then((response) => {
          console.log(response.data)
          this.$axios
      .post("http://127.0.0.1:8000/api/getpostsuserpage",
      {
          id: window.location.href.substring(window.location.href.lastIndexOf('/') + 1)
        })
        .then((response) => {
        console.log(response.data.data)
        this.posts = response.data.data
      });
      this.cancel_modify()
        })
        .catch((err) => {
          if (err.response.data.errors.body) {
            this.errors.body = err.response.data.errors.body[0];
          }
          if (err.response.data.errors.category_name) {
            this.errors.category_name = err.response.data.errors.category_name[0];
          }

        });
    }

    
  },

  
};
</script>