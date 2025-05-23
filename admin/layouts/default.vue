<script setup lang="ts">
import { ref, provide } from 'vue';
import {
	Dialog,
	DialogPanel,
	Menu,
	MenuButton,
	MenuItem,
	MenuItems,
	TransitionChild,
	TransitionRoot,
} from '@headlessui/vue';
import {
	Bars3Icon,
	BellIcon,
	CalendarIcon,
	ChartPieIcon,
	StarIcon,
	HomeIcon,
	UsersIcon,
	XMarkIcon,
	ArrowTopRightOnSquareIcon,
	BanknotesIcon,
	ClockIcon,
	BuildingOfficeIcon,
	WalletIcon,
	DocumentTextIcon,
	AdjustmentsHorizontalIcon,
	ChatBubbleBottomCenterTextIcon,
	ChartBarSquareIcon,
	ChartBarIcon,
	TrophyIcon,
	CalendarDaysIcon,
	DocumentCurrencyEuroIcon,
	LanguageIcon,
	GlobeEuropeAfricaIcon,
	CurrencyEuroIcon,
} from '@heroicons/vue/24/outline';
import { ChevronDownIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/solid';
import { useUserGroupStore } from '~/stores/userGroupStore';
import { useActivityStore } from '~/stores/activityStore';
import { useLanguageStore } from '~/stores/languageStore';
import { useCountryStore } from '~/stores/countryStore';
import { useCurrencyStore } from '~/stores/currencyStore';
import { useTaxRateStore } from '~/stores/taxRateStore';

const userGroupStore = useUserGroupStore();
const activityStore = useActivityStore();
const languageStore = useLanguageStore();
const countryStore = useCountryStore();
const currencyStore = useCurrencyStore();
const taxRateStore = useTaxRateStore();

const route = useRoute();
const router = useRouter();
const user = useSanctumUser();
const { logout, refreshIdentity } = useSanctumAuth();
const sidebarOpen = ref(false);

const searchString = ref('');
provide('searchString', searchString);

const navigation = ref([
	{ title: 'Úvod', menu: [
		{ name: 'Přehled', link: '/', icon: HomeIcon, current: true },
		{ name: 'Statistiky', link: '/statistiky', icon: ChartPieIcon, current: false },
	] },
	{ title: 'Obsah', menu: [
		{ name: 'Blog', link: '/kontakty', icon: UsersIcon, current: false, slug: 'demo' },
		{ name: 'Informační stránky', link: '/aktivita', icon: CalendarIcon, current: false, slug: 'demo' },
		{ name: 'Služby', link: '/sablony-zprav', icon: ChatBubbleBottomCenterTextIcon, current: false, slug: 'demo' },
		{ name: 'Události a akce', link: '/demo', icon: CalendarIcon, current: false, slug: 'demo' },
	] },
	{ title: 'Uživatelé', menu: [
		{ name: 'Uživatelé', link: '/sablony-zprav', icon: ChatBubbleBottomCenterTextIcon, current: false, slug: 'demo' },
		{ name: 'Odběry newsletteru', link: '/kontakty', icon: UsersIcon, current: false, slug: 'demo' },
		{ name: 'Požadavky', link: '/aktivita', icon: CalendarIcon, current: false, slug: 'demo' },
	] },
	{ title: 'Byznys a osobní růst', menu: [
		{ name: 'Kontakty', link: '/kontakty', icon: UsersIcon, current: false, slug: 'contacts' },
		{ name: 'Aktivita', link: '/aktivita', icon: CalendarIcon, current: false, slug: 'users_has_activities' },
		{ name: 'Šablony zpráv', link: '/sablony-zprav', icon: ChatBubbleBottomCenterTextIcon, current: false, slug: 'message_blueprints' },
		{ name: 'Kalendář', link: '/demo', icon: CalendarIcon, current: false, slug: 'calendars' },
		{ name: 'Cashflow', link: '/cashflow', icon: BanknotesIcon, current: false, slug: 'cashflows' },
		{ name: 'Ligy', link: '/demo', icon: TrophyIcon, current: false, slug: 'leagues' },
		{ name: 'Akce', link: '/demo', icon: CalendarDaysIcon, current: false, slug: 'events' },
	] },
	{ title: 'Vedení firmy', menu: [
		{ name: 'Klienti', link: '/klienti', icon: BuildingOfficeIcon, current: false, slug: 'clients' },
		{ name: 'Projekty', link: '/projekty', icon: BuildingOfficeIcon, current: false, slug: 'projects' },
		{ name: 'Cenové nabídky', link: '/cenove-nabidky', icon: DocumentTextIcon, current: false, slug: 'price_offers' },
		{ name: 'Trackování', link: '/demo', icon: ClockIcon, current: false, slug: 'trackings' },
		{ name: 'Faktury', link: '/demo', icon: WalletIcon, current: false, slug: 'invoices' },
		{ name: 'Dodavatelé', link: '/dodavatele', icon: BuildingOfficeIcon, current: false, slug: 'suppliers' },
		{ name: 'Zaměstnanci', link: '/zamestnanci', icon: UsersIcon, current: false, slug: 'employees' },
		{ name: 'Úkoly', link: '/ukoly', icon: ChartBarSquareIcon, current: false, slug: 'tasks' },
		{ name: 'Smlouvy', link: '/smlouvy', icon: DocumentTextIcon, current: false, slug: 'contracts' },
	] },
	{ title: 'Nastavení a správa', menu: [
		{ name: 'Uživatelé', link: '/uzivatele', icon: UsersIcon, current: false, slug: 'users' },
		{ name: 'Uživatelské skupiny', link: '/uzivatele/skupiny', icon: AdjustmentsHorizontalIcon, current: false, slug: 'user_groups' },
		{ name: 'Aktivity', link: '/aktivity', icon: ChartBarSquareIcon, current: false, slug: 'activities' },
		{ name: 'Sazby DPH', link: '/nastaveni/dph', icon: DocumentCurrencyEuroIcon, current: false, slug: 'tax_rates' },
		{ name: 'Jazyky', link: '/nastaveni/jazyky', icon: LanguageIcon, current: false, slug: 'languages' },
		{ name: 'Země', link: '/nastaveni/zeme', icon: GlobeEuropeAfricaIcon, current: false, slug: 'countries' },
		{ name: 'Měny', link: '/nastaveni/meny', icon: CurrencyEuroIcon, current: false, slug: 'currencies' },
		{ name: 'Odkazy', link: '/aktivity', icon: ChartBarSquareIcon, current: false, slug: 'demo' },
	] },
]);

const userNavigation = [
	{ name: 'Profil', link: '/profil' },
	{ name: 'Rychlý přístup', link: '/profil/rychly-pristup' },
	{ name: 'Odhlásit se', link: null, action: 'handleLogout' },
];

const quickAccess = ref([{ name: '', link: '/', target: null }]);

watchEffect(() => {
	const currentPath = route.path;
	navigation.value.forEach((group) => {
		group.menu.forEach((item) => {
			item.current = currentPath === item.link;
		});
	});
});

function filterNavigationGroups(navigation: any[]): any[] {
	return navigation.filter((group: any) =>
		group.menu.some((item: any) => !item.slug || (item.slug && canView(item.slug))),
	);
}

function canView(slug: string): boolean {
	if (user && user.value && (user.value as any).user_group_id && userGroupStore.userGroups) {
		const userGroup = userGroupStore.userGroups.find((group: any) => group.id === (user.value as any).user_group_id);
		if (userGroup && userGroup.permissions) {
			const currentPermissionSlug = userGroup.permissions.find((permission: any) => permission.slug === slug);
			if (currentPermissionSlug && currentPermissionSlug.permissions.view === true) {
				return true;
			}
		}
	}
	return false;
}

function handleLogout() {
	logout();
	router.push('/login');
}
function getQuickAccess() {
	if (user && user.value && user.value.quick_access) {
		quickAccess.value = user.value.quick_access;
	}
}
watchEffect(() => {
	getQuickAccess();
});

onMounted(() => {
	refreshIdentity();
	getQuickAccess();

	userGroupStore.fetchUserGroups();
	activityStore.fetchActivities();
	languageStore.fetchLanguages();
	countryStore.fetchCountries();
	currencyStore.fetchCurrencies();
	taxRateStore.fetchTaxRates();
	setTimeout(() => {
		navigation.value = filterNavigationGroups(navigation.value);
	}, 100000);
});
</script>

<template>
	<div>
		<TransitionRoot
			as="template"
			:show="sidebarOpen"
		>
			<Dialog
				class="relative z-50 lg:hidden"
				@close="sidebarOpen = false"
			>
				<TransitionChild
					as="template"
					enter="transition-opacity ease-linear duration-300"
					enter-from="opacity-0"
					enter-to="opacity-100"
					leave="transition-opacity ease-linear duration-300"
					leave-from="opacity-100"
					leave-to="opacity-0"
				>
					<div class="fixed inset-0 bg-gray-900/80" />
				</TransitionChild>

				<div class="fixed inset-0 flex">
					<TransitionChild
						as="template"
						enter="transition ease-in-out duration-300 transform"
						enter-from="-translate-x-full"
						enter-to="translate-x-0"
						leave="transition ease-in-out duration-300 transform"
						leave-from="translate-x-0"
						leave-to="-translate-x-full"
					>
						<DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
							<TransitionChild
								as="template"
								enter="ease-in-out duration-300"
								enter-from="opacity-0"
								enter-to="opacity-100"
								leave="ease-in-out duration-300"
								leave-from="opacity-100"
								leave-to="opacity-0"
							>
								<div class="absolute left-full top-0 flex w-16 justify-center pt-5">
									<button
										type="button"
										class="-m-2.5 p-2.5"
										@click="sidebarOpen = false"
									>
										<span class="sr-only">Close sidebar</span>
										<XMarkIcon
											class="size-6 text-white"
											aria-hidden="true"
										/>
									</button>
								</div>
							</TransitionChild>
							<!-- Sidebar component, swap this element with another sidebar if you like -->
							<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4 ring-1 ring-white/10">
								<div class="flex h-24 shrink-0 items-center justify-center">
									<img
										class="h-8 w-auto"
										src="public/static/img/logo-gray-300.png"
										alt="Your Company"
									>
								</div>
								<nav class="flex flex-1 flex-col">
									<ul
										role="list"
										class="flex flex-1 flex-col gap-y-7"
									>
										<li
											v-for="(group, index) in navigation"
											:key="index"
										>
											<div class="text-xs/6 font-semibold text-gray-300">
												{{ group.title }}
											</div>
											<ul
												role="list"
												class="-mx-2 mt-2 space-y-1"
											>
												<li
													v-for="(item, key) in group.menu"
													:key="key"
													@click="sidebarOpen = false"
												>
													<NuxtLink
														v-if="!item.slug || (item.slug && canView(item.slug))"
														:to="item.link"
														:class="[item.current ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white', 'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold']"
													>
														<component
															:is="item.icon"
															class="size-6 shrink-0"
															aria-hidden="true"
														/>
														<span class="truncate">{{ item.name }}</span>
													</NuxtLink>
												</li>
											</ul>
										</li>
										<!--										<li class="mt-auto">
											<a
												href="#"
												class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-gray-300 hover:bg-gray-800 hover:text-white"
											>
												<Cog6ToothIcon
													class="size-6 shrink-0"
													aria-hidden="true"
												/>
												Settings
											</a>
										</li> -->
									</ul>
								</nav>
							</div>
						</DialogPanel>
					</TransitionChild>
				</div>
			</Dialog>
		</TransitionRoot>

		<!-- Static sidebar for desktop -->
		<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
			<!-- Sidebar component, swap this element with another sidebar if you like -->
			<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-900 px-6 pb-4">
				<div class="flex h-24 shrink-0 items-center justify-center">
					<img
						class="h-12 w-auto"
						src="public/static/img/logo-gray-300.png"
						alt="Your Company"
					>
				</div>
				<nav class="flex flex-1 flex-col">
					<ul
						role="list"
						class="flex flex-1 flex-col gap-y-7"
					>
						<li
							v-for="(group, index) in navigation"
							:key="index"
						>
							<div class="text-xs/6 font-semibold text-gray-300">
								{{ group.title }}
							</div>
							<ul
								role="list"
								class="-mx-2 mt-2 space-y-1"
							>
								<li
									v-for="(item, key) in group.menu"
									:key="key"
								>
									<NuxtLink
										v-if="!item.slug || (item.slug && canView(item.slug))"
										:to="item.link"
										:class="[item.current ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white', 'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold']"
									>
										<component
											:is="item.icon"
											class="size-6 shrink-0"
											aria-hidden="true"
										/>
										<span class="truncate">{{ item.name }}</span>
									</NuxtLink>
								</li>
							</ul>
						</li>
						<li class="mt-auto">
							<LayoutPropsCountdown class="bg-gray-900 z-50 px-4 py-10 rounded-lg text-center border border-gray-300" />
							<!--							<a
								href="#"
								class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold text-gray-300 hover:bg-gray-800 hover:text-white"
							>
								<Cog6ToothIcon
									class="size-6 shrink-0"
									aria-hidden="true"
								/>
								Nastavení
							</a> -->
						</li>
					</ul>
				</nav>
			</div>
		</div>

		<div class="lg:pl-64">
			<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 no-print">
				<button
					type="button"
					class="-m-2.5 p-2.5 text-gray-700 lg:hidden"
					@click="sidebarOpen = true"
				>
					<span class="sr-only">Menu</span>
					<Bars3Icon
						class="size-6"
						aria-hidden="true"
					/>
				</button>

				<!-- Separator -->
				<div
					class="h-6 w-px bg-gray-900/10 lg:hidden"
					aria-hidden="true"
				/>

				<div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
					<div
						class="relative flex flex-1"
					>
						<label
							for="search-field"
							class="sr-only"
						>Hledat</label>
						<MagnifyingGlassIcon
							class="pointer-events-none absolute inset-y-0 left-0 h-full w-3 lg:w-5 text-grayCustom"
							aria-hidden="true"
						/>
						<input
							id="search-field"
							v-model="searchString"
							class="block size-full border-0 py-0 pl-6 lg:pl-8 pr-0 text-grayDark placeholder:text-grayCustom focus:ring-0 text-xs lg:text-sm"
							placeholder="Hledat..."
							type="search"
							name="search"
							autocomplete="none"
						>
					</div>
					<div class="flex items-center gap-x-4 lg:gap-x-6">
						<!--						<button
							type="button"
							class="-m-2.5 p-2.5 text-gray-300 hover:text-gray-500"
						>
							<span class="sr-only">View notifications</span>
							<BellIcon
								class="size-6"
								aria-hidden="true"
							/>
						</button> -->

						<!-- Separator -->
						<div
							v-if="quickAccess && quickAccess.length"
							class="block lg:h-6 lg:w-px lg:bg-gray-900/10"
							aria-hidden="true"
						/>

						<!-- Quick access dropdown -->
						<Menu
							v-if="quickAccess && quickAccess.length"
							as="div"
							class="relative"
						>
							<MenuButton class="-m-1.5 flex items-center p-1.5">
								<span class="sr-only">Open qick access menu</span>
								<span class="flex lg:items-center">
									<span
										class="hidden lg:block text-sm/6 font-semibold text-gray-900"
										aria-hidden="true"
									>Rychlý přístup</span>
									<ChevronDownIcon
										class="hidden lg:block ml-2 size-5 text-gray-300"
										aria-hidden="true"
									/>
									<StarIcon
										class="lg:hidden ml-2 size-5 text-yellow-600"
										aria-hidden="true"
									/>
								</span>
							</MenuButton>
							<transition
								enter-active-class="transition ease-out duration-100"
								enter-from-class="transform opacity-0 scale-95"
								enter-to-class="transform opacity-100 scale-100"
								leave-active-class="transition ease-in duration-75"
								leave-from-class="transform opacity-100 scale-100"
								leave-to-class="transform opacity-0 scale-95"
							>
								<MenuItems class="absolute right-0 z-10 mt-2.5 w-56 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
									<MenuItem
										v-for="item in quickAccess"
										:key="item.name"
										v-slot="{ active }"
									>
										<NuxtLink
											:to="item.link"
											:target="item.target"
											:class="[active ? 'bg-gray-50 outline-none' : '', 'block px-3 py-1 text-sm/6 text-gray-900 flex flex-grow items-center justify-between']"
										>
											<span>{{ item.name }}</span>
											<ArrowTopRightOnSquareIcon
												v-if="item.target === '_blank'"
												class="size-4 text-warning ml-4"
												aria-hidden="true"
											/>
										</NuxtLink>
									</MenuItem>
								</MenuItems>
							</transition>
						</Menu>
						<!-- Separator -->
						<div
							class="h-6 w-px bg-gray-900/10"
							aria-hidden="true"
						/>
						<!-- Profile dropdown -->
						<Menu
							as="div"
							class="relative"
						>
							<MenuButton class="-m-1.5 flex items-center p-1.5">
								<span class="sr-only">Open user menu</span>
								<img
									class="size-8 rounded-full bg-gray-50"
									src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
									alt=""
								>
								<span class="hidden lg:flex lg:items-center">
									<span
										class="ml-4 text-sm/6 font-semibold text-gray-900"
										aria-hidden="true"
									>{{ user.firstname }} {{ user.lastname }}</span>
									<ChevronDownIcon
										class="ml-2 size-5 text-gray-300"
										aria-hidden="true"
									/>
								</span>
							</MenuButton>
							<transition
								enter-active-class="transition ease-out duration-100"
								enter-from-class="transform opacity-0 scale-95"
								enter-to-class="transform opacity-100 scale-100"
								leave-active-class="transition ease-in duration-75"
								leave-from-class="transform opacity-100 scale-100"
								leave-to-class="transform opacity-0 scale-95"
							>
								<MenuItems class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
									<MenuItem
										v-for="item in userNavigation"
										:key="item.name"
										v-slot="{ active }"
									>
										<NuxtLink
											v-if="item.link != null"
											:to="item.link"
											:class="[active ? 'bg-gray-50 outline-none' : '', 'block px-3 py-1 text-sm/6 text-gray-900']"
										>{{ item.name }}
										</NuxtLink>
										<span
											v-else
											:class="[active ? 'bg-gray-50 outline-none' : '', 'block px-3 py-1 text-sm/6 text-gray-900 cursor-pointer']"
											@click="handleLogout"
										>{{ item.name }}</span>
									</MenuItem>
								</MenuItems>
							</transition>
						</Menu>
					</div>
				</div>
			</div>

			<main class="py-10">
				<div class="px-4 sm:px-6 lg:px-8">
					<slot />
				</div>
			</main>
		</div>
	</div>
</template>
