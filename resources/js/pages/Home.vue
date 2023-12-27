<template>
  <div class="container">
    <h1 class="last_posts">Последние публикации</h1>
    <div class="post_main_container">
      <div v-for="post of posts" class="post_div">
        <div class="user_post_info">
          <a :href="$router.resolve({ name: 'userPage', params: { id: post.user_id } }).href"><img class="post_user_avatar" :src="'/storage/profile_pics/'+post.user_avatar"></a> 
          <a class="post_user_name" :href="$router.resolve({ name: 'userPage', params: { id: post.user_id } }).href"><p>{{ post.user_name }}</p></a>
          <p class="post_user_time">{{ post.time }}</p>
        </div>
        <dt class="dt_not_modify" v-html="post.body"></dt>
      </div>
    </div>

  </div>
</template>

<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

@font-face {
  font-family: text;
  src: url(../../../storage/app/public/fonts/FiraSansCondensed-Regular.ttf);
}

@font-face {
  font-family: title;
  src: url(../../../storage/app/public/fonts/FiraSansCondensed-SemiBold.ttf);
}

.post_main_container{
  width: 431px;
  
}
.post_div{
  width: 100%;
  background-color: #EDF4E9;
  margin-bottom: 20px;
  padding: 19px 19px 0px 19px;
  border-radius: 20px;
  overflow: hidden;
  height: fit-content;

}
.post_user_avatar{
    width: 65px;
    height: 65px;
    border-radius: 50px;
  }
dt{
  width: 100%;
  line-height: 40px;
  font-size: 20px;
  font-family: "text";
  margin-bottom: -5px;
}
  dt img, iframe{
    width: calc(100% + 42px);
    margin-left: -23px; 
  }
  iframe{
    height: 240px;
  }

p,
input,
button,
a {

  font-family: text;

}

h1 {
  font-size: 48px;
  font-family: title;
}

.post_div {
  width: 500px;
}

.last_posts {
  margin-top: 4%;
  text-align: center;
}

.post_main_container {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  margin-top: 2%;
  margin-left: 6%;
  grid-column-gap: 20%;
}

.user_post_info {
  display: flex;
  font-size: 20px;
  column-gap: 10%;
}

.user_post_info p {
  color: black;
  margin-top: 5%;
}

.post_user_name {
  margin-top: 5%;
  white-space: nowrap;
}

.post_user_time {
  position: relative;
  left: 30%;
}

@media (max-width: 320px) {

  h1 {
    font-size: 15px;
  }

  .post_main_container {
    display: block;
    margin-left: 25%;
  }

  .post_div {
    width: 151px;
  }

  .post_user_avatar {
    width: 25px;
    height: 25px;
  }

  .post_user_name {
    font-size: 10px;
  }

  .post_user_time {
    top: 0.2vh;
    left: 1%;
    font-size: 10px;
  }

  dt {
    font-size: 6px;
    line-height: 25px;
  }
  iframe{
    height: 100px;
  }

}

</style>

<script>
export default {
  name: "Home",
  data() {
    return {
      posts: []
    };
  },
  created() {
    if(IsLogged){
      this.$axios
      .get("http://127.0.0.1:8000/api/getpostsdash")
        .then((response) => {
          for (let i = 0; i < response.data.data.length; i++) {
            if(response.data.data[i] === null){
              response.data.data.splice(i,1)
            }
            
          }
          this.posts = response.data.data;
          console.log(this.posts)
      });
    } else{
      this.$axios
      .get("http://127.0.0.1:8000/api/getpostsall")
        .then((response) => {
          this.posts = response.data.data;
      });
    }
    
  },
  methods: {},
};
</script>