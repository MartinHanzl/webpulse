<script setup lang="ts">
import { ref } from 'vue';
import {
	BoltIcon,
	MagnifyingGlassIcon,
	TrashIcon,
	ClipboardDocumentIcon,
} from '@heroicons/vue/24/outline';
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/24/solid';
import { useUserGroupStore } from '~/stores/userGroupStore';

const toast = useToast();

const user = useSanctumUser();
const userGroupStore = useUserGroupStore();

const route = useRoute();
const router = useRouter();
const showDeleteDialog = ref(false);
const deleteDialogItem = ref(null);

defineProps({
	items: {
		type: Array,
		required: true,
		default: [] as [],
	},
	columns: {
		type: Array,
		required: true,
		default: [] as [],
	},
	enums: {
		type: Object,
		required: false,
		default: null,
	},
	actions: {
		type: Array,
		required: false,
		default: [] as [],
	},
	loading: {
		type: Boolean,
		required: false,
		default: false as boolean,
	},
	error: {
		type: Boolean,
		required: false,
		default: false as boolean,
	},
	singular: {
		type: String,
		required: false,
		default: '' as string,
	},
	plural: {
		type: String,
		required: false,
		default: '' as string,
	},
	query: {
		type: Object,
		required: false,
		default: null,
	},
	slug: {
		type: String,
		required: false,
		default: '' as string,
	},
});

function canEdit(slug: string) {
	if (user && user.value && user.value.user_group_id && userGroupStore.userGroups) {
		const userGroup = userGroupStore.userGroups.find(group => group.id === user.value.user_group_id);
		if (userGroup && userGroup.permissions) {
			const currentPermissionSlug = userGroup.permissions.find(permission => permission.slug === slug);
			if (currentPermissionSlug && currentPermissionSlug.slug === slug && currentPermissionSlug.permissions.edit) {
				return true;
			}
		}
	}
	return false;
}

function canDelete(slug: string) {
	if (user && user.value && user.value.user_group_id && userGroupStore.userGroups) {
		const userGroup = userGroupStore.userGroups.find(group => group.id === user.value.user_group_id);
		if (userGroup && userGroup.permissions) {
			const currentPermissionSlug = userGroup.permissions.find(permission => permission.slug === slug);
			if (currentPermissionSlug && currentPermissionSlug.slug === slug && currentPermissionSlug.permissions.delete == true) {
				return true;
			}
		}
	}
	return false;
}

async function copyToClipboard(item, key) {
	console.log(item[key]);
	await navigator.clipboard.writeText(item[key]).then(() => {
		toast.add({
			title: 'Kopírováno',
			description: 'Zpráva byla úspěšně zkopírována do schránky.',
			color: 'green',
		});
	}).catch(() => {
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se zkopírovat zprávu do schránky.',
			color: 'red',
		});
	});
}

function redirect(itemId: number, action: object) {
	if (action.path && action.hash) {
		router.push(`${action.path}/${itemId}${action.hash}`);
	}
	else if (action.hash) {
		router.push(`${route.fullPath}/${itemId}${action.hash}`);
	}
	else if (action.path) {
		router.push(`${action.path}/${itemId}`);
	}
	else {
		router.push(`${route.fullPath}/${itemId}`);
	}
}

const emit = defineEmits(['delete-item', 'update-sort', 'update-page', 'open-dialog']);
</script>

