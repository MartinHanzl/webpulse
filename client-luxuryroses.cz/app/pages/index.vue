<script setup lang="ts">
import { useHead } from '#app';
import { useScrollReveal } from '~/../app/composables/useScrollReveal';

definePageMeta({ layout: false });

const description =
	'Luxury Roses — květinový ateliér v Praze. Kytice vázané na míru, svatební floristika, pokojové rostliny a rozvoz květin po celé Praze ještě týž den.';

useHead({
	title: 'Luxury Roses — květinový ateliér Praha',
	titleTemplate: '%s',
	meta: [
		{ name: 'description', content: description },
		{ property: 'og:title', content: 'Luxury Roses — květinový ateliér Praha' },
		{ property: 'og:description', content: description },
		{ property: 'og:type', content: 'website' },
		{ property: 'og:url', content: 'https://luxuryroses.cz' },
		{ name: 'twitter:card', content: 'summary_large_image' },
		{ name: 'twitter:title', content: 'Luxury Roses — květinový ateliér Praha' },
		{ name: 'twitter:description', content: description },
	],
});

useScrollReveal();
</script>

<template>
	<div class="demo-root scroll-smooth" data-demo="luxuryroses">
		<SiteNavbar />
		<main>
			<SiteHero />
			<SiteSeasonal />
			<SiteServices />
			<SiteGallery />
			<SiteContact />
			<SiteOrderForm />
		</main>
		<SiteFooter />
	</div>
</template>
