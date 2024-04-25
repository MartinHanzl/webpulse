<template>
  <div>
    <PageHeading title="Nástěnka" />
    <h1 class="font-bold">
      Web Pulse
    </h1>
    <p>
      Click
      <nuxt-link to="/about">
        here
      </nuxt-link>
      to go to the about page
    </p>
  </div>
</template>
<script setup>
import PageHeading from "~/components/layout/PageHeading.vue";
</script>
