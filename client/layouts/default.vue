<script setup lang="ts">
</script>

<template>
  <div class="relative w-full h-screen bg-gradient-to-br from-sky-500 via-violet-600 to-fuchsia-500 animate-gradient">
    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
      <h1 class="text-white text-1xl md:text-3xl font-bold tracking-wide font-inter animate-pulse">
        404
      </h1>
    </div>
  </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

.font-inter {
  font-family: 'Inter', sans-serif;
}

@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.animate-gradient {
  background-size: 400% 400%;
  animation: gradient 15s ease infinite;
}

@keyframes fade-in {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 2s ease-out;
}
</style>
