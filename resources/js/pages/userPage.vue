<template>
  <div class="row">
    <a href="/updateinfo">Редактировать информацию
    </a>

    
    <div placeholder="Веедите текст поста" @keyup="image_delete($event)" @paste="link_ut($event)" id="img-from-local-storage" contenteditable="true">
      Веедите текст поста
    </div>
    <!-- <iframe src="https://www.youtube.com/embed/27szVGikYH4??showinfo=0&controls=0&modestbranding=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen width="560" height="315"
    ></iframe> -->
    <p>{{ errors.image }}</p>
    <input @change="save_img" ref="img" type='file' id="bannerImg">
    <select @change="onChangeCategory($event)" name="" id="lang_s">
      <option>Выберите категорию</option>
      <option v-for="category of categories" :value="category.name">{{ category.name }}</option>
  </select>
  <button @click="makePost">Создать пост</button>
  </div>
</template>

<style>
#img-from-local-storage{
  border: 1px solid black;
}
</style>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      imgs: [],
      img_names: [],
      categories: [],
      index: 0,
      category_name: '',
      errors: {
        image: null
      }
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
      localStorage.setItem('image', '');
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
      console.log(this.imgs)
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
    makePost(e){
      e.preventDefault()
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
          console.log(response.data)
        })
        .catch((err) => {
          

        });
    }

    
  },

  
};
</script>