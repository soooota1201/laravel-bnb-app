<template>
  <div>
    <h6 class="text-uppercase text-secondary font-weight-bolder">
      Check Availability
      <span v-if="noAvailability" class="text-danger">(NOT AVAILABLE)</span>
      <span v-if="hasAvailability" class="text-success">(AVAILABLE)</span>
    </h6>

    <!-- フォームを追加 -->
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="from">From</label>
        <input 
        type="text"
        name="from" 
        class="form-control form-control-sm" 
        placeholder="Start date"
        v-model="from"
        @keyup.enter="check" 
        :class="[{'is-invalid': this.errorFor('from')}]"
        >
        <v-errors :errors="errorFor('from')"></v-errors>
      </div><!-- /.form-group col-md-6 -->

      <div class="form-group col-md-6">
        <label for="to">To</label>
        <input 
        type="text"
        name="to" 
        class="form-control form-control-sm" 
        placeholder="End date"
        v-model="to"
        @keyup.enter="check" 
        :class="[{'is-invalid': this.errorFor('to')}]"
        >
        <v-errors :errors="errorFor('to')"></v-errors>
      </div><!-- /.form-group col-md-6 -->
    </div><!-- /.form-row -->

    <button class="btn btn-secondary btn-block" @click="check" type="submit" :disabled = "loading">Check</button>
  </div>
</template>

<script>
import {is422} from "./../shared/utils/response";
import validationErrors from "./../shared/mixins/validationErrors";
export default {
  mixins: [validationErrors],
  props: {
    bookableId: [String, Number],
  },
  data() {
    // inputのnameで指定している値をdataの返り値に指定
    return {
      from: this.$store.state.lastSearch.from,
      to: this.$store.state.lastSearch.to,
      loading: false,
      status: null,
    }
  },
  methods: {
    check() {
      this.loading = true;
      this.errors = null;

      this.$store.dispatch('setLastSearch', {
        from: this.from,
        to: this.to
      })

      //idをどう取得するか？親インスタンスから子インスタンスにデータを受け渡す必要がある。今回の場合はbookableインスタンスから受け渡す必要がある。
      axios.get(`/api/bookables/${this.bookableId}/availability?from=${this.from}&to=${this.to}`)
      .then(response => {
        this.status = response.status;
      })
      .catch(error => {
        if(is422(error)) {
          this.errors = error.response.data.errors;
        }
        this.status = error.response.status;
      })
      .then(() => this.loading = false);
    },
  },
  computed: {
    hasErrors() {
      return 422 == this.status && this.errors != null;
    },
    hasAvailability() {
      return 200 == this.status;
    },
    noAvailability() {
      return 404 == this.status;
    }
  },
}
</script>

<style scoped>
  label {
    font-size: .7rem;
    text-transform: uppercase;
    color: gray;
    font-weight: bolder;
  }
</style>