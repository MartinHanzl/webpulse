<script setup lang="ts">
onMounted(() => {
	useHead({
		title: 'Nový kontakt',
	});
});
</script>

<template>
	<div>
		<BaseHeading
			title="Nový kontakt"
			:actions="[
				{ text: 'Oblíbené', type: 'favorite' },
				{ text: 'Odstranit', type: 'delete' },
				{ text: 'Uložit', type: 'save' },
			]"
			:breadcrumbs="[
				{ name: 'Kontakty', href: '/kontakty#kontakty', current: false },
				{ name: 'Nový kontakt', href: '#', current: true },
			]"
		/>
	</div>
</template>
