<template>
  <div class="row">

    <div v-for="post of posts">
      <dt class="dt_not_modify" v-html="post.body"></dt>
    </div>

  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      posts: []
    };
  },
  created() {
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
  },
  methods: {},
};
</script>