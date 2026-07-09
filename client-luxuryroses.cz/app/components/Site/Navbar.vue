<script setup lang="ts">
const links = [
	{ href: '#sluzby', label: 'Služby' },
	{ href: '#galerie', label: 'Galerie' },
	{ href: '#kontakt', label: 'Kontakt' },
];
</script>

<template>
	<header
		class="sticky top-0 z-50 border-b border-brand-soft bg-white/95 backdrop-blur-md"
	>
		<div
			class="container-x flex min-h-[72px] flex-wrap items-center justify-between gap-x-6 gap-y-1.5 py-2.5"
		>
			<a href="#hero" class="text-brand-ink no-underline">
				<span
					class="whitespace-nowrap font-bold"
					style="font-family: 'Rufina', serif; font-size: clamp(20px, 2.8vw, 27px)"
				>Luxury Roses</span>
			</a>
			<nav class="flex flex-wrap items-center gap-x-[clamp(12px,2vw,32px)] gap-y-2">
				<a
					v-for="link in links"
					:key="link.href"
					:href="link.href"
					class="text-[clamp(11px,1.4vw,13px)] font-semibold uppercase tracking-[0.1em] text-brand-muted no-underline transition-colors hover:text-brand"
				>{{ link.label }}</a>
				<a
					href="#objednavka"
					class="whitespace-nowrap rounded-full bg-brand px-[clamp(16px,2.4vw,28px)] py-[clamp(9px,1.2vw,12px)] text-[clamp(11px,1.4vw,14px)] font-semibold uppercase tracking-[0.06em] text-white no-underline transition-colors hover:bg-brand-dark"
				>Objednat</a>
			</nav>
		</div>
	</header>
</template>
