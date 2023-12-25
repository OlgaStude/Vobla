<template>
  <div class="row">

    <div v-for="post of posts" class="post_div">
     <a :href="$router.resolve({ name: 'userPage', params: { id: post.user_id } }).href"><img :src="'/storage/profile_pics/'+post.user_avatar" style="width: 300px;"></a> 
      <a :href="$router.resolve({ name: 'userPage', params: { id: post.user_id } }).href"><p>{{ post.user_name }}</p></a>
      <p>{{ post.time }}</p>
      <dt class="dt_not_modify" v-html="post.body"></dt>
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
          console.log(this.posts[0])
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