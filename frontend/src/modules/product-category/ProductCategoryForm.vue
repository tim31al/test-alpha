<template>
  <form>
    <div class="modal-card" style="width: auto">
      <header class="modal-card-head">
        <p class="modal-card-title">
          {{ item.id ? "Редактирование категории товара" : "Добавить категорию товара" }}
        </p>
      </header>

      <section class="modal-card-body">

        <b-field label="Название категории">
          <b-input v-model="title" :minlength="minLength" :maxlength="maxLength"></b-input>
        </b-field>

      </section>

      <footer class="modal-card-foot">
        <b-button
            :disabled="isDisabled"
            type="is-success"
            icon-left="check"
            :loading="loading"
            @click="handleSubmit"
        >
          Сохранить
        </b-button>

        <b-button icon-left="close" @click="$emit('close')">
          Закрыть
        </b-button>

      </footer>
    </div>
  </form>
</template>

<script>

import {apiRoute, appMessage} from "@/const";

export default {
  name: 'ProductCategoryForm',

  props: {
    item: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      loading: false,
      title: '',
      minLength: 3,
      maxLength: 255
    }
  },

  created() {
    this.title = this.item.title
  },

  computed: {
    isDisabled() {
      return this.title.length < this.minLength ||
          this.title.length > this.maxLength
    }
  },

  methods: {
    async handleSubmit() {
      this.loading = true
      try {
        const edited = {
          title: this.title
        }

        let data

        if (this.item.id) {
          ({data} = await this.$axios.put(`${apiRoute.PRODUCT_CATEGORY}/${this.item.id}`, edited))
        } else {
          ({data} = await this.$axios.post(`${apiRoute.PRODUCT_CATEGORY}`, edited))
        }

        this.$buefy.notification.open({
          message: appMessage.SAVE_SUCCESS,
          type: 'is-success'
        })

        this.$emit('close', data)

      } catch (e) {
        console.error(e)
        this.$buefy.notification.open({
          message: appMessage.SAVE_ERROR,
          type: 'is-danger'
        })
      } finally {
        this.loading = false
      }
    }
  }


}
</script>
