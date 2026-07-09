<script setup lang="ts">
const instagramUrl = 'https://www.instagram.com/luxury_roses_praha/?hl=cs';
</script>

<template>
	<footer class="border-t border-brand-soft bg-brand-cream py-10">
		<div class="container-x flex flex-wrap items-center justify-between gap-5">
			<span class="text-[21px] font-bold" style="font-family: 'Rufina', serif">Luxury Roses</span>
			<div class="flex items-center gap-7">
				<a
					:href="instagramUrl"
					target="_blank"
					rel="noopener"
					class="text-[13px] font-semibold uppercase tracking-[0.14em] text-brand-ink no-underline transition-colors hover:text-brand"
				>Instagram</a>
				<span class="text-sm text-brand-muted">© 2026 Luxury Roses · Praha</span>
			</div>
		</div>
	</footer>
</template>
