<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { ref } from 'vue';

const show = defineModel('show', {
	type: Boolean,
	default: false,
});

const { refreshIdentity } = useSanctumAuth();
const route = useRoute();
const form = ref({
	name: '',
	link: route.fullPath,
	target: null,
});
async function submitForm() {
	const client = useSanctumClient();

	await client<{}>('/api/admin/quick-access', {
		method: 'POST',
		body: JSON.stringify(form.value),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		refreshIdentity();
	}).finally(() => {
		show.value = false;
	});
}
</script>

<template>
	<TransitionRoot
		as="template"
		:show="show"
	>
		<Dialog class="relative z-10">
			<TransitionChild
				as="template"
				enter="ease-out duration-300"
				enter-from="opacity-0"
				enter-to="opacity-100"
				leave="ease-in duration-200"
				leave-from="opacity-100"
				leave-to="opacity-0"
			>
				<div class="fixed inset-0 bg-gray-500/75 transition-opacity" />
			</TransitionChild>

			<div class="fixed inset-0 z-10 w-screen overflow-y-auto">
				<div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
					<TransitionChild
						as="template"
						enter="ease-out duration-300"
						enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
						enter-to="opacity-100 translate-y-0 sm:scale-100"
						leave="ease-in duration-200"
						leave-from="opacity-100 translate-y-0 sm:scale-100"
						leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
					>
						<DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
							<div class="sm:flex sm:items-start">
								<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
									<DialogTitle
										as="h3"
										class="text-base font-semibold text-gray-900"
									>
										Nová položka rychlého přístupu
									</DialogTitle>
									<div class="mt-6 grid grid-cols-1 gap-y-4 w-full">
										<div class="col-span-full">
											<label
												for="name"
												class="block text-sm/6 font-medium text-gray-500"
											>Název</label>
											<div class="mt-2">
												<input
													id="name"
													v-model="form.name"
													type="text"
													name="name"
													class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-secondary sm:text-sm/6"
												>
											</div>
										</div>
										<div class="col-span-full">
											<label
												for="link"
												class="block text-sm/6 font-medium text-gray-500"
											>Odkaz</label>
											<div class="mt-2">
												<input
													id="link"
													v-model="form.link"
													disabled
													type="text"
													name="link"
													class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-secondary sm:text-sm/6"
												>
											</div>
										</div>
										<div class="col-span-full">
											<label
												for="target"
												class="block text-sm/6 font-medium text-gray-500"
											>Odkaz otevřit v ...</label>
											<div class="mt-2">
												<select
													id="target"
													v-model="form.target"
													name="target"
													class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm/6"
												>
													<option value="null">
														Stejném okně
													</option>
													<option value="_blank">
														Novém okně
													</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
								<button
									type="button"
									class="inline-flex w-full justify-center rounded-md bg-success px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
									@click="submitForm"
								>
									Přidat
								</button>
								<button
									ref="cancelButtonRef"
									type="button"
									class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
									@click="show = false"
								>
									Zavřít
								</button>
							</div>
						</DialogPanel>
					</TransitionChild>
				</div>
			</div>
		</Dialog>
	</TransitionRoot>
</template>
