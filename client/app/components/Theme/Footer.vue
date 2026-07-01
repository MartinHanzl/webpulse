<script setup lang="ts">
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRoute } from '#app';
import { useApi } from '~/../app/composables/useApi';
import type { DemoDefinition, DemoNavLink } from '~/../app/composables/useDemos';
import { useDemoContent } from '~/../app/composables/useDemoContent';

const props = defineProps<{
  demo: DemoDefinition;
  links: DemoNavLink[];
}>();

const api = useApi();
const { locale } = useI18n();

const email = ref('');
const sending = ref(false);
const sent = ref(false);
const error = ref('');

async function subscribe() {
  if (!email.value || sending.value) return;
  sending.value = true;
  error.value = '';
  try {
    await api.global.newsletter(email.value, locale.value);
    sent.value = true;
    email.value = '';
  } catch {
    error.value = 'Nepodařilo se přihlásit. Zkuste to prosím znovu.';
  } finally {
    sending.value = false;
  }
}

const variant = computed(() => props.demo.slug);

// On /demo routes the logo/services link into the demo; on the real site they
// point at the real routes.
const route = useRoute();
const isDemo = computed(() => route.path.startsWith('/demo'));
const homeTo = computed(() => (isDemo.value ? `/demo/${props.demo.slug}` : '/'));
const contactTo = computed(() => (isDemo.value ? `/demo/${props.demo.slug}/kontakt` : '/kontakt'));
const serviceTo = (s: string) =>
  isDemo.value ? `/demo/${props.demo.slug}/sluzby/${s}` : '/sluzby';

/** Real services for this demo, capped to keep the column tidy. */
const services = computed(() => useDemoContent(props.demo.slug).services.slice(0, 6));

const social = ['facebook', 'instagram', 'youtube'];

const currentYear = new Date().getFullYear();

/** Neutral, per-industry intro copy (replaces the gardening-only text). */
const aboutText = computed(() => {
  switch (props.demo.slug) {
    case 'tree':
      return `${props.demo.tagline}. Certifikovaní specialisté s plným pojištěním a důrazem na bezpečnost.`;
    case 'landscaping':
      return `${props.demo.tagline}. Komplexní řešení pod jednou střechou — od projektu přes realizaci po údržbu.`;
    default:
      return `${props.demo.tagline}. Pečujeme o každý detail s důrazem na kvalitu a spolehlivost.`;
  }
});

/** Working hours shown in the landscaping footer. */
const hours = [
  { day: 'Po–Pá', time: '8:00 – 17:00' },
  { day: 'Sobota', time: '9:00 – 13:00' },
  { day: 'Neděle', time: 'Zavřeno' },
];

/** Opening hours shown in the restaurant footer. */
const restaurantHours = [
  { day: 'Po–Čt', time: '11:00 – 23:00' },
  { day: 'Pá–So', time: '11:00 – 24:00' },
  { day: 'Neděle', time: '11:00 – 22:00' },
];

/** Office hours shown in the lawyer footer. */
const lawyerHours = [
  { day: 'Po–Pá', time: '8:00 – 18:00' },
  { day: 'Sobota', time: 'Dle domluvy' },
  { day: 'Neděle', time: 'Zavřeno' },
];
</script>

