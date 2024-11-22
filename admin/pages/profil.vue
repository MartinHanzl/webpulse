<script setup lang="ts">
const breadcrumbs = [
  {
    name: 'Profil',
    link: '/profil',
    current: true,
  },
];
</script>

<template>
	<div>
    <LayoutHeader
        title="Profil"
        :breadcrumbs="breadcrumbs"
    />
	</div>
</template>
