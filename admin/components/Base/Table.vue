<script setup lang="ts">
import { ref } from 'vue';
import {
	BoltIcon,
	MagnifyingGlassIcon,
	TrashIcon,
	EyeIcon,
} from '@heroicons/vue/24/outline';
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/24/solid';

const route = useRoute();
const router = useRouter();
const showDeleteDialog = ref(false);
const deleteDialogItem = ref(null);

defineProps({
	items: {
		type: Array,
		required: true,
		default: [],
	},
	columns: {
		type: Array,
		required: true,
		default: [],
	},
	enums: {
		type: Object,
		required: false,
		default: {},
	},
	actions: {
		type: Array,
		required: false,
		default: [],
	},
	loading: {
		type: Boolean,
		required: false,
		default: false,
	},
	error: {
		type: Boolean,
		required: false,
		default: false,
	},
	singular: {
		type: String,
		required: false,
		default: '',
	},
	plural: {
		type: String,
		required: false,
		default: '',
	},
	query: {
		type: Object,
		required: false,
		default: {},
	},
});

const emit = defineEmits(['delete-item', 'update-sort', 'update-page', 'open-dialog']);
</script>

<template>
	<div class="px-4 sm:px-6 lg:px-8">
		<div class="flow-root">
			<div class="-mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
				<div class="inline-block min-w-full py-2 align-middle">
					<table class="min-w-full divide-y divide-grayLight">
						<thead>
							<tr>
								<th
									v-for="(column, key) in columns"
									:key="key"
									scope="col"
									:class="{
										'hidden md:table-cell': column.hidden,
										'py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-grayDark sm:pl-6 lg:pl-8': true,
										'cursor-pointer': column.sortable,
										[`w-[${column.width}px]`]: column.width,
									}"
									@click="column.sortable ? $emit('update-sort', column.key) : null"
								>
									<div class="flex items-center">
										<span>{{ column.name }}</span>
										<ChevronDownIcon
											v-if="column.sortable && query.orderBy === column.key && query.orderWay === 'asc'"
											class="size-4 text-grayCustom ml-2"
										/>
										<ChevronUpIcon
											v-if="column.sortable && query.orderBy === column.key && query.orderWay === 'desc'"
											class="size-4 text-grayCustom ml-2"
										/>
									</div>
								</th>
								<th
									scope="col"
									class="w-[150px] relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8"
								/>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-200">
							<tr
								v-for="(item, key) in items.data"
								v-if="!loading && !error && items.data && items.data.length"
								:key="key"
							>
								<td
									v-for="(column, index) in columns"
									:key="index"
									scope="col"
									:class="[column.hidden ? 'hidden md:table-cell' : '', `w-[${column.width}px] whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-grayCustom sm:pl-6 lg:pl-8`]"
								>
									<span v-if="column.type === 'text' || column.type === 'number'">
										{{ item[column.key] }}
									</span>
									<span v-else-if="column.type === 'enum'">
										{{ enums[column.key][item[column.key]] }}
									</span>
									<NuxtLink
										v-else-if="column.type === 'link'"
										:to="item[column.key]"
										:target="item[column.target]"
										class="text-primaryLight"
									>
										{{ item[column.key] }}
									</NuxtLink>
								</td>
								<td class="w-[150px] relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8 flex items-center justify-end">
									<span
										v-for="(action, key) in actions"
										:key="key"
									>
										<MagnifyingGlassIcon
											v-if="action.type === 'edit'"
											class="cursor-pointer size-5 text-primaryCustom hover:text-primaryLight"
											@click="router.push(`${route.fullPath}/${item.id}`)"
										/>
										<BoltIcon
											v-if="action.type === 'edit-dialog'"
											class="cursor-pointer size-5 text-warning hover:text-warningLight ml-4"
											@click="emit('open-dialog', item)"
										/>
										<TrashIcon
											v-if="action.type === 'delete'"
											class="cursor-pointer size-5 text-danger hover:text-dangerLight ml-4"
											@click="showDeleteDialog = true; deleteDialogItem = item"
										/>
									</span>
								</td>
							</tr>
							<tr v-else-if="!loading && error">
								<td
									:colspan="columns.length + 1"
									class="relative whitespace-nowrap py-8 pl-3 pr-4 text-center text-grayCustom text-sm font-semibold sm:pr-6 lg:pr-8"
								>
									{{ `${plural} se nepodařilo načíst` }}
								</td>
							</tr>
							<tr v-else-if="!loading && !error && items.data && items.data.length === 0">
								<td
									:colspan="columns.length + 1"
									class="relative whitespace-nowrap py-8 pl-3 pr-4 text-center text-grayCustom text-sm font-semibold sm:pr-6 lg:pr-8"
								>
									{{ `Nemáte žádné ${plural.toLowerCase()}` }}
								</td>
							</tr>
							<tr v-else-if="loading">
								<td
									:colspan="columns.length + 1"
									class="relative whitespace-nowrap py-8 pl-3 pr-4 text-center text-grayCustom text-sm font-semibold sm:pr-6 lg:pr-8"
								>
									{{ `${plural} se načítají` }}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<BasePagination
					v-if="!loading && !error && items.data && items.data.length"
					:page="items.currentPage"
					:per-page="items.perPage"
					:total="items.total"
					:last-page="items.lastPage"
					@update-page="emit('update-page', $event)"
				/>
				<BaseDialogDelete
					v-model:show="showDeleteDialog"
					v-model:item="deleteDialogItem"
					@delete-item="emit('delete-item', deleteDialogItem.id);showDeleteDialog = false"
				/>
			</div>
		</div>
	</div>
</template>
