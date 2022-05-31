<template>
  <div id="app">
    <app-navbar />

    <div class="container mt-4">
      <router-view/>
    </div>

  </div>
</template>

<script>
import AppNavbar from "@/components/AppNavbar";
export default {
  components: {AppNavbar},
};
</script>
