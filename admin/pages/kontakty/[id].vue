<script setup lang="ts">
import { inject, ref } from 'vue';
import { Form } from 'vee-validate';

const toast = useToast();

const route = useRoute();
const router = useRouter();

const error = ref(false);
const loading = ref(false);

const phases = ref([]);
const sources = ref([]);
const tasks = ref([]);

const pageTitle = ref(route.params.id === 'pridat' ? 'Nový kontakt' : 'Detail kontaktu');

const tabs = ref([
	{ name: 'Základní údaje', link: '#info', current: false },
	{ name: 'Proces', link: '#proces', current: false },
	{ name: 'Historie', link: '#historie', current: false },
	{ name: 'Lidé', link: '#lide', current: false },
]);

const historyDialog = ref({
	open: false,
	item: null,
});

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
	contact_id: null as number | null,
	history: [] as [],
	contacts: [] as [],
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
	} as [],
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
		contact_id: number | null;
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
		}[];
	}>('/api/admin/contact/' + route.params.id, {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		item.value = response;
		item.value.tasks = response.tasks.map(task => task.id);
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

	await client<{ id: number }>('/api/admin/contact/phase', {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		response.forEach((phase: { id: number; name: string }) => {
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

	await client<{ id: number }>('/api/admin/contact/source', {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		response.forEach((source: { id: number; name: string }) => {
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

	await client<{ id: number }>('/api/admin/contact/task', {
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
		contact_id: number | null;
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
		}[];
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
			description: `Nový kontakt byl úspěšně ${item.value.id === 'pridat' ? 'vytvořen' : 'uložen'}.`,
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

async function saveHistoryItem(item: { id: number }) {
	const client = useSanctumClient();
	loading.value = true;

	await client<{}>(!item.id ? '/api/admin/contact/history/' + route.params.id : '/api/admin/contact/history/' + route.params.id + '/' + item.id, {
		method: 'POST',
		body: JSON.stringify(item),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		toast.add({
			title: 'Hotovo',
			description: `Záznám historie byl úspěšně ${!item.id ? 'vytvořen' : 'uložen'}.`,
			color: 'green',
		});
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se uložit záznam historie. Zkontrolujte, že máte vyplněna všechna pole správně a zkuste to znovu.',
			color: 'red',
		});
	}).finally(() => {
		historyDialog.value.open = false;
		historyDialog.value.item = null;
		loading.value = false;
		loadItem();
	});
}

async function deleteHistoryItem(item: { id: number }) {
	const client = useSanctumClient();
	loading.value = true;

	await client<{}>('/api/admin/contact/history/destroy/' + item.id, {
		method: 'DELETE',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		toast.add({
			title: 'Hotovo',
			description: `Záznám historie byl úspěšně smazán.`,
			color: 'green',
		});
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se smazat záznam historie.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
		loadItem();
	});
}

function addRemoveItemTask(taskId) {
	if (item.value.tasks.includes(taskId)) {
		item.value.tasks = item.value.tasks.filter(task => task !== taskId);
		return;
	}
	else {
		item.value.tasks.push(taskId);
	}
}

function editHistoryItem(item) {
	historyDialog.value.item = item;
	historyDialog.value.open = true;
}

useHead({
	title: pageTitle.value,
});

watchEffect(() => {
	const routeTabHash = route.hash;
	if (routeTabHash && routeTabHash !== '') {
		tabs.value.forEach((tab) => {
			tab.current = tab.link === routeTabHash;
		});
	}
	else {
		tabs.value[0].current = true;
		router.push(route.path + '#info');
	}
});

onMounted(() => {
	if (route.params.id !== 'pridat') {
		loadItem();
	}
	else {
		tabs.value.pop();
		tabs.value.pop();
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
		<div>
			<div class="grid grid-cols-1 sm:hidden mt-5">
				<!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
				<select
					aria-label="Select a tab"
					class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
				>
					<option>My Account</option>
					<option>Company</option>
					<option selected>
						Team Members
					</option>
					<option>Billing</option>
				</select>
				<svg
					class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end fill-gray-500"
					viewBox="0 0 16 16"
					fill="currentColor"
					aria-hidden="true"
					data-slot="icon"
				>
					<path
						fill-rule="evenodd"
						d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
						clip-rule="evenodd"
					/>
				</svg>
			</div>
			<div class="hidden sm:block mt-5">
				<nav
					class="isolate flex divide-x divide-gray-200 shadow-sm rounded-lg"
					aria-label="Tabs"
				>
					<!-- Current: "text-gray-900", Default: "text-gray-500 hover:text-gray-700" -->
					<NuxtLink
						v-for="(tab, index) in tabs"
						:key="index"
						:to="tab.link"
						class="group relative min-w-0 flex-1 overflow-hidden bg-white px-4 py-4 text-center text-sm font-medium text-grayCustom hover:bg-gray-50 hover:text-grayDark focus:z-10"
					>
						<span>{{ tab.name }}</span>
						<span
							aria-hidden="true"
							:class="tab.current ? 'absolute inset-x-0 bottom-0 h-0.5 bg-primaryCustom' : 'absolute inset-x-0 bottom-0 h-0.5 bg-transparent'"
						/>
					</NuxtLink>
				</nav>
			</div>
		</div>
		<Form @submit="saveItem">
			<template v-if="tabs.find(tab => tab.current && tab.link === '#info')">
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
							<ContactAutocomplete
								v-model="item.contact_id"
								class="col-span-full"
								label="Od"
							/>
						</div>
					</LayoutContainer>
				</div>
			</template>
			<template v-if="tabs.find(tab => tab.current && tab.link === '#proces')">
				<div class="grid grid-cols-1 gap-x-10">
					<LayoutContainer class="col-span-full w-full">
						<h3 class="text-lg font-semibold text-grayCustom mb-8">
							Úkoly
						</h3>
						<div class="grid grid-cols-4 gap-4">
							<BaseFormCheckbox
								v-for="(task, key) in tasks"
								:key="key"
								:label="task.name"
								:name="task.id"
								:value="item.tasks.includes(task.id)"
								:checked="item.tasks.includes(task.id)"
								class="col-span-1"
								type="badge"
								:color="task.phase_color"
								@change="addRemoveItemTask(task.id)"
							/>
						</div>
					</LayoutContainer>
					<LayoutContainer class="col-span-full w-full">
						<h3 class="text-lg font-semibold text-grayCustom mb-8">
							Proces
						</h3>
						<div class="grid grid-cols-3 gap-x-8 gap-y-4">
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
								class="col-span-1"
							/>
							<BaseFormInput
								v-model="item.formatted_last_contacted_at"
								type="datetime-local"
								label="Poslední kontakt/pokus o kontakt"
								name="last_contacted_at"
								class="col-span-1"
							/>
						</div>
					</LayoutContainer>
				</div>
			</template>
			<template v-if="tabs.find(tab => tab.current && tab.link === '#historie')">
				<div class="grid grid-cols-4 gap-x-10">
					<LayoutContainer class="col-span-full w-full flex items-center justify-between">
						<h3 class="text-lg font-semibold text-grayCustom">
							Historie
						</h3>
						<BaseButton
							variant="primary"
							size="lg"
							type="button"
							@click="historyDialog.open = true;"
						>
							Přidat záznam
						</BaseButton>
					</LayoutContainer>
				</div>
				<div class="grid grid-cols-1 gap-8 mt-5">
					<ol class="relative border-s border-gray-200 dark:border-gray-700">
						<ContactHistoryCard
							v-for="(history, index) in item.history"
							:key="index"
							:history="history"
							@edit-history="editHistoryItem(history)"
							@delete-item="deleteHistoryItem(history)"
						/>
					</ol>
				</div>
				<ContactHistoryDialog
					v-model:show="historyDialog.open"
					v-model:contact="historyDialog.item"
					:phases="phases"
					@save-item="saveHistoryItem"
				/>
			</template>
			<template v-if="tabs.find(tab => tab.current && tab.link === '#lide') && item.contacts && item.contacts.data && item.contacts.data.length">
				<div class="grid grid-cols-4 gap-x-10">
					<LayoutContainer class="col-span-full w-full flex items-center justify-between grid grid-cols-1 gap-4">
						<h3 class="text-lg font-semibold text-grayCustom col-span-full">
							Kontakty
						</h3>
						<BaseTable
							class="col-span-full"
							:items="item.contacts"
							:columns="[
								{ key: 'id', name: 'ID', type: 'text', width: 80, hidden: false, sortable: false },
								{ key: 'firstname', name: 'Jméno', type: 'text', width: 80, hidden: false, sortable: false },
								{ key: 'lastname', name: 'Příjmení', type: 'text', width: 80, hidden: false, sortable: false },
								{ key: 'phone', name: 'Telefon', type: 'text', width: 80, hidden: true, sortable: false },
								{ key: 'email', name: 'E-mail', type: 'text', width: 80, hidden: true, sortable: false },
								{ key: 'phase', name: 'Fáze', type: 'badge', width: 80, hidden: true, sortable: false, colorKey: 'phase_color' },
								{ key: 'source', name: 'Zdroj', type: 'badge', width: 80, hidden: true, sortable: false, colorKey: 'source_color' },
							]"
							:loading="loading"
							:error="error"
							singular="Kontakt"
							plural="Kontakty"
							slug="contacts"
						/>
					</layoutcontainer>
				</div>
			</template>
		</Form>
	</div>
</template>
