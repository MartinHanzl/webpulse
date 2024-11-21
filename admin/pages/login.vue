<script setup lang="ts">
const form = ref({
	email: '' as string,
	password: '' as string,
});

const { login } = useSanctumAuth();
function handleSubmit() {
	login(form.value);
}

useHead({
	title: 'Login',
	meta: [
		{
			name: 'description',
			content: 'Login to your account',
		},
	],
});
definePageMeta({
	layout: 'login',
});
</script>

<template>
	<div class="mt-20">
		<div class="overflow-hidden bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h1 class="text-2xl font-semibold text-gray-900">
					Přihlášení
				</h1>
				<form @submit.prevent="handleSubmit">
					<div class="mt-6">
						<label
							for="email"
							class="block text-sm font-medium text-gray-700"
						>E-mail</label>
						<input
							id="email"
							v-model="form.email"
							type="email"
							name="email"
							class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
						>
					</div>
					<div class="mt-6">
						<label
							for="password"
							class="block text-sm font-medium text-gray-700"
						>Heslo</label>
						<input
							id="password"
							v-model="form.password"
							type="password"
							name="password"
							class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
						>
					</div>
					<div class="mt-6">
						<BaseButton
							type="submit"
							variant="primary"
							size="lg"
						>
							Přihlásit se
						</BaseButton>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>