<template>
	<div class="px-4 sm:px-6 lg:px-8">
		<div class="flow-root">
			<div class="-mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
				<div class="inline-block min-w-full py-2 pb-0 align-middle">
					<table class="min-w-full divide-y divide-grayLight">
						<thead>
							<tr>
								<th
									v-for="(column, key) in columns"
									:key="key"
									scope="col"
									:class="{
										'hidden md:table-cell': column.hidden,
										'py-2.5 lg:py-3.5 pl-2 lg:pl-8 pr-1.5 lg:pr-3 text-left text-xs lg:text-sm font-semibold text-grayDark': true,
										'cursor-pointer': column.sortable,
										[`w-[${column.width}px]`]: column.width,
									}"
									@click="column.sortable ? $emit('update-sort', column.key) : null"
								>
									<div class="flex items-center">
										<span>{{ column.name }}</span>
										<ChevronDownIcon
											v-if="query && column.sortable && query.orderBy === column.key && query.orderWay === 'asc'"
											class="size-3 lg:size-4 text-primaryCustom ml-2"
										/>
										<ChevronUpIcon
											v-if="query && column.sortable && query.orderBy === column.key && query.orderWay === 'desc'"
											class="size-3 lg:size-4 text-primaryCustom ml-2"
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
									:class="[column.hidden ? 'hidden md:table-cell' : '', `w-[${column.width}px] whitespace-nowrap py-2 lg:py-4 pl-2 lg:pl-8 pr-1.5 lg:pr-3 text-xs lg:text-sm font-medium text-grayCustom`]"
								>
									<span v-if="column.type === 'text' || column.type === 'number'">
										{{ item[column.key] ?? '-' }}
									</span>
									<span v-if="column.type === 'percent'">
										{{ item[column.key] ?? '-' }}%
									</span>
									<PropsBadge
										v-else-if="column.type === 'badge'"
										:color="item[column.colorKey]"
									>
										{{ item[column.key] }}
									</PropsBadge>
									<span v-else-if="column.type === 'enum'">
										{{ enums[column.key][item[column.key]] }}
									</span>
									<span v-else-if="column.type === 'date'">
										{{ new Date(item[column.key]).toLocaleDateString() }}
									</span>
									<span v-else-if="column.type === 'datetime'">
										{{ new Date(item[column.key]).toLocaleString() }}
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
								<td class="w-[150px] relative whitespace-nowrap py-2 lg:py-4 pl-1.5 lg:pl-3 pr-2 lg:pr-8 text-right text-xs lg:text-sm font-medium sm:pr-6 flex items-center justify-end">
									<span
										v-for="(action, index) in actions"
										:key="index"
									>
										<MagnifyingGlassIcon
											v-if="action.type === 'edit' && canEdit(slug) || action.type === 'edit' && slug === ''"
											class="cursor-pointer size-3 lg:size-5 text-primaryCustom hover:text-primaryLight ml-2 lg:ml-4"
											@click="redirect(item.id, action)"
										/>
										<ClipboardDocumentIcon
											v-if="action.type === 'copy'"
											class="cursor-pointer size-4 lg:size-5 text-secondary hover:text-secondaryLight ml-2 lg:ml-4"
											@click="copyToClipboard(item, action.key)"
										/>
										<BoltIcon
											v-if="action.type === 'edit-dialog' && canEdit(slug) || action.type === 'edit-dialog' && slug === ''"
											class="cursor-pointer size-4 lg:size-5 text-warning hover:text-warningLight ml-2 lg:ml-4"
											@click="emit('open-dialog', item)"
										/>
										<TrashIcon
											v-if="action.type === 'delete' && canDelete(slug) || action.type === 'delete' && slug === ''"
											class="cursor-pointer size-4 lg:size-5 text-danger hover:text-dangerLight ml-2 lg:ml-4"
											@click="showDeleteDialog = true; deleteDialogItem = item"
										/>
									</span>
								</td>
							</tr>
							<tr v-else-if="!loading && error">
								<td
									:colspan="columns.length + 1"
									class="relative whitespace-nowrap py-8 pl-3 pr-4 text-center text-grayCustom text-xs lg:text-sm font-semibold sm:pr-6 lg:pr-8"
								>
									{{ `${plural} se nepodařilo načíst` }}
								</td>
							</tr>
							<tr v-else-if="!loading && !error && items.data && items.data.length === 0">
								<td
									:colspan="columns.length + 1"
									class="relative whitespace-nowrap py-8 pl-3 pr-4 text-center text-grayCustom text-xs lg:text-sm font-semibold sm:pr-6 lg:pr-8"
								>
									{{ `Nemáte žádné ${plural.toLowerCase()}` }}
								</td>
							</tr>
							<tr v-else-if="loading">
								<td
									:colspan="columns.length + 1"
									class="relative whitespace-nowrap py-8 pl-3 pr-4 text-center text-grayCustom text-xs lg:text-sm font-semibold sm:pr-6 lg:pr-8"
								>
									{{ `${plural} se načítají` }}
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<BasePagination
					v-if="!loading && !error && items.data && items.data.length && query"
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
