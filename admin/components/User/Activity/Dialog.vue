<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { Form } from 'vee-validate';
import { useActivityStore } from '~/stores/activityStore';

const activityStore = useActivityStore();

const show = defineModel('show', {
	type: Boolean,
	default: false,
});
const item = defineModel('item', {
	type: Object,
	default: {},
});

const emit = defineEmits(['save-item']);
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
							<DialogPanel class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
								<Form
									@submit="emit('save-item', item)"
								>
									<div class="sm:flex sm:items-start">
										<div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
											<DialogTitle
												as="h3"
												class="text-sm lg:text-base font-semibold text-grayDark mb-4 lg:mb-6"
											>
												{{ item.id !== 0 ? 'Upravit aktivitu' : 'Přidat aktivitu' }}
											</DialogTitle>
											<div class="col-span-1 grid grid-cols-1 gap-y-4 text-wrap">
												<BaseFormSelect
													v-model="item.activity_id"
													:options="activityStore.activitiesOptions"
													class="text-sm font-medium text-grayDark col-span-full"
													name="activity_id"
												/>
												<BaseFormInput
													v-model="item.formatted_date"
													type="date"
													label="Datum"
													name="formatted_date"
													class="text-sm font-medium text-grayDark col-span-full"
												/>
												<BaseFormCheckbox
													v-model="item.completed"
													label="Kompletní"
													name="completed"
													class="text-sm font-medium text-grayDark col-span-full"
												/>
											</div>
										</div>
									</div>
									<div class="mt-4 lg:mt-6 flex justify-end lg:flex-row-reverse lg:justify-start gap-x-4">
										<BaseButton
											type="submit"
											variant="success"
											size="lg"
										>
											Uložit
										</BaseButton>
										<BaseButton
											ref="cancelButtonRef"
											type="button"
											variant="secondary"
											size="lg"
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
	</div>
</template>