<template>
  <!-- ============================ LAWN — dark footer ============================ -->
  <footer v-if="variant === 'lawn'" class="relative overflow-hidden bg-brand-dark text-white">
    <div
      class="pointer-events-none absolute -right-24 -top-24 size-80 rounded-full bg-brand/20 blur-3xl"
    />
    <div class="container-x relative pt-20">
      <!-- Newsletter band (clean, no overlap) -->
      <div
        class="reveal mb-16 flex flex-col gap-6 rounded-3xl bg-brand p-8 text-center shadow-2xl sm:p-12 lg:flex-row lg:items-center lg:justify-between lg:text-left"
      >
        <div class="max-w-md">
          <h3 class="text-2xl font-bold !text-white sm:text-3xl">Odebírejte naše novinky a tipy</h3>
          <p class="mt-2 text-white/80">Sezónní rady, novinky a akční nabídky jednou měsíčně.</p>
        </div>
        <form class="flex w-full max-w-md flex-col gap-3 sm:flex-row" @submit.prevent="subscribe">
          <input
            v-model="email"
            type="email"
            required
            placeholder="Váš e-mail"
            class="w-full rounded-full border-0 px-5 py-3.5 text-brand-ink placeholder:text-slate-400 focus:ring-2 focus:ring-brand-accent"
          />
          <button
            type="submit"
            :disabled="sending"
            class="shrink-0 rounded-full bg-brand-dark px-7 py-3.5 font-semibold text-white transition-colors hover:bg-brand-ink disabled:opacity-60"
          >
            {{ sending ? '...' : sent ? 'Hotovo ✓' : 'Odebírat' }}
          </button>
        </form>
      </div>
      <p v-if="error" class="-mt-12 mb-12 text-center text-sm text-red-300">{{ error }}</p>

      <!-- Columns -->
      <div class="grid grid-cols-1 gap-10 pb-14 sm:grid-cols-2 lg:grid-cols-4">
        <div>
          <NuxtLink :to="homeTo" class="flex items-center gap-2.5">
            <span class="flex size-11 items-center justify-center rounded-2xl bg-brand text-white">
              <svg class="size-6" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M12 2C7 6 5 10 5 14a7 7 0 0 0 14 0c0-4-2-8-7-12Zm0 17a5 5 0 0 1-5-5c0-2.5 1.2-5.3 5-8.4V19Z"
                />
              </svg>
            </span>
            <span class="text-xl font-extrabold text-white">{{ demo.brandName }}</span>
          </NuxtLink>
          <p class="mt-5 text-sm leading-relaxed text-white/60">{{ aboutText }}</p>
          <div class="mt-6 flex gap-3">
            <a
              v-for="s in social"
              :key="s"
              href="#"
              class="flex size-10 items-center justify-center rounded-full bg-white/10 text-white/80 transition-colors hover:bg-brand hover:text-white"
              :aria-label="s"
            >
              <span class="material-symbols-outlined text-[18px]">public</span>
            </a>
          </div>
        </div>

        <div>
          <h4 class="mb-5 text-lg font-bold !text-white">Navigace</h4>
          <ul class="flex flex-col gap-3">
            <li v-for="link in links" :key="link.to">
              <NuxtLink
                :to="link.to"
                class="text-sm text-white/60 transition-colors hover:text-brand-accent"
                >{{ link.label }}</NuxtLink
              >
            </li>
          </ul>
        </div>

        <div>
          <h4 class="mb-5 text-lg font-bold !text-white">Služby</h4>
          <ul class="flex flex-col gap-3">
            <li v-for="s in services" :key="s.slug">
              <NuxtLink
                :to="serviceTo(s.slug)"
                class="text-sm text-white/60 transition-colors hover:text-brand-accent"
                >{{ s.name }}</NuxtLink
              >
            </li>
          </ul>
        </div>

        <div>
          <h4 class="mb-5 text-lg font-bold !text-white">Kontakt</h4>
          <ul class="flex flex-col gap-4 text-sm text-white/60">
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand-accent">call</span>
              <a :href="`tel:${demo.phone.replace(/\s/g, '')}`" class="hover:text-white">{{
                demo.phone
              }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand-accent">mail</span>
              <a :href="`mailto:${demo.email}`" class="hover:text-white">{{ demo.email }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand-accent">location_on</span>
              <span>{{ demo.address }}</span>
            </li>
          </ul>
        </div>
      </div>

      <div
        class="flex flex-col items-center justify-between gap-3 border-t border-white/10 py-7 text-sm text-white/50 sm:flex-row"
      >
        <p>© {{ currentYear }} {{ demo.brandName }}. Demo prezentace — WebPulse.</p>
        <div class="flex gap-6">
          <NuxtLink to="#" class="hover:text-white">Ochrana soukromí</NuxtLink>
          <NuxtLink to="#" class="hover:text-white">Obchodní podmínky</NuxtLink>
        </div>
      </div>
    </div>
  </footer>

  <!-- ====================== TREE — light / cream footer ====================== -->
  <footer v-else-if="variant === 'tree'" class="bg-brand-cream text-brand-ink">
    <div class="container-x pt-20">
      <!-- Newsletter band (light, pill input + button) -->
      <div
        class="reveal mb-16 flex flex-col gap-6 rounded-3xl border border-brand/15 bg-white p-8 text-center shadow-sm sm:p-12 lg:flex-row lg:items-center lg:justify-between lg:text-left"
      >
        <div class="max-w-md">
          <h3 class="text-2xl font-bold text-brand-ink sm:text-3xl">Zůstaňte v obraze</h3>
          <p class="mt-2 text-brand-muted">
            Tipy k péči o stromy a novinky z oboru jednou měsíčně.
          </p>
        </div>
        <form class="flex w-full max-w-md flex-col gap-3 sm:flex-row" @submit.prevent="subscribe">
          <input
            v-model="email"
            type="email"
            required
            placeholder="Váš e-mail"
            class="w-full rounded-full border border-brand/20 bg-brand-cream px-5 py-3.5 text-brand-ink placeholder:text-brand-muted focus:border-brand focus:ring-2 focus:ring-brand/30"
          />
          <button
            type="submit"
            :disabled="sending"
            class="shrink-0 rounded-full bg-brand px-7 py-3.5 font-semibold text-white transition-colors hover:bg-brand-dark disabled:opacity-60"
          >
            {{ sending ? '...' : sent ? 'Hotovo ✓' : 'Odebírat' }}
          </button>
        </form>
      </div>
      <p v-if="error" class="-mt-12 mb-12 text-center text-sm text-red-600">{{ error }}</p>

      <!-- Columns -->
      <div class="grid grid-cols-1 gap-10 pb-14 sm:grid-cols-2 lg:grid-cols-4">
        <div>
          <NuxtLink :to="homeTo" class="flex items-center gap-2.5">
            <span class="flex size-11 items-center justify-center rounded-2xl bg-brand text-white">
              <svg class="size-6" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M12 2C7 6 5 10 5 14a7 7 0 0 0 14 0c0-4-2-8-7-12Zm0 17a5 5 0 0 1-5-5c0-2.5 1.2-5.3 5-8.4V19Z"
                />
              </svg>
            </span>
            <span class="text-xl font-extrabold text-brand-ink">{{ demo.brandName }}</span>
          </NuxtLink>
          <p class="mt-5 text-sm leading-relaxed text-brand-muted">{{ aboutText }}</p>
          <div class="mt-6 flex gap-3">
            <a
              v-for="s in social"
              :key="s"
              href="#"
              class="flex size-10 items-center justify-center rounded-full bg-brand/10 text-brand transition-colors hover:bg-brand hover:text-white"
              :aria-label="s"
            >
              <span class="material-symbols-outlined text-[18px]">public</span>
            </a>
          </div>
        </div>

        <div>
          <h4 class="mb-5 text-lg font-bold text-brand-ink">Navigace</h4>
          <ul class="flex flex-col gap-3">
            <li v-for="link in links" :key="link.to">
              <NuxtLink
                :to="link.to"
                class="text-sm text-brand-muted transition-colors hover:text-brand"
                >{{ link.label }}</NuxtLink
              >
            </li>
          </ul>
        </div>

        <div>
          <h4 class="mb-5 text-lg font-bold text-brand-ink">Služby</h4>
          <ul class="flex flex-col gap-3">
            <li v-for="s in services" :key="s.slug">
              <NuxtLink
                :to="serviceTo(s.slug)"
                class="text-sm text-brand-muted transition-colors hover:text-brand"
                >{{ s.name }}</NuxtLink
              >
            </li>
          </ul>
        </div>

        <div>
          <h4 class="mb-5 text-lg font-bold text-brand-ink">Kontakt</h4>
          <ul class="flex flex-col gap-4 text-sm text-brand-muted">
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">call</span>
              <a :href="`tel:${demo.phone.replace(/\s/g, '')}`" class="hover:text-brand">{{
                demo.phone
              }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">mail</span>
              <a :href="`mailto:${demo.email}`" class="hover:text-brand">{{ demo.email }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">location_on</span>
              <span>{{ demo.address }}</span>
            </li>
          </ul>
        </div>
      </div>

      <div
        class="flex flex-col items-center justify-between gap-3 border-t border-brand/10 py-7 text-sm text-brand-muted sm:flex-row"
      >
        <p>© {{ currentYear }} {{ demo.brandName }}. Demo prezentace — WebPulse.</p>
        <div class="flex gap-6">
          <NuxtLink to="#" class="hover:text-brand">Ochrana soukromí</NuxtLink>
          <NuxtLink to="#" class="hover:text-brand">Obchodní podmínky</NuxtLink>
        </div>
      </div>
    </div>
  </footer>

  <!-- ================= RESTAURANT — elegant light / cream footer ================= -->
  <footer v-else-if="variant === 'restaurant'" class="bg-brand-cream text-brand-ink">
    <div class="container-x pt-20">
      <!-- Newsletter band (clean, pill input + gold button) -->
      <div
        class="reveal mb-16 flex flex-col gap-6 rounded-3xl border border-brand/20 bg-white p-8 text-center shadow-sm sm:p-12 lg:flex-row lg:items-center lg:justify-between lg:text-left"
      >
        <div class="max-w-md">
          <h3 class="text-2xl uppercase tracking-wide text-brand-ink sm:text-3xl">
            Odebírejte novinky a nabídky
          </h3>
          <p class="mt-2 text-brand-muted">Sezónní menu, akce a události jednou měsíčně.</p>
        </div>
        <form class="flex w-full max-w-md flex-col gap-3 sm:flex-row" @submit.prevent="subscribe">
          <input
            v-model="email"
            type="email"
            required
            placeholder="Váš e-mail"
            class="w-full rounded-full border border-brand/20 bg-brand-cream px-5 py-3.5 text-brand-ink placeholder:text-brand-muted focus:border-brand focus:ring-2 focus:ring-brand/30"
          />
          <button
            type="submit"
            :disabled="sending"
            class="shrink-0 rounded-full bg-brand px-7 py-3.5 font-semibold text-white transition-colors hover:bg-brand-dark disabled:opacity-60"
          >
            {{ sending ? '...' : sent ? 'Hotovo ✓' : 'Odebírat' }}
          </button>
        </form>
      </div>
      <p v-if="error" class="-mt-12 mb-12 text-center text-sm text-brand-accent">{{ error }}</p>

      <!-- Columns -->
      <div class="grid grid-cols-1 gap-10 pb-14 sm:grid-cols-2 lg:grid-cols-4">
        <!-- O restauraci -->
        <div>
          <NuxtLink :to="homeTo" class="flex flex-col leading-none">
            <span class="text-2xl uppercase tracking-wide text-brand-ink">{{
              demo.brandName
            }}</span>
            <span class="mt-1 text-[11px] uppercase tracking-[0.3em] text-brand">{{
              demo.industry
            }}</span>
          </NuxtLink>
          <p class="mt-5 text-sm leading-relaxed text-brand-muted">{{ aboutText }}</p>
          <div class="mt-6 flex gap-3">
            <a
              v-for="s in social"
              :key="s"
              href="#"
              class="flex size-10 items-center justify-center rounded-full bg-brand/10 text-brand transition-colors hover:bg-brand hover:text-white"
              :aria-label="s"
            >
              <span class="material-symbols-outlined text-[18px]">public</span>
            </a>
          </div>
        </div>

        <!-- Rezervace -->
        <div>
          <h4 class="mb-5 text-lg uppercase tracking-wide text-brand-ink">Rezervace</h4>
          <ul class="flex flex-col gap-4 text-sm text-brand-muted">
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">call</span>
              <a :href="`tel:${demo.phone.replace(/\s/g, '')}`" class="hover:text-brand">{{
                demo.phone
              }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">restaurant</span>
              <NuxtLink :to="contactTo" class="hover:text-brand">Rezervovat stůl</NuxtLink>
            </li>
          </ul>
          <ul class="mt-5 flex flex-col gap-2.5">
            <li v-for="link in links" :key="link.to">
              <NuxtLink
                :to="link.to"
                class="text-sm text-brand-muted transition-colors hover:text-brand"
                >{{ link.label }}</NuxtLink
              >
            </li>
          </ul>
        </div>

        <!-- Otevírací doba -->
        <div>
          <h4 class="mb-5 text-lg uppercase tracking-wide text-brand-ink">Otevírací doba</h4>
          <ul class="flex flex-col gap-2.5">
            <li
              v-for="h in restaurantHours"
              :key="h.day"
              class="flex items-center justify-between border-b border-brand/10 pb-2.5 text-sm"
            >
              <span class="text-brand-muted">{{ h.day }}</span>
              <span class="font-medium text-brand-ink">{{ h.time }}</span>
            </li>
          </ul>
        </div>

        <!-- Kontakt -->
        <div>
          <h4 class="mb-5 text-lg uppercase tracking-wide text-brand-ink">Kontakt</h4>
          <ul class="flex flex-col gap-4 text-sm text-brand-muted">
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">mail</span>
              <a :href="`mailto:${demo.email}`" class="hover:text-brand">{{ demo.email }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">location_on</span>
              <span>{{ demo.address }}</span>
            </li>
          </ul>
        </div>
      </div>

      <div
        class="flex flex-col items-center justify-between gap-3 border-t border-brand/10 py-7 text-sm text-brand-muted sm:flex-row"
      >
        <p>© {{ currentYear }} {{ demo.brandName }}. Demo prezentace — WebPulse.</p>
        <div class="flex gap-6">
          <NuxtLink to="#" class="hover:text-brand">Ochrana soukromí</NuxtLink>
          <NuxtLink to="#" class="hover:text-brand">Obchodní podmínky</NuxtLink>
        </div>
      </div>
    </div>
  </footer>

  <!-- ================= LAWYER — elegant navy (dark) footer ================= -->
  <footer
    v-else-if="variant === 'lawyer'"
    class="relative overflow-hidden bg-brand-dark text-white"
  >
    <!-- Top CTA band — signature "Potřebujete právní pomoc?" -->
    <div class="border-b border-white/10 bg-brand/10">
      <div
        class="container-x flex flex-col items-center gap-6 py-12 text-center lg:flex-row lg:justify-between lg:text-left"
      >
        <div class="max-w-xl">
          <h3
            class="text-2xl italic !text-white [font-family:'Playfair_Display',serif] sm:text-3xl lg:text-4xl"
          >
            Potřebujete právní pomoc?
          </h3>
          <p class="mt-2 text-white/70">
            První konzultaci vyřídíme rychle, diskrétně a bez jakýchkoli závazků.
          </p>
        </div>
        <ThemeButton :to="contactTo" variant="accent" size="lg">Nezávazná konzultace</ThemeButton>
      </div>
    </div>

    <div class="container-x relative pt-16">
      <!-- Newsletter band -->
      <div
        class="reveal mb-16 flex flex-col gap-6 rounded-2xl border border-white/10 bg-white/5 p-8 sm:p-10 lg:flex-row lg:items-center lg:justify-between"
      >
        <div class="max-w-md">
          <h3 class="text-xl font-semibold !text-white sm:text-2xl">Právní novinky do e-mailu</h3>
          <p class="mt-2 text-sm text-white/60">
            Přehled změn v legislativě a praktické tipy jednou měsíčně.
          </p>
        </div>
        <form class="flex w-full max-w-md flex-col gap-3 sm:flex-row" @submit.prevent="subscribe">
          <input
            v-model="email"
            type="email"
            required
            placeholder="Váš e-mail"
            class="w-full rounded-full border border-white/15 bg-white/5 px-5 py-3.5 text-white placeholder:text-white/40 focus:border-brand focus:ring-2 focus:ring-brand/40"
          />
          <button
            type="submit"
            :disabled="sending"
            class="shrink-0 rounded-full bg-brand px-7 py-3.5 font-semibold text-white transition-colors hover:bg-brand-dark disabled:opacity-60"
          >
            {{ sending ? '...' : sent ? 'Hotovo ✓' : 'Odebírat' }}
          </button>
        </form>
      </div>
      <p v-if="error" class="-mt-12 mb-12 text-center text-sm text-red-300">{{ error }}</p>

      <!-- Columns -->
      <div class="grid grid-cols-1 gap-10 pb-14 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Brand -->
        <div>
          <NuxtLink :to="homeTo" class="flex flex-col leading-none">
            <span class="text-2xl italic text-white [font-family:'Playfair_Display',serif]">{{
              demo.brandName
            }}</span>
            <span class="mt-1 text-[11px] uppercase tracking-[0.28em] text-brand">{{
              demo.industry
            }}</span>
          </NuxtLink>
          <p class="mt-5 text-sm leading-relaxed text-white/60">{{ aboutText }}</p>
          <div class="mt-6 flex gap-3">
            <a
              v-for="s in social"
              :key="s"
              href="#"
              class="flex size-10 items-center justify-center rounded-full bg-white/10 text-white/80 transition-colors hover:bg-brand hover:text-white"
              :aria-label="s"
            >
              <span class="material-symbols-outlined text-[18px]">public</span>
            </a>
          </div>
        </div>

        <!-- Právní služby -->
        <div>
          <h4 class="mb-5 text-lg font-semibold !text-white">Právní služby</h4>
          <ul class="flex flex-col gap-3">
            <li v-for="s in services" :key="s.slug">
              <NuxtLink
                :to="serviceTo(s.slug)"
                class="flex items-start gap-2 text-sm text-white/60 transition-colors hover:text-brand"
              >
                <span class="material-symbols-outlined text-[18px] text-brand">chevron_right</span>
                {{ s.name }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Kontakt -->
        <div>
          <h4 class="mb-5 text-lg font-semibold !text-white">Kontakt</h4>
          <ul class="flex flex-col gap-4 text-sm text-white/60">
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">call</span>
              <a :href="`tel:${demo.phone.replace(/\s/g, '')}`" class="hover:text-white">{{
                demo.phone
              }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">mail</span>
              <a :href="`mailto:${demo.email}`" class="hover:text-white">{{ demo.email }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand">location_on</span>
              <span>{{ demo.address }}</span>
            </li>
          </ul>
        </div>

        <!-- Otevírací doba -->
        <div>
          <h4 class="mb-5 text-lg font-semibold !text-white">Otevírací doba</h4>
          <ul class="flex flex-col gap-2.5">
            <li
              v-for="h in lawyerHours"
              :key="h.day"
              class="flex items-center justify-between border-b border-white/10 pb-2.5 text-sm"
            >
              <span class="text-white/70">{{ h.day }}</span>
              <span class="font-medium text-white">{{ h.time }}</span>
            </li>
          </ul>
        </div>
      </div>

      <div
        class="flex flex-col items-center justify-between gap-3 border-t border-white/10 py-7 text-sm text-white/50 sm:flex-row"
      >
        <p>© {{ currentYear }} {{ demo.brandName }}. Demo prezentace — WebPulse.</p>
        <div class="flex gap-6">
          <NuxtLink to="#" class="hover:text-white">Ochrana soukromí</NuxtLink>
          <NuxtLink to="#" class="hover:text-white">Obchodní podmínky</NuxtLink>
        </div>
      </div>
    </div>
  </footer>

  <!-- =============== LANDSCAPING — dark footer, big faint word =============== -->
  <footer v-else class="relative overflow-hidden bg-brand-dark text-white">
    <div class="container-x relative pt-16">
      <!-- CTA band: "Naplánujte konzultaci" with inline email + accent button -->
      <div
        class="reveal mb-14 flex flex-col gap-8 border-b border-white/10 pb-14 lg:flex-row lg:items-center lg:justify-between"
      >
        <div class="max-w-xl">
          <h3 class="text-2xl font-bold !text-white sm:text-3xl lg:text-4xl">
            Naplánujte konzultaci
          </h3>
          <p class="mt-2 text-white/60">
            Pošlete nám e-mail a navrhneme řešení pro váš pozemek nebo projekt.
          </p>
        </div>
        <form class="relative flex w-full max-w-md items-center" @submit.prevent="subscribe">
          <input
            v-model="email"
            type="email"
            required
            placeholder="Váš e-mail"
            class="w-full rounded-full border border-white/15 bg-white/5 py-4 pl-6 pr-44 text-white placeholder:text-white/40 focus:border-brand-accent focus:ring-2 focus:ring-brand-accent/40"
          />
          <button
            type="submit"
            :disabled="sending"
            class="absolute right-1.5 inline-flex shrink-0 items-center gap-2 rounded-full bg-brand-accent px-6 py-3 font-semibold text-brand-ink transition-colors hover:bg-white disabled:opacity-60"
          >
            <span class="material-symbols-outlined text-[18px]">north_east</span>
            {{ sending ? '...' : sent ? 'Hotovo ✓' : 'Odeslat' }}
          </button>
        </form>
      </div>
      <p v-if="error" class="-mt-10 mb-10 text-center text-sm text-red-300">{{ error }}</p>

      <!-- Columns -->
      <div class="grid grid-cols-1 gap-10 pb-10 sm:grid-cols-2 lg:grid-cols-12">
        <!-- Brand + working hours -->
        <div class="lg:col-span-4">
          <NuxtLink :to="homeTo" class="flex items-center gap-2.5">
            <span class="flex size-11 items-center justify-center rounded-2xl bg-brand text-white">
              <svg class="size-6" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M12 2C7 6 5 10 5 14a7 7 0 0 0 14 0c0-4-2-8-7-12Zm0 17a5 5 0 0 1-5-5c0-2.5 1.2-5.3 5-8.4V19Z"
                />
              </svg>
            </span>
            <span class="text-xl font-extrabold text-white">{{ demo.brandName }}</span>
          </NuxtLink>
          <p class="mt-5 text-sm leading-relaxed text-white/60">{{ aboutText }}</p>

          <div class="mt-7">
            <h4 class="mb-4 text-sm font-bold uppercase tracking-wide text-brand-accent">
              Otevírací doba
            </h4>
            <ul class="flex flex-col gap-2.5">
              <li
                v-for="h in hours"
                :key="h.day"
                class="flex items-center justify-between border-b border-white/10 pb-2.5 text-sm"
              >
                <span class="text-white/70">{{ h.day }}</span>
                <span class="font-medium text-white">{{ h.time }}</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="lg:col-span-2">
          <h4 class="mb-5 text-lg font-bold !text-white">Navigace</h4>
          <ul class="flex flex-col gap-3">
            <li v-for="link in links" :key="link.to">
              <NuxtLink
                :to="link.to"
                class="text-sm text-white/60 transition-colors hover:text-brand-accent"
                >{{ link.label }}</NuxtLink
              >
            </li>
          </ul>
        </div>

        <div class="lg:col-span-3">
          <h4 class="mb-5 text-lg font-bold !text-white">Služby</h4>
          <ul class="flex flex-col gap-3">
            <li v-for="s in services" :key="s.slug">
              <NuxtLink
                :to="serviceTo(s.slug)"
                class="text-sm text-white/60 transition-colors hover:text-brand-accent"
                >{{ s.name }}</NuxtLink
              >
            </li>
          </ul>
        </div>

        <div class="lg:col-span-3">
          <h4 class="mb-5 text-lg font-bold !text-white">Kontakt</h4>
          <ul class="flex flex-col gap-4 text-sm text-white/60">
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand-accent">call</span>
              <a :href="`tel:${demo.phone.replace(/\s/g, '')}`" class="hover:text-white">{{
                demo.phone
              }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand-accent">mail</span>
              <a :href="`mailto:${demo.email}`" class="hover:text-white">{{ demo.email }}</a>
            </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-brand-accent">location_on</span>
              <span>{{ demo.address }}</span>
            </li>
          </ul>
          <div class="mt-6 flex gap-3">
            <a
              v-for="s in social"
              :key="s"
              href="#"
              class="flex size-10 items-center justify-center rounded-full bg-white/10 text-white/80 transition-colors hover:bg-brand hover:text-white"
              :aria-label="s"
            >
              <span class="material-symbols-outlined text-[18px]">public</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Big faint background-style title -->
      <div class="pointer-events-none select-none overflow-hidden pb-4 text-center">
        <span
          class="block text-[18vw] font-extrabold uppercase leading-none tracking-tight text-white/5 lg:text-[12rem]"
        >
          {{ demo.industry }}
        </span>
      </div>

      <div
        class="flex flex-col items-center justify-between gap-3 border-t border-white/10 py-7 text-sm text-white/50 sm:flex-row"
      >
        <p>© {{ currentYear }} {{ demo.brandName }}. Demo prezentace — WebPulse.</p>
        <div class="flex gap-6">
          <NuxtLink to="#" class="hover:text-white">Ochrana soukromí</NuxtLink>
          <NuxtLink to="#" class="hover:text-white">Obchodní podmínky</NuxtLink>
        </div>
      </div>
    </div>
  </footer>
</template>
