<script setup lang="ts">
const instagramUrl = 'https://www.instagram.com/luxury_roses_praha/?hl=cs';

const tiles = [
	{ img: '/images/sluzba-4.webp', alt: 'Čerstvé kytice v ateliéru' },
	{ img: '/images/sluzba-2.webp', alt: 'Svatební vazba' },
	{ img: '/images/sluzba-3.webp', alt: 'Pokojové rostliny' },
	{ img: '/images/sluzba-1.webp', alt: 'Kytice na míru' },
	{ img: '/images/sluzba-4.webp', alt: 'Kytice na míru' },
];
</script>

<template>
	<section id="galerie" class="section bg-brand-cream">
		<div class="container-x">
			<div class="reveal mx-auto mb-14 max-w-[640px] text-center">
				<span class="text-[13px] font-semibold uppercase tracking-[0.24em] text-brand-muted">
					Naše práce
				</span>
				<h2 class="mt-[18px] text-[clamp(34px,4vw,50px)] leading-[1.15]">
					Kytice, které od nás odešly
				</h2>
			</div>
			<div class="grid grid-cols-2 gap-[18px] md:grid-cols-3">
				<!-- First tile with overlay card -->
				<div class="reveal relative aspect-square overflow-hidden">
					<NuxtImg
						src="/images/sluzba-1.webp"
						alt="Sezónní kytice"
						class="absolute inset-0 size-full object-cover"
						loading="lazy"
					/>
					<div
						class="pointer-events-none absolute inset-6 flex flex-col items-center justify-center gap-2.5 bg-white/95 p-5 text-center shadow-[0_6px_24px_rgba(0,0,0,0.06)]"
					>
						<h3 class="m-0 text-2xl">Sezónní kytice</h3>
						<p class="m-0 text-[15px] text-brand-muted">Co právě kvete, to vážeme</p>
					</div>
				</div>
				<div
					v-for="(tile, i) in tiles"
					:key="i"
					class="reveal aspect-square overflow-hidden"
				>
					<NuxtImg
						:src="tile.img"
						:alt="tile.alt"
						class="size-full object-cover"
						loading="lazy"
					/>
				</div>
			</div>
			<div class="reveal mt-11 text-center">
				<a
					:href="instagramUrl"
					target="_blank"
					rel="noopener"
					class="inline-flex items-center gap-2.5 border-b border-brand pb-1 text-sm font-semibold uppercase tracking-[0.12em] text-brand-ink no-underline transition-colors hover:text-brand"
				>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<rect x="2" y="2" width="20" height="20" rx="5" />
						<circle cx="12" cy="12" r="4.5" />
						<circle cx="17.5" cy="6.5" r="1.3" fill="currentColor" stroke="none" />
					</svg>
					Sledujte nás na Instagramu
				</a>
			</div>
		</div>
	</section>
</template>
