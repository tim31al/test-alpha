<template>
<b-navbar class="container navbar">
  <template #brand>
    <b-navbar-item tag="router-link" :to="{ path: appRoute.HOME }">
      <img src="@/assets/logo.png" alt="Logo" style="max-height: 40px" />
    </b-navbar-item>
  </template>

  <template #start>
    <b-navbar-item tag="router-link" :to="{ path: appRoute.HOME }">
      Home
    </b-navbar-item>

    <b-navbar-item tag="router-link" :to="{ path: appRoute.PRODUCT_CATEGORY }">
      Категории товаров
    </b-navbar-item>

  </template>

</b-navbar>
</template>

<script>
import {appRoute} from "@/const";

export default {
  name: 'AppNavbar',

  data() {
    return {
      appRoute
    }
  }
}
</script>
