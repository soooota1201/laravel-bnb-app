<template>
  <div class="row">
    <div class="col-md-8 pb-4">
      <div class="card">
          <div class="card-body">
            <div v-if="!loading">
              <h2>{{ bookable.title }}</h2>
              <hr />
              <article>{{ bookable.description }}</article>
            </div><!-- /. -->
            <div class="v-else">loading....</div><!-- /.v-else -->
          </div><!-- /.card-body -->
        </div><!-- /.card -->
        <review-list :bookable-id="this.$route.params.id"></review-list>
      </div><!-- /.col-md-8 -->
    <div class="col-md-4 pb-4">
      <availability :bookable-id="this.$route.params.id"></availability>
    </div><!-- /.col-md-4 -->
  </div>
</template>

<script>
import Availability from "./Availability";
import ReviewList from "./ReviewList";
export default {
  // インポートして、使うコンポーネントを定義する
  components: {
    Availability,
    ReviewList
  },
  data() {
    return {
      bookable:null,
      loading: false
    }
  },
  created() {
    this.loading = true;
    console.log(this.$route.params.id);
    axios
    .get(`/api/bookables/${this.$route.params.id}`)
    .then(response => {
      this.bookable = response.data.data;
      this.loading = false;
    });
  }
}
</script>