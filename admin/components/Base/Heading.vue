<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import {
	StarIcon,
	ChevronRightIcon,
	HomeIcon,
} from '@heroicons/vue/24/outline';

const route = useRoute();
const router = useRouter();

function addNew() {
	router.push('/kontakty/pridat');
}

const pages = [
	{ name: 'Projects', href: '#', current: false },
	{ name: 'Project Nero', href: '#', current: true },
];

defineProps({
	title: {
		type: String,
		required: true,
	},
	actions: {
		type: Array,
		required: false,
		default: [],
	},
	breadcrumbs: {
		type: Array,
		required: true,
		default: [],
	},
});
</script>

<template>
	<div class="mb-8">
		<nav
			class="flex mb-4"
			aria-label="Breadcrumb"
		>
			<ol
				role="list"
				class="flex items-center space-x-4"
			>
				<li>
					<div>
						<NuxtLink
							to="/"
							class="text-gray-400 hover:text-gray-500"
						>
							<HomeIcon
								class="h-5 w-5 flex-shrink-0"
								aria-hidden="true"
							/>
							<span class="sr-only">Domů</span>
						</NuxtLink>
					</div>
				</li>
				<li
					v-for="(link, index) in breadcrumbs"
					:key="index"
				>
					<div class="flex items-center">
						<ChevronRightIcon
							class="h-5 w-5 flex-shrink-0 text-gray-400"
							aria-hidden="true"
						/>
						<NuxtLink
							:to="link.href"
							class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
							:aria-current="link.current ? 'page' : undefined"
						>
							{{ link.name }}
						</NuxtLink>
					</div>
				</li>
			</ol>
		</nav>
		<div class="flex justify-between">
			<h1 class="text-3xl font-bold text-primary-dark">
				{{ title }}
			</h1>
			<div
				v-if="actions && actions.length"
				class="flex"
			>
				<div
					v-for="(action, index) in actions"
					:key="index"
					class="ml-4"
				>
					<button
						v-if="action.type === 'favorite'"
						type="button"
						class="inline-flex items-center gap-x-2 rounded-md bg-primary-light px-3.5 py-2.5 text-sm font-semibold text-primary shadow-sm hover:bg-primary-light-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
					>
						<StarIcon
							class="-ml-0.5 h-5 w-5 text-yellow-600"
							aria-hidden="true"
						/>
						{{ action.text }}
					</button>
					<button
						v-else-if="action.type === 'add'"
						type="button"
						class="rounded-md bg-primary px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
						@click="addNew"
					>
						{{ action.text }}
					</button>
					<button
						v-else-if="action.type === 'export'"
						type="button"
						class="rounded-md bg-primary px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
					>
						{{ action.text }}
					</button>
					<button
						v-else-if="action.type === 'delete'"
						type="button"
						class="rounded-md bg-danger px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-danger-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-danger"
					>
						{{ action.text }}
					</button>
					<button
						v-else-if="action.type === 'save'"
						type="button"
						class="rounded-md bg-success px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-success-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-success"
					>
						{{ action.text }}
					</button>
				</div>
			</div>
		</div>
	</div>
</template>
