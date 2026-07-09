<template>
	<section id="kontakt" class="section bg-brand-soft/45">
		<div class="container-x grid grid-cols-1 items-center gap-12 lg:grid-cols-[1fr_1.2fr] lg:gap-[72px]">
			<div class="reveal reveal-left">
				<div class="mb-[22px] flex items-center gap-3.5">
					<span class="inline-block h-px w-9 bg-brand-ink" />
					<span class="text-[13px] font-semibold uppercase tracking-[0.22em] text-brand-muted">
						Kde nás najdete
					</span>
				</div>
				<h2 class="m-0 mb-[22px] text-[clamp(34px,4vw,50px)] leading-[1.15]">
					Zastavte se na kus řeči — a kytku
				</h2>
				<p class="m-0 mb-9 max-w-[44ch] text-[17px] leading-[1.7] text-brand-muted [text-wrap:pretty]">
					Ateliér najdete kousek od centra. Přijďte si vybrat z denní nabídky, nebo si kytici
					objednejte předem a jen si ji vyzvedněte.
				</p>
				<a
					href="#objednavka"
					class="inline-block rounded-full bg-brand px-9 py-4 text-sm font-semibold uppercase tracking-[0.1em] text-white no-underline transition-colors hover:bg-brand-dark"
				>Objednat předem</a>
			</div>
			<div class="reveal reveal-right relative pb-12">
				<div class="aspect-[16/10] w-full overflow-hidden">
					<iframe
						title="Mapa — Luxury Roses, Zenklova 165/74, Praha 8"
						src="https://www.google.com/maps?q=Zenklova%20165%2F74%20Praha%208&output=embed"
						class="size-full border-0"
						loading="lazy"
						referrerpolicy="no-referrer-when-downgrade"
					/>
				</div>
				<div
					class="absolute bottom-0 right-4 flex max-w-[320px] flex-col gap-4 bg-white p-[30px_34px] shadow-[0_20px_50px_rgba(0,0,0,0.12)] sm:right-8"
				>
					<div>
						<p class="m-0 text-xs font-semibold uppercase tracking-[0.18em] text-brand-pop">
							Adresa
						</p>
						<p class="mt-1.5 text-base">Zenklova 165/74, 180 00 Praha 8</p>
					</div>
					<div>
						<p class="m-0 text-xs font-semibold uppercase tracking-[0.18em] text-brand-pop">
							Otevírací doba
						</p>
						<p class="mt-1.5 text-base leading-[1.6]">Po–NE 9:00 – 20:00</p>
					</div>
					<div>
						<p class="m-0 text-xs font-semibold uppercase tracking-[0.18em] text-brand-pop">
							Telefon
						</p>
						<p class="mt-1.5 text-base">
							<a href="tel:+420777837518" class="text-brand-ink no-underline hover:text-brand">
								+420 777 837 518
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
