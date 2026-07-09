<template>
	<section
		id="hero"
		class="grid min-h-[640px] grid-cols-1 bg-white md:grid-cols-2"
	>
		<div
			class="reveal reveal-left flex flex-col justify-center px-6 py-16 md:py-20 md:pl-[max(64px,calc((100vw-1320px)/2+20px))] md:pr-16"
		>
			<div class="mb-6 flex items-center gap-3.5">
				<span class="inline-block h-px w-9 bg-brand-ink" />
				<span class="text-[13px] font-semibold uppercase tracking-[0.22em] text-brand-muted">
					Květinový ateliér · Praha
				</span>
			</div>
			<h1 class="m-0 mb-6 text-[clamp(42px,4.5vw,62px)] leading-[1.12]">
				Květiny vázané srdcem, pro každý váš den
			</h1>
			<p class="m-0 mb-10 max-w-[46ch] text-lg leading-[1.7] text-brand-muted [text-wrap:pretty]">
				Každou kytici vážeme na míru — podle příležitosti, nálady i toho, komu ji nesete.
				Zastavte se u nás, vůně čerstvých květin vás přivítá už ve dveřích.
			</p>
			<div class="flex flex-wrap items-center gap-5">
				<a
					href="#kontakt"
					class="rounded-full bg-brand px-9 py-4 text-sm font-semibold uppercase tracking-[0.1em] text-white no-underline transition-colors hover:bg-brand-dark"
				>Navštívit ateliér</a>
				<a
					href="#galerie"
					class="border-b border-brand pb-1 text-sm font-semibold uppercase tracking-[0.1em] text-brand-ink no-underline transition-colors hover:text-brand"
				>Naše práce</a>
			</div>
		</div>
		<div class="reveal reveal-right relative min-h-[420px] md:min-h-[640px]">
			<NuxtImg
				src="/images/sluzba-4.webp"
				alt="Květinový ateliér Luxury Roses"
				class="absolute inset-0 size-full object-cover"
			/>
		</div>
	</section>
</template>
