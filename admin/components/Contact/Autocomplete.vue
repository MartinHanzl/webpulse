<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import {
	Combobox,
	ComboboxInput,
	ComboboxButton,
	ComboboxOptions,
	ComboboxOption,
	TransitionRoot,
} from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/20/solid';
import { debounce } from 'lodash';

defineProps({
	label: {
		type: Number,
		required: true,
		default: '' as string | null,
	},
	name: {
		type: String,
		required: true,
		default: '' as string | null,
	},
});
const toast = useToast();

const model = ref<number | null>(null);

const error = ref(false);
const loading = ref(false);

const contacts = ref([
	{ id: null, firstname: 'Načítám', lastname: 'kontatky...' },
]);

const query = ref('');

const emit = defineEmits(['update:modelValue']);

async function loadItems() {
	if (query.value === '' || query.value.length < 3) {
		return;
	}

	loading.value = true;
	const client = useSanctumClient();

	await client<{ id: number; firstname: string; lastname: string }>('/api/admin/contact', {
		method: 'GET',
		query: {
			search: query.value,
		},
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
		},
	}).then((response) => {
		contacts.value = response;
	}).catch(() => {
		error.value = true;
		toast.add({
			title: 'Chyba',
			description: 'Nepodařilo se načíst kontakty. Zkuste to prosím později.',
			color: 'red',
		});
	}).finally(() => {
		loading.value = false;
	});
}
const debouncedLoadItems = debounce(loadItems, 400);

watch(query, debouncedLoadItems);

const selectedContact = computed(() => {
	return contacts.value.find(contact => contact.id === model.value) || { firstname: '', lastname: '' };
});

watch(model, (newValue) => {
	emit('update:modelValue', newValue);
});
</script>

<template>
	<div class="w-72">
		<Combobox v-model="model">
			<div class="relative mt-1">
				<div
					class="w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm"
				>
					<label
						:for="name"
						class="block text-sm/6 font-medium text-grayCustom"
					>{{ label }}</label>
					<ComboboxInput
						class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
						:name="name"
						:display-value="() => selectedContact ? `${selectedContact.firstname} ${selectedContact.lastname}` : ''"
						@change="query = $event.target.value"
					/>
					<ComboboxButton
						class="absolute inset-y-0 right-0 flex items-center pr-2"
					>
						<ChevronUpDownIcon
							class="h-5 w-5 text-grayLight"
							aria-hidden="true"
						/>
					</ComboboxButton>
				</div>
				<TransitionRoot
					leave="transition ease-in duration-100"
					leave-from="opacity-100"
					leave-to="opacity-0"
				>
					<ComboboxOptions
						class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
					>
						<div
							v-if="contacts.length === 0"
							class="relative cursor-default select-none px-4 py-2 text-gray-700"
						>
							Žádné kontakty.
						</div>

						<ComboboxOption
							v-for="(contact, index) in contacts"
							:key="index"
							v-slot="{ selected, active }"
							as="template"
							:value="contact.id"
						>
							<li
								class="relative cursor-default select-none py-2 pl-10 pr-4"
								:class="{
									'bg-secondary text-grayDark': active,
									'text-grayDark': !active,
								}"
							>
								<span
									class="block truncate"
									:class="{ 'font-medium': selected, 'font-normal': !selected }"
								>
									{{ `${contact.firstname} ${contact.lastname}` }}
								</span>
							</li>
						</ComboboxOption>
					</ComboboxOptions>
				</TransitionRoot>
			</div>
		</Combobox>
	</div>
</template>
