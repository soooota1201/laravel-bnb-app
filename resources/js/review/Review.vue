<template>
  <div>
    <success v-if="success">
      You've left a review, thank you very much!
    </success>
    <fatal-error v-if="error"></fatal-error>
    <div v-if="!success && !error" class="row">
      <!-- データの有無によって表示を変更する -->
      <div :class="[{'col-md-4': twoColumns}, {'d-none': oneColumn}]">
        <div class="card">
          <div class="card-body">
            <div v-if="loading">Loading ...</div>
            <div v-if="hasBooking">
              <p>
                Stayed at <router-link :to="{name: 'bookable', params: {id: booking.bookable.bookable_id}}">{{booking.bookable.title}}</router-link>
              </p>
              <p>
                From {{ booking.from }} to {{ booking.to }}
              </p>
            </div>
          </div><!-- /.card-body -->
        </div><!-- /.card -->
      </div>
      <div :class="[{'col-md-8': twoColumns}, {'col-md-12': oneColumn}]">
        <div v-if="loading">
          Loading ...
        </div>
        <div v-else>
          <div v-if="alreadyReviewed">
            <h3>You.ve already left a review for this booking!</h3>
          </div>
          <div v-else>
            <div class="form-group">
              <label class="text-muted">Select the star rating (1 is worst - 5 is best)</label><!-- /.text-muted -->
              <!-- :rating="review.rating"
              @rating:changed="review.rating = $event" の代わりにv-modelを使う-->
              <star-rating class="fa-lg" v-model="review.rating"></star-rating>
            </div><!-- /.form-group -->
            <div class="form-group">
              <label for="content" class="text-muted">Describe your experience with</label>
              <textarea 
              name="content" 
              cols="30" rows="10" 
              class="form-control" 
              v-model="review.content"
              :class="[{'is-invalid': errorFor('content')}]"
              ></textarea>
              <v-errors :errors="errorFor('content')"></v-errors>
            </div><!-- /.form-group -->

            <button class="btn btn-lg btn-primary btn-block" @click.prevent="submit" :disabled="sending">Submit</button><!-- /.btn btn-lg btn-primary btn- -->
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
import {is404, is422} from "./../shared/utils/response";
import validationErrors from "./../shared/mixins/validationErrors";
export default {
  mixins: [validationErrors],
  data() {
    return {
      review: {
        id: null,
        rating: 5,
        content: null
      },
      existingReview: null,
      loading: false,
      booking: null,
      error: false,
      sending: false,
      success: false
    }
  },
  async created() {
    this.review.id = this.$route.params.id;
    this.loading = true;
    //1. If review already exists (in reviews table by id)
    try {
      this.exsistingReview = (await axios.get(`/api/reviews/${this.review.id}`)).data.data;
    } catch(err) {
      if(is404(err)) {
        try {
          this.booking = (await axios.get(`/api/booking-by-review/${this.$route.params.id}`)).data.data;
        } catch(err) {
          this.error = !is404(err);
        }
      } else {
        this.error = true;
      }
    }
    this.loading = false;
  },
  computed: {
    alreadyReviewed() {
      return this.hasReview || !this.booking;
    },
    hasReview() {
      return this.existingReview !== null;
    },
    hasBooking() {
      return this.booking !== null;
    },
    oneColumn() {
      return !this.loading && this.alreadyReviewed
    },
    twoColumns() {
      return this.loading || !this.alreadyReviewed;
    }
  },
  methods: {
    //3. Store the review
    submit() {
      this.errors = null;
      this.sending = true;
      this.success = false;
      axios
        .post(`/api/reviews`, this.review)
        .then(response => {
          this.success = 201 === response.status;
        })
        .catch(err => {
          if(is422(err)) {
            const errors = err.response.data.errors;
            //idとratingでのエラーを出さないようにする。（apiではvalidationがかかっているけど）。ここは全然わからん。lodashプラグイン。
            if(errors["content"] && 1 === _.size(errors)) {
              this.errors = errors;
              return;
            }
          }
          this.error = true;
        })
        .then(() => (this.sending = false));
    },
  },
  // methods: {
  //   onRatingChanged(rating) {
  //     console.log(rating);
  //   }
  // }
}
</script>

<style scoped>
  .form-control.is-invalid ~ div > .invalid-feedback {
    display: block;
  }
</style>