<script setup lang="ts">
import { ChevronRightIcon, HomeIcon } from '@heroicons/vue/24/outline';

defineProps({
	pages: {
		type: Array,
		required: true,
		default: [] as [],
	},
});
</script>

<template>
	<nav
		class="flex"
		aria-label="Breadcrumb"
	>
		<ol
			role="list"
			class="flex items-center space-x-2"
		>
			<li>
				<div>
					<NuxtLink
						to="/"
						class="text-gray-400 hover:text-grayCustom"
					>
						<HomeIcon
							class="size-3 lg:size-4 shrink-0"
							aria-hidden="true"
						/>
						<span class="sr-only">Home</span>
					</NuxtLink>
				</div>
			</li>
			<li
				v-for="page in pages"
				:key="page.name"
			>
				<div class="flex items-center">
					<ChevronRightIcon
						class="size-3 lg:size-4 shrink-0 text-gray-400"
						aria-hidden="true"
					/>
					<NuxtLink
						:to="page.link"
						class="ml-2 text-xs lg:text-sm font-medium text-grayCustom hover:text-gray-700"
						:aria-current="page.current ? 'page' : undefined"
					>{{ page.name }}</NuxtLink>
				</div>
			</li>
		</ol>
	</nav>
</template>
