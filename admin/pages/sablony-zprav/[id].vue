<script setup lang="ts">
import { ref } from 'vue';
import { Form } from 'vee-validate';
import { useUserGroupStore } from '~/stores/userGroupStore';

const userGroupStore = useUserGroupStore();
const toast = useToast();

const route = useRoute();
const router = useRouter();

const error = ref(false);
const loading = ref(false);

const pageTitle = ref(route.params.id === 'pridat' ? 'Nová šablona' : 'Detail šablony');

const breadcrumbs = ref([
	{
		name: 'Šablony zpráv',
		link: '/sablony-zprav',
		current: false,
	},
	{
		name: 'Nová šablona',
		link: '/sablony-zprav/pridat',
		current: true,
	},
]);

const item = ref({
	id: null as number | null,
	name: '' as string,
	message: '' as string,
  type: '' as string,
});

async function loadItem() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{
		id: number | null;
		name: string;
    message: string;
    type: string;
	}>('/api/admin/message/blueprint/' + route.params.id, {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		item.value = response;
		breadcrumbs.value.pop();
		breadcrumbs.value.push({
			name: item.value.name,
			link: '/faze/' + route.params.id,
			current: true,
		});
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst fázi procesu. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function saveItem() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{
    id: number | null;
    name: string;
    message: string;
    type: string;
	}>(route.params.id === 'pridat' ? '/api/admin/message/blueprint' : '/api/admin/message/blueprint/' + route.params.id, {
		method: 'POST',
		body: JSON.stringify(item.value),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		toast.add({
			title: 'Hotovo',
			description: 'Nová šablona byla úspěšně vytvořena.',
			color: 'green',
		});
		router.push('/faze');
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se uložit šablonu. Zkontrolujte, že máte vyplněna všechna pole správně a zkuste to znovu.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

useHead({
	title: pageTitle.value,
});

onMounted(() => {
	if (route.params.id !== 'pridat') {
		loadItem();
	}
});
definePageMeta({
	middleware: 'sanctum:auth',
});
</script>

<template>
	<div>
		<LayoutHeader
			:title="route.params.id === 'pridat' ? 'Nová šablona' : item.name"
			:breadcrumbs="breadcrumbs"
			:actions="[{ type: 'save' }]"
			slug="message_blueprints"
			@save="saveItem"
		/>
		<Form @submit="saveItem">
			<div class="grid grid-cols-1 gap-x-10">
				<LayoutContainer class="col-span-full w-full">
					<div class="grid grid-cols-2 gap-x-8 gap-y-4">
						<BaseFormInput
							v-model="item.name"
							label="Název"
							type="text"
							name="name"
							rules="required|min:3"
							class="col-span-1"
						/>
					</div>
				</LayoutContainer>
			</div>
		</Form>
	</div>
</template>
