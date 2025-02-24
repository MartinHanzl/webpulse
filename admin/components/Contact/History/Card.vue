<script setup lang="ts">
import { ref } from 'vue';
import { CogIcon, UserIcon, QuestionMarkCircleIcon, AcademicCapIcon, PencilIcon, TrashIcon, PhoneIcon, ComputerDesktopIcon, AtSymbolIcon, BoltIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
	history: {
		type: Object,
		required: true,
		default: {} as Record<string, any>,
	},
});
const emit = defineEmits(['edit-history', 'delete-item']);

const deleteDialog = ref({
	show: false,
	item: {},
});

function openDeleteDialog() {
	deleteDialog.value.show = true;
	deleteDialog.value.item = props.history;
}
</script>

<template>
	<li class="mb-4 ms-6">
		<div class="absolute w-6 h-6 bg-gray-200 rounded-full mt-3 -start-3 border border-white dark:border-gray-900 dark:bg-gray-700 flex items-center justify-center">
			<CogIcon
				v-if="history.origin === 'system'"
				class="size-4 lg:size-5 shrink-0"
			/>
			<UserIcon
				v-if="history.origin === 'user'"
				class="size-4 lg:size-5 shrink-0"
			/>
			<AcademicCapIcon
				v-if="history.origin === 'mentor'"
				class="size-4 lg:size-5 shrink-0"
			/>
			<QuestionMarkCircleIcon
				v-if="history.origin === 'other'"
				class="size-4 lg:size-5 shrink-0"
			/>
		</div>
		<div class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow-sm">
			<div class="px-4 py-5 sm:px-6">
				<div class="grid grid-cols-3">
					<div class="col-span-1">
						<time class="mb-1 text-xs lg:text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{ new Date(history.created_at).toLocaleString() }}</time>
						<div class="flex items-end">
							<div class="hidden lg:block">
								<PropsBadge
									v-if="history.type === 'other'"
									color="zinc"
								>
									<QuestionMarkCircleIcon class="size-3 shrink-0" />
								</PropsBadge>
								<PropsBadge
									v-else-if="history.type === 'meeting'"
									color="orange"
								>
									<ComputerDesktopIcon class="size-3 shrink-0" />
								</PropsBadge>
								<PropsBadge
									v-else-if="history.type === 'call'"
									color="emerald"
								>
									<PhoneIcon class="size-3 shrink-0" />
								</PropsBadge>
								<PropsBadge
									v-else-if="history.type === 'email'"
									color="indigo"
								>
									<AtSymbolIcon class="size-3 shrink-0" />
								</PropsBadge>
								<PropsBadge
									v-else-if="history.type === 'activity'"
									color="sky"
								>
									<BoltIcon class="size-3 shrink-0" />
								</PropsBadge>
							</div>
							<h3 class="text-sm lg:text-lg font-semibold text-grayDark lg:ml-4">
								{{ history.name }}
							</h3>
						</div>
					</div>
					<div class="col-span-1 text-center flex items-end justify-evenly">
						<!-- <PropsBadge
							v-if="history.phase"
							:color="history.phase.color"
						>
							{{ history.phase.name }}
						</PropsBadge> -->
						<PropsBadge
							v-if="history.activity"
							:color="history.activity.color"
						>
							{{ history.activity.name }}
						</PropsBadge>
					</div>
					<div class="col-span-1 text-end flex items-end justify-end">
						<PencilIcon
							v-if="history.origin === 'user'"
							class="size-4 lg:size-5 shrink-0 text-primaryCustom hover:text-primaryLight cursor-pointer"
							@click="emit('edit-history', history)"
						/>
						<TrashIcon
							v-if="history.origin === 'user'"
							class="ml-4 size-4 lg:size-5 shrink-0 text-danger hover:text-dangerLight cursor-pointer"
							@click="openDeleteDialog"
						/>
					</div>
				</div>
			</div>
			<div class="px-4 py-5 sm:p-6 divide-x-2 divide-gray-200">
				<p class="text-sm lg:text-base font-normal text-gray-500 dark:text-gray-400">
					{{ history.description }}
				</p>
			</div>
		</div>
		<BaseDialogDelete
			v-model:show="deleteDialog.show"
			v-model:item="deleteDialog.item"
			@delete-item="emit('delete-item', history.id);deleteDialog.show = false;"
		/>
	</li>
</template>
