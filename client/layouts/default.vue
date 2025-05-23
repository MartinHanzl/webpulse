<script setup lang="ts">
</script>

<template>
	<div>
		<div class="relative flex w-full flex-col">
			<main class="flex-auto">
				<slot />
			</main>
		</div>
		<LayoutCookiesBar />
	</div>
</template>
