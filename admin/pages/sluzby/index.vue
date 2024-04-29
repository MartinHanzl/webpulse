<template>
  <div>
    <PageHeading :page-heading-data="pageHeadingData[0]" />
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
const pageHeadingData = [
  {
    title: 'Služby',
    links: [
      {
        text: 'Služby',
        href: '/sluzby',
      },
    ],
  },
];
</script>
