<script setup lang="ts">
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid';

defineProps({
	title: {
		type: String,
		required: true,
		default: '' as string,
	},
	data: {
		type: Array,
		required: true,
		default: [] as [],
	},
	multiple: {
		type: Array,
		required: true,
		default: [] as [],
	},
	slug: {
		type: String,
		required: true,
		default: '' as string,
	},
	type: {
		type: String,
		required: true,
		default: '' as string,
	},
});

const emit = defineEmits(['update-filters']);
</script>

<template>
	<div class="w-full">
		<Popover
			v-slot="{ open }"
			class="relative"
		>
			<PopoverButton
				class="w-full group inline-flex items-center justify-between bg-white ring-1 ring-inset ring-grayLight hover:bg-gray-50 focus-visible:outline-grayLight text-grayDark px-3.5 py-2.5 text-sm shadow-sm rounded-md"
			>
				<span>{{ title }}</span>
				<ChevronDownIcon
					class="-mr-1 ml-2 h-5 w-5 text-primaryCustom hover:text-primaryLight"
					aria-hidden="true"
				/>
			</PopoverButton>

			<transition
				enter-active-class="transition duration-200 ease-out"
				enter-from-class="translate-y-1 opacity-0"
				enter-to-class="translate-y-0 opacity-100"
				leave-active-class="transition duration-150 ease-in"
				leave-from-class="translate-y-0 opacity-100"
				leave-to-class="translate-y-1 opacity-0"
			>
				<PopoverPanel
					class="absolute left-80 z-10 mt-3 w-screen max-w-sm -translate-x-1/2 transform px-4 sm:px-0 lg:max-w-3xl"
				>
					<div class="overflow-hidden rounded-lg shadow-lg ring-1 ring-black/5">
						<div class="relative grid gap-4 bg-white p-7 lg:grid-cols-3">
							<div
								v-for="(filter, key) in data"
								:key="key"
							>
								<BaseFormCheckbox
									v-if="multiple === true"
									:name="filter.name"
									:label="filter.name"
									:model="filter.id"
									:type="'badge'"
									:color="filter.color"
									@change="emit('update-filters', { slug: slug, value: filter.id })"
								/>
							</div>
						</div>
					</div>
				</PopoverPanel>
			</transition>
		</Popover>
	</div>
</template>
