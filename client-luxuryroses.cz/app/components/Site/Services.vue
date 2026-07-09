<script setup lang="ts">
const services = [
	{
		img: '/images/sluzba-1.webp',
		title: 'Kytice na míru',
		text: 'Řezané květiny denně čerstvé, uvázané podle příležitosti i rozpočtu.',
	},
	{
		img: '/images/sluzba-2.webp',
		title: 'Svatební floristika',
		text: 'Od svatební kytice po výzdobu celého dne. Začínáme konzultací u kávy.',
	},
	{
		img: '/images/sluzba-3.webp',
		title: 'Pokojové rostliny',
		text: 'Zdravé rostliny, které u vás doma vydrží. Poradíme s výběrem i péčí.',
	},
	{
		img: '/images/sluzba-4.webp',
		title: 'Rozvoz květin',
		text: 'Doručíme po Praze ještě týž den — čerstvé a s věnováním.',
	},
];
</script>

<template>
	<section id="sluzby" class="section bg-white">
		<div class="container-x">
			<div class="reveal mx-auto mb-[72px] max-w-[640px] text-center">
				<span class="text-[13px] font-semibold uppercase tracking-[0.24em] text-brand-muted">
					Služby
				</span>
				<h2 class="mt-[18px] text-[clamp(34px,4vw,50px)] leading-[1.15]">
					Od jedné růže<br>po celou svatbu
				</h2>
			</div>
			<div class="grid grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-4">
				<div
					v-for="service in services"
					:key="service.title"
					class="reveal flex flex-col"
				>
					<div class="aspect-[4/5] w-full overflow-hidden">
						<NuxtImg
							:src="service.img"
							:alt="service.title"
							class="size-full object-cover"
							loading="lazy"
						/>
					</div>
					<div
						class="relative mx-4 -mt-16 flex flex-col gap-2.5 bg-white px-[22px] py-[26px] text-center shadow-[0_14px_36px_rgba(0,0,0,0.08)] transition-transform duration-300 hover:-translate-y-1"
					>
						<h3 class="m-0 text-[22px]">{{ service.title }}</h3>
						<p class="m-0 text-[15px] leading-[1.6] text-brand-muted [text-wrap:pretty]">
							{{ service.text }}
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
