<template>
  <section>

    <div class="is-flex mb-4">
      <b-button class="mr-4" type="is-success" @click="handleAdd">Добавить</b-button>

      <b-field>
        <b-select v-model="perPage">
          <option
              v-for="value in perPageValues"
              :key="`per_page_${value}`"
              :value="value"
          >
            {{value}} на странице
          </option>
        </b-select>
      </b-field>
    </div>

    <b-table
        :data="data"
        :loading="loading"

        paginated
        backend-pagination
        :total="total"
        :per-page="perPage"
        @page-change="onPageChange"
        aria-next-label="Next page"
        aria-previous-label="Previous page"
        aria-page-label="Page"
        aria-current-label="Current page"

        backend-sorting
        :default-sort-direction="defaultSortOrder"
        :default-sort="[sortField, sortOrder]"
        @sort="onSort">

      <b-table-column field="title" label="Название" sortable v-slot="props">
        <router-link :to="`${appRoute.PRODUCT_CATEGORY}/${props.row.id}`">
          {{ props.row.title }}
        </router-link>
      </b-table-column>


      <b-table-column field="createdAt" label="Создана" sortable centered v-slot="props">
        {{ props.row.createdAt|date}}
      </b-table-column>

      <b-table-column label="Действия" v-slot="props">
        <div class="buttons">
          <b-button
              type="is-success"
              icon-left="pencil"
              size="is-small"
              @click="handleEdit(props.row)"
          />
          <b-button
              type="is-danger"
              icon-left="delete"
              size="is-small"
              @click="handleDelete(props.row)"
          />
        </div>
      </b-table-column>

    </b-table>

  </section>
</template>

<script>

import {appRoute, apiRoute, ERROR_DURATION} from "@/const";
import ProductCategoryForm from "@/modules/product-category/ProductCategoryForm";
import dateMixin from "@/mixins/date-mixin";


export default {
  name: 'ProductCategoryList',

  mixins: [dateMixin],

  data() {
    return {
      appRoute,
      perPageValues: [5, 10, 20, 50],

      data: [],
      total: 0,
      loading: false,
      sortField: 'title',
      sortOrder: 'asc',
      defaultSortOrder: 'asc',
      page: 1,
    };
  },

  computed: {
    perPage: {
      get: function () {
        return this.$store.state.perPage
      },
      set: async function (value) {
        this.$store.commit('setPerPage', value)
        await this.loadData()
      }
    }
  },


  async mounted() {
    await this.loadData();
  },

  methods: {
    async loadData() {
      this.loading = true;

      const params = [
        `page=${this.page}`,
        `perPage=${this.perPage}`,
        `order[${this.sortField}]=${this.sortOrder}`,
      ].join('&');

      try {
        const uri = `${apiRoute.PRODUCT_CATEGORY}?${params}`

        const {data} = await this.$axios.get(uri);

        this.total = data['hydra:totalItems'];
        this.data = data['hydra:member'];
      } catch (e) {
        console.error(e)

        this.$buefy.notification.open({
          duration: ERROR_DURATION,
          message: e.message,
          type: 'is-danger',
        });
      } finally {
        this.loading = false;
      }

    },

    async onPageChange(page) {
      this.page = page;
      await this.loadData();
    },

    async onSort(field, order) {
      this.sortField = field;
      this.sortOrder = order;
      await this.loadData();
    },

    async handleAdd() {
      await this.handleEdit({title: ''})
    },

    async handleEdit(item) {
      this.$buefy.modal.open({
        parent: this,
        component: ProductCategoryForm,
        props: { item },
        events: { close: this.handleCloseForm },
        hasModalCard: true,
      });
    },

    async handleCloseForm(item) {
      if (item) {
        await this.$router.push({
          name: 'ProductCategoryShow',
          params: {id: item.id}
        })
      }
    },

    async handleDelete({id, title}) {

      this.$buefy.dialog.confirm({
        title: "Удаление категории",
        message: `"${title}" будет удалена!!!`,
        cancelText: "Отмена",
        confirmText: "Удалить",
        hasIcon: true,
        type: "is-danger",
        onConfirm: async () => {
          try {
            const uri = `${apiRoute.PRODUCT_CATEGORY}/${id}`
            await this.$axios.delete(uri)
            await this.loadData()
            this.$buefy.notification.open({
              message: 'Категория удалена',
              type: 'is-success'
            })
          } catch (e) {
            console.error(e)
          }
        },
      });
    }
  },
};
</script>
