<template>
  <div class="card w-100">
    <div class="card-body">
        <router-link :to="{ name: 'bookable', params: { id }}">
          <h5 class="card-title">{{ title }}</h5>
        </router-link>
        <p class="card-text">{{ description }}</p><!-- /.card-text -->
      </div><!-- /.card-body -->
    <h1></h1>
  </div>
</template>

<script>
export default {
  props: {
    title: String, 
    description: String, 
    id: Number
  },

  mounted() {
    console.log(this.title);

    // Uncomment to see why modifying prop is a bad idea.
    //　要するに親コンポーネントのデータを子のコンポーネントは書き換えてはだめということ。単方向のデータフロー
    // this.itemTitle = "New title";
  }
}
</script>