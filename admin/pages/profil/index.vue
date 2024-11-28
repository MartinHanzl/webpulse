<script setup lang="ts">
import { Form } from 'vee-validate';

const toast = useToast();
const error = ref(false);
const loading = ref(false);
const { refreshIdentity } = useSanctumAuth();

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

async function loadItem() {
	const client = useSanctumClient();
	loading.value = true;

	await client<{}>('/api/admin/auth/profile', {
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
async function submitForm() {
	const client = useSanctumClient();
}
const breadcrumbs = [
	{
		name: 'Profil',
		link: '/profil',
		current: true,
	},
];
onMounted(() => {
	loadItem();
});
</script>

<template>
	<div>
		<LayoutHeader
			title="Profil"
			:breadcrumbs="breadcrumbs"
		/>
		<LayoutContainer>
			<Form @submit="submitForm">
				<div class="grid grid-cols-2 gap-x-8 gap-y-4">
					<BaseFormInput
						v-model="item.firstname"
						label="Jméno"
						name="firstname"
						rules="required"
						class="col-span-1"
					/>
					<BaseFormInput
						v-model="item.lastname"
						label="Příjmení"
						name="lastname"
						rules="required"
						class="col-span-1"
					/>
					<BaseFormInput
						v-model="item.email"
						label="E-mail"
						name="email"
						rules="required"
						class="col-span-1"
					/>
          <div class="col-span-full border-b border-grayLight mb-2 mt-4" />
          <BaseFormInput
              v-model="item.street"
              label="Ulice a č.p."
              name="street"
              class="col-span-1"
          />
          <br>
          <BaseFormInput
              v-model="item.zip"
              label="PSČ"
              name="zip"
              class="col-span-1"
          />
          <BaseFormInput
              v-model="item.city"
              label="Město"
              name="street"
              class="col-span-1"
          />
				</div>
			</Form>
		</LayoutContainer>
	</div>
</template>
