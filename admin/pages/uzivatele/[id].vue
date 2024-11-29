<script setup lang="ts">
import { ref } from 'vue';
import { Form } from 'vee-validate';

const toast = useToast();

const route = useRoute();

const error = ref(false);
const loading = ref(false);
const { refreshIdentity, logout } = useSanctumAuth();

const item = ref({
	firstname: '' as string,
	lastname: '' as string,
	avatar: '' as string,
	email: '' as string,
	phone_prefix: '+420' as string,
	phone: '' as string,
	invitation_token: '' as string,
	street: '' as string,
	city: '' as string,
	zip: '' as string,
});

const passwords = ref({
	current_password: '' as string,
	new_password: '' as string,
	confirm_new_password: '' as string,
});

async function loadItem() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{
		firstname: string;
		lastname: string;
		avatar: string;
		email: string;
		phone_prefix: string;
		phone: string;
		invitation_token: string;
		street: string;
		city: string;
		zip: string;
	}>('/api/admin/user/' + route.params.id, {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		item.value = response;
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst uživatelský profil. Zkuste to prosím později.',
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
		firstname: string;
		lastname: string;
		avatar: string;
		email: string;
		phone_prefix: string;
		phone: string;
		invitation_token: string;
		street: string;
		city: string;
		zip: string;
	}>(route.params.id === 'pridat' ? '/api/admin/user' : '/api/admin/user/' + route.params.id, {
		method: 'POST',
		body: JSON.stringify(item.value),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		item.value = response;
	}).then(() => {
		refreshIdentity();
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se uložit uživatelský profil. Zkontrolujte, že máte vyplněna všechna pole správně a zkuste to znovu.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}

async function copyToClipboard() {
	await navigator.clipboard.writeText(item.value.invitation_token).then(() => {
		toast.add({
			title: 'Kopírováno',
			description: 'Kód pozvánky byl zkopírován do schránky.',
			color: 'green',
		});
	}).catch(() => {
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se zkopírovat kód pozvánky do schránky.',
			color: 'red',
		});
	});
}

const breadcrumbs = [
	{
		name: 'Uživatelé',
		link: '/uzivatele',
		current: false,
	},
	{
		name: route.params.id === 'pridat' ? 'Nový uživatel' : item.value.firstname + ' ' + item.value.lastname,
		link: route.params.id === 'pridat' ? '/uzivatele/pridat' : '/uzivatele/' + route.params.id,
		current: true,
	},
];
useHead({
	title: 'Profil',
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
			:title="route.params.id === 'pridat' ? 'Nový uživatel' : item.firstname + ' ' + item.lastname"
			:breadcrumbs="breadcrumbs"
			:actions="[{ type: 'save' }]"
			@save="saveItem"
		/>
		<div class="grid grid-cols-4 gap-x-10">
			<LayoutContainer class="col-span-3 w-full">
				<Form @submit="saveItem">
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
							rules="required|email"
							class="col-span-1"
						/>
						<div class="col-span-full border-b border-grayLight mb-2 mt-4" />
						<BaseFormInput
							v-model="item.street"
							label="Ulice a č.p."
							type="text"
							name="street"
							class="col-span-1"
						/>
						<br>
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
						<div class="col-span-full border-b border-grayLight mb-2 mt-4" />
            <BaseFormInput
                v-model="passwords.new_password"
                label="Nové heslo"
                type="password"
                name="new_password"
                rules="required"
                class="col-span-1"
            />
            <BaseFormInput
                v-model="passwords.confirm_new_password"
                label="Potvrzení nového hesla"
                type="password"
                name="confirm_new_password"
                rules="required"
                class="col-span-1"
            />
					</div>
				</Form>
			</LayoutContainer>
			<LayoutContainer class="col-span-1 w-full">
				<div class="grid grid-cols-2 gap-x-8 gap-y-4">
					<div class="col-span-full text-center">
						<NuxtImg
							class="mx-auto size-full rounded-full"
							src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
							alt=""
						/>
					</div>
					<div class="col-span-full border-b border-grayLight mb-2 mt-4" />
					<BaseFormInput
						v-model="item.invitation_token"
						label="Kód pozvánky"
						disabled
						name="invitation_token"
						class="col-span-full cursor-pointer"
						@click.prevent="copyToClipboard"
					/>
				</div>
			</LayoutContainer>
		</div>
	</div>
</template>
