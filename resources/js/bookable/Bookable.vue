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
      <availability
      :bookable-id="this.$route.params.id" 
      @availability="checkPrice($event)"
      class="mb-4"
      ></availability>
      <transition name="fade">
        <price-breakdown v-if="price" :price="price" class="mb-4"></price-breakdown>
      </transition>
      <transition name="fade">
        <button class="btn btn-outline-secondary btn-block" v-if="price">Book Now</button>
      </transition>
    </div><!-- /.col-md-4 -->
  </div>
</template>

<script>
import Availability from "./Availability";
import ReviewList from "./ReviewList";
import PriceBreakdown from "./PriceBreakdown";
import { mapState } from 'vuex';
export default {
  // インポートして、使うコンポーネントを定義する
  components: {
    Availability,
    ReviewList,
    PriceBreakdown
  },
  data() {
    return {
      bookable: null,
      loading: false,
      price: null
    }
  },
  created() {
    this.loading = true;
    axios
    .get(`/api/bookables/${this.$route.params.id}`)
    .then(response => {
      this.bookable = response.data.data;
      this.loading = false;
    });
  },
  computed: 
    mapState({
      lastSearch: "lastSearch"
    })
  ,
  methods: {
    async checkPrice(hasAvailability) {
      if(!hasAvailability) {
        this.price = null;
        return;
      }
      try {
        this.price = (await axios.get(`/api/bookables/${this.bookable.id}/price?from=${this.lastSearch.from}&to=${this.lastSearch.to}`)).data.data
      } catch {
        this.price = null;
      }
    }
  }
}
</script>