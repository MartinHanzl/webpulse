<script setup lang="ts">
import { ref, watchEffect, onMounted } from 'vue';

const router = useRouter();
const route = useRoute();

const query = ref({
	page: 1,
	perPage: 25,
	search: '',
});
const filters = ref({
	category: '',
	favorite: false,
	sort: 'name',
	order: 'asc',
});

const tabs = ref([
	{ name: 'Seznam kontaktů', href: '#kontakty', current: true },
	{ name: 'Kategorie kontaktů', href: '#kategorie', current: false },
	{ name: 'Úkoly', href: '#ukoly', current: false },
]);

const title = ref('Seznam kontaktů');

const currentTab = ref(tabs.value.find(tab => tab.current).href);

watchEffect(() => {
	tabs.value.forEach((tab) => {
		tab.current = tab.href === currentTab.value;
	});
});

watchEffect(() => {
	router.push(currentTab.value);
	if (currentTab.value === '#kontakty') {
		title.value = 'Seznam kontaktů';
	}
	else if (currentTab.value === '#kategorie') {
		title.value = 'Kategorie kontaktů';
	}
	else if (currentTab.value === '#ukoly') {
		title.value = 'Úkoly';
	}
});

function handleTabChange(event) {
	currentTab.value = event.target.value;
}

onMounted(() => {
	const hash = route.hash;
	if (hash) {
		currentTab.value = hash;
	}
	useHead({
		title: 'Kontakty',
	});
});
</script>

<template>
	<div>
		<BaseHeading
			:title="title"
			:actions="[
				{ text: 'Oblíbené', type: 'favorite' },
				{ text: 'Exportovat', type: 'export' },
				{ text: 'Přidat', type: 'add' },
			]"
			:breadcrumbs="[
				{ name: 'Kontakty', href: '#', current: true },
			]"
		/>
		<div>
			<div class="sm:hidden">
				<select
					id="tabs"
					name="tabs"
					class="block w-full rounded-md border-slate-dark focus:border-primary focus:ring-primary"
					@change="handleTabChange"
				>
					<option
						v-for="tab in tabs"
						:key="tab.name"
						:value="tab.href"
						:selected="tab.current"
					>
						{{ tab.name }}
					</option>
				</select>
			</div>
			<div class="hidden sm:block">
				<nav
					class="flex space-x-4"
					aria-label="Tabs"
				>
					<a
						v-for="tab in tabs"
						:key="tab.name"
						:href="tab.href"
						:class="[tab.current ? 'bg-primary text-white font-semibold hover:bg-primary-hover' : 'bg-primary-light text-primary hover:bg-primary-light-hover', 'rounded-md px-3 py-2 text-sm font-medium']"
						:aria-current="tab.current ? 'page' : undefined"
						@click.prevent="currentTab = tab.href"
					>{{ tab.name }}</a>
				</nav>
			</div>
		</div>
		<template v-if="currentTab === '#kontakty'">
			<div />
		</template>
		<template v-else-if="currentTab === '#kategorie'">
			<div />
		</template>
	</div>
</template>
