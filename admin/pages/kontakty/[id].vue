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
const sources = ref([]);
const tasks = ref([]);

const pageTitle = ref(route.params.id === 'pridat' ? 'Nový kontakt' : 'Detail kontaktu');

const breadcrumbs = ref([
	{
		name: 'Kontakty',
		link: '/kontakty',
		current: false,
	},
	{
		name: 'Nový kontakt',
		link: '/kontakty/pridat',
		current: true,
	},
]);

const item = ref({
	id: null as number | null,
	firstname: '' as string,
	lastname: '' as string,
	phone_prefix: '+420' as string,
	phone: '' as string,
	email: '' as string,
	company: '' as string,
	street: '' as string,
	city: '' as string,
	zip: '' as string,
	occupation: '' as string,
	goal: '' as string,
	note: '' as string,
	contact_source_id: null as number | string | null,
	contact_phase_id: null as number | string | null,
	next_meeting: '' as string,
	formatted_next_meeting: '' as string,
	last_contacted_at: '' as string,
	formatted_last_contacted_at: '' as string,
	source: {
		id: null as number | null,
		name: '' as string,
		color: '' as string,
	},
	phase: {
		id: null as number | null,
		name: '' as string,
		color: '' as string,
	},
	tasks: {
		id: null as number | null,
		name: '' as string,
		phase_id: null as number | null,
	},
});

