<script setup lang="ts">
const { locale, locales, t } = useI18n();
const switchLocalePath = useSwitchLocalePath();

const availableLocales = computed(() => {
	return locales.value;
});
</script>

<template>
	<div>
		<LayoutNavbar />
		<div class="relative flex w-full flex-col">
			<div class="absolute">
				<NuxtLink
					v-for="locale in availableLocales"
					:key="locale.code"
					:to="switchLocalePath(locale.code)"
				>
					<img
						:src="'/static/img/flags/' + locale.code + '.svg'"
						class="w-8 h-8"
						alt="Locale Flag"
					>
				</NuxtLink>
			</div>
		</div>
		<main>
			<NuxtPage />
		</main>
		<LayoutCookiesBar />
	</div>
</template>
