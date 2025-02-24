<script setup lang="ts">
import { StarIcon, PrinterIcon, ServerIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';
import { useUserGroupStore } from '~/stores/userGroupStore';

const userGroupStore = useUserGroupStore();

const user = useSanctumUser();
const route = useRoute();
const router = useRouter();
const quickAccessDialogShow = ref(false);

const props = defineProps({
	title: {
		type: String,
		required: true as boolean,
	},
	description: {
		type: String,
		required: false as boolean,
	},
	breadcrumbs: {
		type: Array,
		required: true,
		default: [] as [],
	},
	actions: {
		type: Array,
		required: false,
		default: [] as [],
	},
	slug: {
		type: String,
		required: false,
		default: '' as string | null,
	},
	links: {
		type: Array,
		required: false,
		default: [] as [],
	},
	filters: {
		type: Array,
		required: false,
		default: [] as [],
	},
	filtersQuery: {
		type: Object,
		required: false,
		default: null,
	},
});

const emit = defineEmits(['save', 'update-filters', 'add-dialog', 'filter-dialog']);
const quickAccessItem = ref({
	id: null,
	name: props.title,
	link: route.fullPath,
	target: null,
});

function canEdit(slug: string) {
	if (user && user.value && user.value.user_group_id && userGroupStore.userGroups) {
		const userGroup = userGroupStore.userGroups.find(group => group.id === user.value.user_group_id);
		if (userGroup && userGroup.permissions) {
			const currentPermissionSlug = userGroup.permissions.find(permission => permission.slug === slug);
			if (currentPermissionSlug && currentPermissionSlug.slug === slug && currentPermissionSlug.permissions.edit == true) {
				return true;
			}
		}
	}
	return false;
}

const isInQuickAccess = computed(() => {
	if (user.value) {
		return user.value.quick_access.some(item => item.link === route.fullPath);
	}
	return false;
});
function openQuickAccessDialog(searchForItem: boolean = false) {
	quickAccessDialogShow.value = true;
	if (searchForItem) {
		quickAccessItem.value = user.value.quick_access.find(item => item.link === route.fullPath);
	}
}

function print() {
	window.print();
}

const emitUpdateFilters = () => {
	emit('update-filters', props.filtersQuery);
};
</script>

<template>
	<div
		class="py-4 lg:py-6 pb-4 lg:pb-6 pr-5 lg:pr-8 pl-5 lg:pl-8 bg-white rounded-lg shadow no-print"
	>
		<LayoutBreadcrumbs
			:pages="breadcrumbs"
			class="mb-4"
		/>
		<div class="mt-2 flex items-center justify-between">
			<div class="min-w-0 flex-1">
				<h2 class="text-xl lg:text-3xl/7 font-bold text-grayDark sm:truncate sm:tracking-tight">
					{{ title }}
				</h2>
			</div>
			<div
				class="flex shrink-0 md:ml-4 md:mt-0"
			>
				<button
					v-if="isInQuickAccess"
					type="button"
					class="rounded-full px-1.5 lg:px-2.5 py-1.5 lg:py-2.5 text-sm shadow-sm hover:shadow-md ring-1 ring-slate-200"
					@click="openQuickAccessDialog(true)"
				>
					<StarIcon
						class="size-4 lg:size-5 text-yellow-600 fill-yellow-600"
						aria-hidden="true"
					/>
				</button>
				<button
					v-else
					type="button"
					class="rounded-full px-1.5 lg:px-2.5 py-1.5 lg:py-2.5 text-sm shadow-sm hover:shadow-md ring-1 ring-slate-200"
					@click="openQuickAccessDialog(false)"
				>
					<StarIcon
						class="size-4 lg:size-5 text-yellow-600"
						aria-hidden="true"
					/>
				</button>
				<LayoutActionsDropdown
					v-if="links && links.length"
					:links="links"
				/>
				<!--				<LayoutActionsMobileDropdown
					v-if="actions && actions.length"
					:actions="actions"
					:slug="slug"
				/> -->
				<div
					v-for="(action, key) in actions"
					v-if="actions && actions.length"
					:key="key"
					class="hidden lg:block"
				>
					<BaseButton
						v-if="action.type === 'save-and-stay' && canEdit(slug) || action.type === 'save-and-stay' && slug === ''"
						variant="secondary"
						size="xl"
						class="ml-4"
						@click="emit('save', true)"
					>
						Uložit a odejít
					</BaseButton>
					<BaseButton
						v-if="action.type === 'save' && canEdit(slug) || action.type === 'save' && slug === ''"
						variant="primary"
						size="xl"
						class="ml-4"
						@click="emit('save', false)"
					>
						Uložit
					</BaseButton>
					<BaseButton
						v-if="action.type === 'add' && canEdit(slug)"
						variant="primary"
						size="xl"
						class="ml-4"
						@click="router.push(route.fullPath + '/pridat')"
					>
						{{ action.text }}
					</BaseButton>
					<BaseButton
						v-if="action.type === 'add-dialog' && canEdit(slug)"
						variant="primary"
						size="xl"
						class="ml-4"
						@click="emit('add-dialog')"
					>
						{{ action.text }}
					</BaseButton>
					<BaseButton
						v-if="action.type === 'filter-dialog'"
						variant="primary"
						size="xl"
						class="ml-4"
						@click="emit('filter-dialog')"
					>
						{{ action.text }}
					</BaseButton>
					<BaseButton
						v-if="action.type === 'print'"
						variant="secondary"
						size="xl"
						class="ml-4 hidden lg:block"
						@click="print"
					>
						<PrinterIcon class="size-5 text-primaryLight" />
					</BaseButton>
				</div>
			</div>
		</div>
		<div
			v-for="(action, key) in actions"
			v-if="actions && actions.length"
			:key="key"
			class="lg:hidden flex gap-x-4 mt-4"
		>
			<BaseButton
				v-if="action.type === 'save' && canEdit(slug) || action.type === 'save' && slug === ''"
				variant="secondary"
				size="md"
				@click="emit('save', true)"
			>
				Uložit a odejít
			</BaseButton>
			<BaseButton
				v-if="action.type === 'save' && canEdit(slug) || action.type === 'save' && slug === ''"
				variant="primary"
				size="md"
				@click="emit('save', false)"
			>
				Uložit
			</BaseButton>
			<BaseButton
				v-if="action.type === 'add' && canEdit(slug)"
				variant="primary"
				size="md"
				@click="router.push(route.fullPath + '/pridat')"
			>
				{{ action.text }}
			</BaseButton>
			<BaseButton
				v-if="action.type === 'add-dialog' && canEdit(slug)"
				variant="primary"
				size="md"
				@click="emit('add-dialog')"
			>
				{{ action.text }}
			</BaseButton>
			<BaseButton
				v-if="action.type === 'filter-dialog'"
				variant="primary"
				size="md"
				@click="emit('filter-dialog')"
			>
				{{ action.text }}
			</BaseButton>
		</div>
		<div
			v-if="filters && filters.length"
			class="grid grid-cols-12 gap-4"
		>
			<div class="col-span-full border-b border-grayLight mt-4" />
			<ContactFilterDropdown
				v-for="(filter, key) in filters"
				:key="key"
				class="col-span-4 lg:col-span-2"
				:title="filter.title"
				:data="filter.data"
				:multiple="filter.multiple"
				:type="filter.type"
				:slug="filter.slug"
				:filters-query="filtersQuery"
				@update-filters="emitUpdateFilters"
			/>
		</div>
		<QuickAccessDialog
			v-model:show="quickAccessDialogShow"
			v-model:form="quickAccessItem"
		/>
	</div>
</template>
