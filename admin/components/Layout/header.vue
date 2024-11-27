<script setup lang="ts">
import { StarIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';

const user = useSanctumUser();
const route = useRoute();
const quickAccessDialogShow = ref(false);

const props = defineProps({
	title: {
		type: String,
		required: true,
	},
	description: {
		type: String,
		required: false,
	},
	breadcrumbs: {
		type: Array,
		required: true,
		default: [],
	},
	actions: {
		type: Array,
		required: false,
		default: [],
	},
});

const quickAccessItem = ref({
  id: null,
  name: props.title,
  link: route.fullPath,
  target: null,
});

const isInQuickAccess = computed(() => {
	if (user.value) {
		return user.value.quick_access.some(item => item.link === route.fullPath);
	}
	return false;
});
function openQuickAccessDialog(searchForItem: boolean = false) {
  quickAccessDialogShow.value = true;
  if(searchForItem) {
    quickAccessItem.value = user.value.quick_access.find(item => item.link === route.fullPath);
  }
}
</script>

<template>
	<div
		class="py-6 pb-6 pr-8 pl-8 pb- bg-white rounded-lg shadow"
	>
		<LayoutBreadcrumbs
			:pages="breadcrumbs"
			class="mb-4"
		/>
		<div class="mt-2 md:flex md:items-center md:justify-between">
			<div class="min-w-0 flex-1">
				<h2 class="text-2xl/7 font-bold text-grayDark sm:truncate sm:text-3xl sm:tracking-tight">
					{{ title }}
				</h2>
			</div>
			<div
				class="mt-4 flex shrink-0 md:ml-4 md:mt-0"
			>
				<button
					v-if="isInQuickAccess"
					type="button"
					class="rounded-full px-2.5 py-2.5 text-sm font-semibold shadow-sm hover:shadow-md ring-1 ring-slate-200"
					@click="openQuickAccessDialog(true)"
				>
					<StarIcon
						class="size-5 text-yellow-600 fill-yellow-600"
						aria-hidden="true"
					/>
				</button>
				<button
					v-else
					type="button"
					class="rounded-full px-2.5 py-2.5 text-sm font-semibold shadow-sm hover:shadow-md ring-1 ring-slate-200"
					@click="openQuickAccessDialog(false)"
				>
					<StarIcon
						class="size-5 text-yellow-600"
						aria-hidden="true"
					/>
				</button>
				<div
					v-if="actions && actions.length"
					class="ml-4"
				>
					<button
						type="button"
						class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
					>
						Button text
					</button>
				</div>
			</div>
		</div>
		<QuickAccessDialog
			v-model:show="quickAccessDialogShow"
			v-model:form="quickAccessItem"
		/>
	</div>
</template>
