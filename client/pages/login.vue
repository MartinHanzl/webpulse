<script setup lang="ts">
import { ref } from 'vue';
import { Form } from 'vee-validate';

const form = ref({
	email: '' as string,
	password: '' as string,
});
const toast = useToast();

const { login } = useSanctumAuth();
function handleSubmit() {
	if (form.value.email && form.value.password) {
		login(form.value).then(() => {
			toast.add({
				title: 'Přihlášení',
				description: 'Byli jste úspěšně přihlášeni.',
				color: 'green',
			});
		}).catch(() => {
			toast.add({
				title: 'Chyba',
				description: 'Nepodařilo se přihlásit. Zkontrolujte prosím zadané údaje.',
				color: 'red',
			});
		});
	}
}

useHead({
	title: 'Login',
});
definePageMeta({
	layout: 'login',
});
</script>

<template>
	<LayoutContainer class="max-w-md w-full mt-24 lg:mt-60">
		<h1 class="text-md lg:text-2xl font-semibold text-grayDark text-center mb-4">
			Přihlášení
		</h1>
		<Form @submit="handleSubmit">
			<div class="grid grid-cols-1 gap-y-4">
				<BaseFormInput
					v-model="form.email"
					class="col-span-full"
					label="E-mail"
					type="email"
					name="email"
					rules="required|email"
				/>
				<BaseFormInput
					v-model="form.password"
					class="col-span-full"
					label="Heslo"
					type="password"
					name="password"
					rules="required"
				/>
				<BaseButton
					class="col-span-full mt-4"
					type="submit"
					variant="primary"
					size="xl"
				>
					Přihlásit se
				</BaseButton>
			</div>
		</Form>
	</LayoutContainer>
</template>
