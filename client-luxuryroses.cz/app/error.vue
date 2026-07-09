<script setup lang="ts">
import type { NuxtError } from '#app';

defineProps({
	error: Object as () => NuxtError,
});
</script>

<template>
	<div class="demo-root flex min-h-screen flex-col" data-demo="luxuryroses">
		<div class="container-x flex w-full flex-1 flex-col items-center justify-center py-24 text-center">
			<span class="text-6xl">✿</span>
			<h1 class="mt-6 text-4xl">Stránka nenalezena</h1>
			<p class="mt-4 max-w-md text-brand-muted">
				Zdá se, že tahle kytice už někomu odešla. Vraťme se zpátky do ateliéru.
			</p>
			<button
				type="button"
				class="mt-8 cursor-pointer rounded-full border-none bg-brand px-9 py-4 text-sm font-semibold uppercase tracking-wider text-white transition-colors hover:bg-brand-dark"
				@click="clearError({ redirect: '/' })"
			>
				Zpět na úvod
			</button>
		</div>
	</div>
</template>
