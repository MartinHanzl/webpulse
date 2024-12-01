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

const phases = ref([]);

const pageTitle = ref(route.params.id === 'pridat' ? 'Nová fáze procesu' : 'Detail fáze procesu');

const breadcrumbs = ref([
	{
		name: 'Kontakty',
		link: '/kontakty',
		current: false,
	},
	{
		name: 'Úkoly',
		link: '/ukoly',
		current: false,
	},
	{
		name: 'Nový úkol',
		link: '/ukoly/pridat',
		current: true,
	},
]);

const item = ref({
	id: null as number | null,
	name: '' as string,
	contact_phase_id: null as string | null,
});

async function loadItem() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{
		id: number | null;
		name: string;
		contact_phase_id: string | null;
	}>('/api/admin/contact/task/' + route.params.id, {
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
			description: 'Nepodařilo se načíst úkol. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function loadPhases() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{}>('/api/admin/contact/phase', {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		response.forEach((phase: any) => {
			phases.value.push({
				value: phase.id,
				name: phase.name,
			});
		});
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst fáze. Zkuste to prosím později.',
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
		contact_phase_id: string | null;
	}>(route.params.id === 'pridat' ? '/api/admin/contact/task' : '/api/admin/contact/task/' + route.params.id, {
		method: 'POST',
		body: JSON.stringify(item.value),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		toast.add({
			title: 'Hotovo',
			description: 'Nový úkol byl úspěšně vytvořen.',
			color: 'green',
		});
		router.push('/ukoly');
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se uložit úkol. Zkontrolujte, že máte vyplněna všechna pole správně a zkuste to znovu.',
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
	loadPhases();
});
definePageMeta({
	middleware: 'sanctum:auth',
});
</script>

<template>
	<div>
		<LayoutHeader
			:title="route.params.id === 'pridat' ? 'Nový úkol' : item.name"
			:breadcrumbs="breadcrumbs"
			:actions="[{ type: 'save' }]"
			slug="contacts"
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
						<BaseFormSelect
							v-model="item.contact_phase_id"
							:options="phases"
							label="Fáze procesu"
							name="contact_phase_id"
							class="col-span-1"
						/>
					</div>
				</LayoutContainer>
			</div>
		</Form>
	</div>
</template>
