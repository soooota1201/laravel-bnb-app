<template>
  <div style="padding: 1.25rem;">
    <h6 class="text-uppercase text-secondary font-weight-bolder pt-5">Review List</h6>
    <div v-if="loading">Loading...</div><!-- /. -->
    <div v-else>
      <div class="border-bottom d-none d-md-block" v-for="(review, index) in reviews" :key="index">
        <div class="row pt-4">
          <div class="col-md-6">Sota</div><!-- /.col-md-6 -->
          <div class="col-md-6 d-flex justify-content-end">
          <star-rating :value="review.rating" class="fa-lg"></star-rating>  
          </div><!-- /.col-md-6 -->
        </div><!-- /.row -->
        <div class="row">
          <div class="col-md-12">{{ review.created_at | fromNow }}</div><!-- /.col-md-12 -->
        </div><!-- /.row -->
        <div class="row pt-4 pb-4">
          <div class="col-md-12">
            {{ review.content }}
          </div><!-- /.col-md-12 -->
        </div><!-- /.row -->
      </div><!-- /. -->
    </div>
  </div>
</template>

<script>
export default {
  props: {
    bookableId: [String, Number],
  },
  data() {
    return {
      loading: false, 
      reviews: null,
    }
  },
  created() {
    this.loading = true,
    axios
    .get(`/api/bookables/${this.bookableId}/reviews`)
    .then(response => this.reviews = response.data.data)
    .then(() => (this.loading = false));
  }
}
</script>