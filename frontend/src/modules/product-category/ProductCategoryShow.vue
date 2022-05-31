<template>
  <section>
    <div class="content">

      <h2>{{title}}</h2>
      <p>Дата создания: {{createdAt|date}}</p>
    </div>

    <div class="buttons mt-4">
      <b-button
        tag="router-link"
        :to="appRoute.PRODUCT_CATEGORY"
        type="is-info"
      >
        Вернуться к списку
      </b-button>
    </div>
  </section>
</template>

<script>
import {apiRoute, appRoute} from "@/const";
import dateMixin from "@/mixins/date-mixin";

export default {
  name: 'ProductCategoryShow',

  mixins: [dateMixin],

  data() {
    return {
      appRoute,
      title: '',
      createdAt: null
    }
  },

  async mounted() {
    try {
      const {id} = this.$route.params
      const uri = `${apiRoute.PRODUCT_CATEGORY}/${id}`
      const {data} = await this.$axios.get(uri)

      this.title = data.title
      this.createdAt = data.createdAt

    } catch (e) {
      console.error(e)
      await this.$router.push({
        name: 'NotFound',
        params: {0: this.$route.path}
      })
    }
  },
}
</script>
