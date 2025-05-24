<script setup lang="ts">
const videoSrc = "/static/video/hero.mp4";
</script>
<template>
  <client-only>
    <video
      class="w-full h-auto"
      src="/static/video/hero.mp4"
      type="video/mp4"
      loop
      autoplay
      poster="https://www.telefonica.com/en/wp-content/uploads/sites/5/2023/09/10-Tips-to-improve-your-photos-e1696506323825.jpg?w=1200"
    >
      Váš prohlížeč nepodporuje HTML5 video.
    </video>
  </client-only>
</template>