async function loadItem() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{
		id: number | null;
		firstname: string;
		lastname: string;
		phone_prefix: string;
		phone: string;
		email: string;
		company: string;
		street: string;
		city: string;
		zip: string;
		occupation: string;
		goal: string;
		note: string;
		contact_source_id: number | null;
		contact_phase_id: number | null;
		next_meeting: string;
		formatted_next_meeting: string;
		last_contacted_at: string;
		formatted_last_contacted_at: string;
		source: {
			id: number | null;
			name: string;
			color: string;
		};
		phase: {
			id: number | null;
			name: string;
			color: string;
		};
		tasks: {
			id: number | null;
			name: string;
			phase_id: string;
		};
	}>('/api/admin/contact/' + route.params.id, {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		item.value = response;
		breadcrumbs.value.pop();
		breadcrumbs.value.push({
			name: item.value.firstname + ' ' + item.value.lastname,
			link: '/uzivatele/' + route.params.id,
			current: true,
		});
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst detail kontaktu. Zkuste to prosím později.',
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

async function loadSources() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{}>('/api/admin/contact/source', {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		response.forEach((source: any) => {
			sources.value.push({
				value: source.id,
				name: source.name,
			});
		});
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst zdroje. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function loadTasks() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{}>('/api/admin/contact/task', {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		tasks.value = response;
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst zdroje. Zkuste to prosím později.',
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
		firstname: string;
		lastname: string;
		phone_prefix: string;
		phone: string;
		email: string;
		company: string;
		street: string;
		city: string;
		zip: string;
		occupation: string;
		goal: string;
		note: string;
		contact_source_id: number | null;
		contact_phase_id: number | null;
		next_meeting: string;
		formatted_next_meeting: string;
		last_contacted_at: string;
		formatted_last_contacted_at: string;
		source: {
			id: number | null;
			name: string;
			color: string;
		};
		phase: {
			id: number | null;
			name: string;
			color: string;
		};
		tasks: {
			id: number | null;
			name: string;
			phase_id: string;
		};
	}>(route.params.id === 'pridat' ? '/api/admin/contact' : '/api/admin/contact/' + route.params.id, {
		method: 'POST',
		body: JSON.stringify(item.value),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		toast.add({
			title: 'Hotovo',
			description: 'Nový kontakt byl úspěšně vytvořen.',
			color: 'green',
		});
		router.push('/kontakty');
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se uložit kontakt. Zkontrolujte, že máte vyplněna všechna pole správně a zkuste to znovu.',
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
	loadSources();
	loadTasks();
});
definePageMeta({
	middleware: 'sanctum:auth',
});
</script>

<template>
	<div>
		<LayoutHeader
			:title="route.params.id === 'pridat' ? 'Nový kontakt' : item.firstname + ' ' + item.lastname"
			:breadcrumbs="breadcrumbs"
			:actions="[{ type: 'save' }]"
			slug="contacts"
			@save="saveItem"
		/>
		<Form @submit="saveItem">
			<div class="grid grid-cols-4 gap-x-10">
				<LayoutContainer class="col-span-2 w-full">
					<h3 class="text-lg font-semibold text-grayCustom mb-8">
						Základní údaje
					</h3>
					<div class="grid grid-cols-2 gap-x-8 gap-y-4">
						<BaseFormInput
							v-model="item.firstname"
							label="Jméno"
							type="text"
							name="firstname"
							rules="required|min:3"
							class="col-span-1"
						/>
						<BaseFormInput
							v-model="item.lastname"
							label="Příjmení"
							type="text"
							name="lastname"
							rules="required|min:3"
							class="col-span-1"
						/>
						<BaseFormInput
							v-model="item.email"
							label="E-mail"
							type="text"
							name="email"
							rules="email"
							class="col-span-full"
						/>
						<BaseFormInput
							v-model="item.phone"
							label="Telefon"
							type="text"
							name="phone"
							class="col-span-full"
						/>
						<div class="col-span-full border-b border-grayLight mb-2 mt-4" />
						<BaseFormInput
							v-model="item.company"
							label="Firma"
							type="text"
							name="company"
							class="col-span-full"
						/>
						<BaseFormInput
							v-model="item.street"
							label="Ulice a č.p."
							type="text"
							name="street"
							class="col-span-full"
						/>
						<BaseFormInput
							v-model="item.zip"
							label="PSČ"
							type="text"
							name="zip"
							class="col-span-1"
						/>
						<BaseFormInput
							v-model="item.city"
							type="text"
							label="Město"
							name="city"
							class="col-span-1"
						/>
					</div>
				</LayoutContainer>
				<LayoutContainer class="col-span-2 w-full">
					<h3 class="text-lg font-semibold text-grayCustom mb-8">
						Rozšiřující údaje
					</h3>
					<div class="grid grid-cols-2 gap-x-8 gap-y-4">
						<BaseFormInput
							v-model="item.occupation"
							type="text"
							label="Práce/obor/studium"
							name="occupation"
							class="col-span-full"
						/>
						<BaseFormInput
							v-model="item.goal"
							type="text"
							label="Sen/cíl"
							name="goal"
							class="col-span-full"
						/>
						<div class="col-span-full border-b border-grayLight mb-2 mt-4" />
						<BaseFormTextarea
							v-model="item.note"
							label="Poznámka"
							name="note"
							class="col-span-full"
						/>
						<BaseFormSelect
							v-model="item.contact_source_id"
							:options="sources"
							label="Zdroj kontaktu"
							name="contact_source_id"
							class="col-span-full"
						/>
					</div>
				</LayoutContainer>
				<LayoutContainer class="col-span-1 w-full">
					<h3 class="text-lg font-semibold text-grayCustom mb-8">
						Proces
					</h3>
					<div class="grid grid-cols-1 gap-x-8 gap-y-4">
						<BaseFormSelect
							v-model="item.contact_phase_id"
							:options="phases"
							label="Fáze"
							name="contact_phase_id"
							class="col-span-1"
						/>
						<BaseFormInput
							v-model="item.formatted_next_meeting"
							type="datetime-local"
							label="Další meeting"
							name="next_meeting"
							class="col-span-full"
						/>
						<BaseFormInput
							v-model="item.formatted_last_contacted_at"
							type="datetime-local"
							label="Poslední kontakt/pokus o kontakt"
							name="last_contacted_at"
							class="col-span-full"
						/>
					</div>
				</LayoutContainer>
				<LayoutContainer class="col-span-3 w-full">
					<h3 class="text-lg font-semibold text-grayCustom mb-8">
						Úkoly
					</h3>
					<BaseFormCheckbox
						v-for="(task, key) in tasks"
						:key="task.id"
						v-model="item.tasks"
						:label="task.name"
						:value="task.id"
						type="checkbox"
						:name="task.name"
						class="col-span-1"
					/>
				</LayoutContainer>
				<LayoutContainer class="col-span-full w-full">
					<h3 class="text-lg font-semibold text-grayCustom mb-8">
						Historie
					</h3>
				</LayoutContainer>
			</div>
		</Form>
	</div>
</template>
