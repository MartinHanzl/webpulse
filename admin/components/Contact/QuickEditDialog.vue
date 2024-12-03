<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { Form } from 'vee-validate';
import { ref } from 'vue';

const toast = useToast();
const showDeleteDialog = ref(false);
const deleteDialogItem = ref(null);

const show = defineModel('show', {
	type: Boolean,
	default: false,
});
const item = defineModel('item', {
	type: Object,
	default: {},
});

const { refreshIdentity } = useSanctumAuth();
const route = useRoute();

async function submitForm() {
	const client = useSanctumClient();

	await client<{
		id: null;
		name: string;
		link: string;
		target: string;
	}>(form.value.id == null ? '/api/admin/quick-access' : '/api/admin/quick-access/' + form.value.id, {
		method: 'POST',
		body: JSON.stringify(form.value),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		toast.add({
			title: 'Hotovo',
			description: 'Položka rychlého přístupu byla úspěšně ' + (form.value.id == null ? 'přidána' : 'upravena') + '.',
			color: 'green',
		});
		refreshIdentity();
	}).catch(() => {
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se ' + (form.value.id == null ? 'přidat' : 'upravit') + ' položku rychlého přístupu.',
			color: 'red',
		});
	}).finally(() => {
		showDeleteDialog.value = false;
		show.value = false;
	});
}
async function deleteItem() {
	const client = useSanctumClient();

	await client<{
		id: null;
		name: string;
		link: string;
		target: string;
	}>('/api/admin/quick-access/' + form.value.id, {
		method: 'DELETE',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then(() => {
		toast.add({
			title: 'Hotovo',
			description: 'Položka rychlého přístupu byla úspěšně smazána.',
			color: 'green',
		});
		refreshIdentity();
	}).catch(() => {
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se smazat položku rychlého přístupu.',
			color: 'red',
		});
	}).finally(() => {
		showDeleteDialog.value = false;
		show.value = false;
	});
}
</script>

<template>
	<div>
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
					<div class="fixed inset-0 bg-grayCustom/75 transition-opacity" />
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
								<Form
									@submit="submitForm"
								>
									<div class="sm:flex sm:items-start">
										<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
											<DialogTitle
												as="h3"
												class="text-base font-semibold text-grayDark"
											>
												{{ item.firstname + ' ' + item.lastname }}
											</DialogTitle>
											<div
												class="mt-6 grid grid-cols-2 gapy-6"
											>
												<div class="col-span-1 grid grid-cols-1 gap-y-4">
													<p class="text-sm font-medium text-grayDark col-span-full">
														<span class="font-semibold">E-mail:</span> {{ item.email ?? '-' }}
													</p>
													<p class="text-sm font-medium text-grayDark col-span-full">
														<span class="font-semibold">Telefon:</span> {{ item.phone ?? '-' }}
													</p>
													<p class="text-sm font-medium text-grayDark col-span-full">
														<span class="font-semibold">Práce/obor/studium:</span> {{ item.email ?? '-' }}
													</p>
													<p class="text-sm font-medium text-grayDark col-span-full">
														<span class="font-semibold">Sen/cíl:</span> {{ item.phone ?? '-' }}
													</p>
												</div>
												<div class="border-l col-span-1 grid grid-cols-1 gap-y-4">
													<p class="text-sm font-medium text-grayDark col-span-full">
														<span class="font-semibold">Práce/obor/studium:</span> {{ item.email ?? '-' }}
													</p>
													<p class="text-sm font-medium text-grayDark col-span-full">
														<span class="font-semibold">Sen/cíl:</span> {{ item.phone ?? '-' }}
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
										<BaseButton
											type="submit"
											variant="success"
											size="lg"
											class="ml-4"
										>
											Uložit
										</BaseButton>
										<BaseButton
											ref="cancelButtonRef"
											type="button"
											variant="secondary"
											size="lg"
											class="ml-4"
											@click="show = false"
										>
											Zavřít
										</BaseButton>
									</div>
								</Form>
							</DialogPanel>
						</TransitionChild>
					</div>
				</div>
			</Dialog>
		</TransitionRoot>
		<BaseDialogDelete
			v-model:show="showDeleteDialog"
			v-model:item="deleteDialogItem"
			@delete-item="deleteItem"
		/>
	</div>
</template>
