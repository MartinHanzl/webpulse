<script setup lang="ts">
import { reactive, ref } from 'vue';

const form = reactive({
	name: '',
	phone: '',
	occasion: 'Kytice na míru',
	date: '',
	note: '',
});
const sent = ref(false);

const occasions = ['Kytice na míru', 'Svatba', 'Narozeniny', 'Pokojová rostlina', 'Jiné'];

function submit() {
	sent.value = true;
}
</script>

<template>
	<section id="objednavka" class="section bg-white">
		<div class="container-x max-w-[760px]">
			<div class="reveal mb-[52px] text-center">
				<span class="text-[13px] font-semibold uppercase tracking-[0.24em] text-brand-muted">
					Objednávka
				</span>
				<h2 class="mb-3.5 mt-[18px] text-[clamp(34px,4vw,50px)] leading-[1.15]">
					Napište nám, co má rozkvést
				</h2>
				<p class="m-0 text-[17px] text-brand-muted [text-wrap:pretty]">
					Ozveme se do pár hodin a domluvíme detaily. Rozvážíme po celé Praze.
				</p>
			</div>
			<div
				class="reveal border border-brand-soft bg-white p-6 shadow-[0_20px_50px_rgba(0,0,0,0.06)] sm:p-12"
			>
				<div class="grid grid-cols-1 gap-[22px] sm:grid-cols-2">
					<label class="flex flex-col gap-2 text-[13px] font-semibold uppercase tracking-[0.12em]">
						Jméno
						<input
							v-model="form.name"
							type="text"
							placeholder="Vaše jméno"
							class="border border-brand-soft bg-white px-4 py-3.5 text-base normal-case tracking-normal text-brand-ink outline-none focus:border-brand"
						>
					</label>
					<label class="flex flex-col gap-2 text-[13px] font-semibold uppercase tracking-[0.12em]">
						Telefon
						<input
							v-model="form.phone"
							type="tel"
							placeholder="+420"
							class="border border-brand-soft bg-white px-4 py-3.5 text-base normal-case tracking-normal text-brand-ink outline-none focus:border-brand"
						>
					</label>
					<label class="flex flex-col gap-2 text-[13px] font-semibold uppercase tracking-[0.12em]">
						Příležitost
						<select
							v-model="form.occasion"
							class="border border-brand-soft bg-white px-4 py-3.5 text-base normal-case tracking-normal text-brand-ink outline-none focus:border-brand"
						>
							<option v-for="o in occasions" :key="o">{{ o }}</option>
						</select>
					</label>
					<label class="flex flex-col gap-2 text-[13px] font-semibold uppercase tracking-[0.12em]">
						Kdy ji potřebujete
						<input
							v-model="form.date"
							type="date"
							class="border border-brand-soft bg-white px-4 py-[13px] text-base normal-case tracking-normal text-brand-ink outline-none focus:border-brand"
						>
					</label>
					<label
						class="flex flex-col gap-2 text-[13px] font-semibold uppercase tracking-[0.12em] sm:col-span-2"
					>
						Vaše představa
						<textarea
							v-model="form.note"
							rows="4"
							placeholder="Barvy, oblíbené květiny, rozpočet… cokoliv nám pomůže"
							class="resize-y border border-brand-soft bg-white px-4 py-3.5 text-base normal-case tracking-normal text-brand-ink outline-none focus:border-brand"
						/>
					</label>
				</div>
				<p v-if="sent" class="mt-[26px] text-center text-[17px] font-semibold text-brand-pop">
					Děkujeme! Ozveme se vám co nejdřív. ✿
				</p>
				<button
					v-else
					type="button"
					class="mt-[30px] w-full cursor-pointer rounded-full border-none bg-brand py-[17px] text-sm font-semibold uppercase tracking-[0.12em] text-white transition-colors hover:bg-brand-dark"
					@click="submit"
				>
					Odeslat poptávku
				</button>
			</div>
		</div>
	</section>
</template>
