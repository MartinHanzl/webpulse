<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { useRoute } from '#app';
import type { DemoDefinition, DemoNavLink } from '~/../app/composables/useDemos';

const props = defineProps<{
  demo: DemoDefinition;
  links: DemoNavLink[];
  transparent?: boolean;
}>();

const scrolled = ref(false);
const mobileOpen = ref(false);

// Solid (white) header → dark text. Transparent over the dark hero → light text.
const solid = computed(() => scrolled.value || !props.transparent);
// Dark-template demos render the solid header on a dark surface.
const darkSolid = computed(() => solid.value && !!props.demo.dark);

// Per-template header design, selected by the demo slug.
const variant = computed(() => props.demo.slug);

// Light text is used over the dark hero (transparent state) and on the dark bar.
const lightText = computed(() => !solid.value || darkSolid.value);

const telHref = computed(() => `tel:${props.demo.phone.replace(/\s/g, '')}`);

// On /demo routes the logo/CTA point into the demo; on the real site they point
// at the real routes.
const route = useRoute();
const isDemo = computed(() => route.path.startsWith('/demo'));
const homeTo = computed(() => (isDemo.value ? `/demo/${props.demo.slug}` : '/'));
const contactTo = computed(() => (isDemo.value ? `/demo/${props.demo.slug}/kontakt` : '/kontakt'));

const onScroll = () => {
  scrolled.value = window.scrollY > 40;
};
onMounted(() => {
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
});
onBeforeUnmount(() => window.removeEventListener('scroll', onScroll));
</script>

<template>
  <header
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
    :class="
      solid
        ? darkSolid
          ? 'border-b border-white/10 bg-neutral-900/90 shadow-sm backdrop-blur-md'
          : 'border-b border-slate-100 bg-white/90 shadow-sm backdrop-blur-md'
        : 'bg-transparent'
    "
  >
    <!-- ============================================================= -->
    <!-- LAWN — clean: left logo, rounded pill nav, green pill CTA      -->
    <!-- ============================================================= -->
    <div
      v-if="variant === 'lawn'"
      class="container-x flex items-center justify-between gap-6 transition-all"
      :class="solid ? 'py-3' : 'py-5'"
    >
      <!-- Logo -->
      <NuxtLink :to="homeTo" class="flex items-center gap-2.5">
        <span
          class="flex size-11 items-center justify-center rounded-2xl bg-brand text-white shadow-lg shadow-brand/30"
        >
          <svg class="size-6" viewBox="0 0 24 24" fill="currentColor">
            <path
              d="M12 2C7 6 5 10 5 14a7 7 0 0 0 14 0c0-4-2-8-7-12Zm0 17a5 5 0 0 1-5-5c0-2.5 1.2-5.3 5-8.4V19Z"
            />
          </svg>
        </span>
        <span class="flex flex-col leading-none">
          <span
            class="text-xl font-extrabold tracking-tight"
            :class="lightText ? 'text-white' : 'text-brand-ink'"
            >{{ demo.brandName }}</span
          >
          <span
            class="text-[11px] font-medium uppercase tracking-wider"
            :class="lightText ? 'text-brand-accent' : 'text-brand'"
            >{{ demo.industry }}</span
          >
        </span>
      </NuxtLink>

      <!-- Desktop nav -->
      <nav class="hidden items-center gap-1 lg:flex">
        <NuxtLink
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          class="rounded-full px-4 py-2 text-[15px] font-semibold transition-colors hover:bg-brand-soft hover:text-brand"
          :class="lightText ? 'text-white/80' : 'text-brand-ink/80'"
        >
          {{ link.label }}
        </NuxtLink>
      </nav>

      <!-- Actions -->
      <div class="flex items-center gap-3">
        <a
          :href="telHref"
          class="hidden items-center gap-2 text-sm font-semibold xl:flex"
          :class="lightText ? 'text-white' : 'text-brand-ink'"
        >
          <span
            class="flex size-9 items-center justify-center rounded-full bg-brand-soft text-brand"
          >
            <span class="material-symbols-outlined text-[20px]">call</span>
          </span>
          {{ demo.phone }}
        </a>
        <ThemeButton :to="contactTo" size="sm" class="hidden sm:inline-flex">Poptávka</ThemeButton>
        <button
          class="flex size-11 items-center justify-center rounded-xl lg:hidden"
          :class="lightText ? 'bg-white/15 text-white' : 'bg-brand-soft text-brand-ink'"
          aria-label="Menu"
          @click="mobileOpen = !mobileOpen"
        >
          <span class="material-symbols-outlined">{{ mobileOpen ? 'close' : 'menu' }}</span>
        </button>
      </div>
    </div>

    <!-- ============================================================= -->
    <!-- TREE — friendly/light: slim top utility bar + pill CTA         -->
    <!-- ============================================================= -->
    <div v-else-if="variant === 'tree'">
      <!-- Top utility bar -->
      <div
        class="hidden border-b transition-all md:block"
        :class="lightText ? 'border-white/15' : 'border-slate-100'"
      >
        <div
          class="container-x flex items-center justify-between gap-6 py-2 text-[13px] font-medium"
          :class="lightText ? 'text-white/85' : 'text-brand-ink/70'"
        >
          <div class="flex items-center gap-6">
            <a :href="telHref" class="flex items-center gap-1.5 transition-colors hover:text-brand">
              <span class="material-symbols-outlined text-[18px] text-brand">call</span>
              {{ demo.phone }}
            </a>
            <a
              :href="`mailto:${demo.email}`"
              class="flex items-center gap-1.5 transition-colors hover:text-brand"
            >
              <span class="material-symbols-outlined text-[18px] text-brand">mail</span>
              {{ demo.email }}
            </a>
          </div>
          <div class="flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[18px] text-brand">schedule</span>
            Po–Pá 8:00–17:00
          </div>
        </div>
      </div>

      <!-- Main bar -->
      <div
        class="container-x flex items-center justify-between gap-6 transition-all"
        :class="solid ? 'py-3' : 'py-4'"
      >
        <!-- Logo with leaf mark -->
        <NuxtLink :to="homeTo" class="flex items-center gap-3">
          <span
            class="flex size-12 items-center justify-center rounded-full bg-brand-soft text-brand ring-4 ring-brand/10"
          >
            <span class="material-symbols-outlined text-[26px]">forest</span>
          </span>
          <span class="flex flex-col leading-tight">
            <span
              class="text-[22px] font-bold tracking-tight"
              :class="lightText ? 'text-white' : 'text-brand-ink'"
              >{{ demo.brandName }}</span
            >
            <span
              class="text-[12px] font-medium"
              :class="lightText ? 'text-white/70' : 'text-brand-ink/50'"
              >{{ demo.tagline }}</span
            >
          </span>
        </NuxtLink>

        <!-- Desktop nav -->
        <nav class="hidden items-center gap-7 lg:flex">
          <NuxtLink
            v-for="link in links"
            :key="link.to"
            :to="link.to"
            class="group relative text-[15px] font-semibold transition-colors hover:text-brand"
            :class="lightText ? 'text-white/85' : 'text-brand-ink/80'"
          >
            {{ link.label }}
            <span
              class="absolute -bottom-1.5 left-0 h-0.5 w-0 rounded-full bg-brand transition-all duration-300 group-hover:w-full"
            />
          </NuxtLink>
        </nav>

        <!-- Actions -->
        <div class="flex items-center gap-3">
          <ThemeButton :to="contactTo" size="sm" class="hidden sm:inline-flex"
            >Nezávazná poptávka</ThemeButton
          >
          <button
            class="flex size-11 items-center justify-center rounded-full lg:hidden"
            :class="lightText ? 'bg-white/15 text-white' : 'bg-brand-soft text-brand'"
            aria-label="Menu"
            @click="mobileOpen = !mobileOpen"
          >
            <span class="material-symbols-outlined">{{ mobileOpen ? 'close' : 'menu' }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- ============================================================= -->
    <!-- RESTAURANT — elegant fine-dining: Bebas wordmark, uppercase    -->
    <!-- inline nav, gold "Rezervovat stůl" CTA; light (cream) solid.   -->
    <!-- ============================================================= -->
    <div
      v-else-if="variant === 'restaurant'"
      class="container-x flex items-center justify-between gap-6 transition-all"
      :class="solid ? 'py-3' : 'py-5'"
    >
      <!-- Bebas wordmark logo -->
      <NuxtLink :to="homeTo" class="flex flex-col leading-none">
        <span
          class="text-[26px] uppercase tracking-wide"
          :class="lightText ? 'text-white' : 'text-brand-ink'"
          >{{ demo.brandName }}</span
        >
        <span class="mt-0.5 text-[11px] uppercase tracking-[0.3em] text-brand">{{
          demo.industry
        }}</span>
      </NuxtLink>

      <!-- Desktop nav -->
      <nav class="hidden items-center gap-8 lg:flex">
        <NuxtLink
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          class="text-sm uppercase tracking-wide transition-colors hover:text-brand"
          :class="lightText ? 'text-white/85' : 'text-brand-ink/80'"
        >
          {{ link.label }}
        </NuxtLink>
      </nav>

      <!-- Actions -->
      <div class="flex items-center gap-4">
        <a
          :href="telHref"
          class="hidden items-center gap-2 text-sm font-medium xl:flex"
          :class="lightText ? 'text-white' : 'text-brand-ink'"
        >
          <span class="material-symbols-outlined text-[20px] text-brand">call</span>
          {{ demo.phone }}
        </a>
        <NuxtLink
          :to="contactTo"
          class="hidden items-center gap-2 rounded-full border px-6 py-2.5 text-xs uppercase tracking-wide transition-all hover:-translate-y-0.5 sm:inline-flex"
          :class="
            lightText
              ? 'border-white/40 text-white hover:border-brand hover:bg-brand'
              : 'border-brand text-brand hover:bg-brand hover:text-white'
          "
        >
          <span class="material-symbols-outlined text-[18px]">restaurant</span>
          Rezervovat stůl
        </NuxtLink>
        <button
          class="flex size-11 items-center justify-center rounded-full lg:hidden"
          :class="lightText ? 'bg-white/15 text-white' : 'bg-brand-cream text-brand-ink'"
          aria-label="Menu"
          @click="mobileOpen = !mobileOpen"
        >
          <span class="material-symbols-outlined">{{ mobileOpen ? 'close' : 'menu' }}</span>
        </button>
      </div>
    </div>

    <!-- ============================================================= -->
    <!-- LANDSCAPING — bold/dark: uppercase tracked menu, square accent -->
    <!-- CTA, social dots; solid state is dark.                         -->
    <!-- ============================================================= -->
    <div
      v-else
      class="container-x flex items-center justify-between gap-6 transition-all"
      :class="solid ? 'py-3.5' : 'py-5'"
    >
      <!-- Square wordmark logo -->
      <NuxtLink :to="homeTo" class="flex items-center gap-3">
        <span
          class="flex size-11 items-center justify-center rounded-md bg-brand-accent text-brand-dark shadow-lg shadow-brand-accent/30"
        >
          <span class="material-symbols-outlined text-[24px]">grass</span>
        </span>
        <span class="flex flex-col leading-none">
          <span class="text-xl font-extrabold uppercase tracking-[0.12em] text-white">{{
            demo.brandName
          }}</span>
          <span class="text-[10px] font-semibold uppercase tracking-[0.3em] text-brand-accent">{{
            demo.industry
          }}</span>
        </span>
      </NuxtLink>

      <!-- Desktop nav -->
      <nav class="hidden items-center gap-7 xl:flex">
        <NuxtLink
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          class="text-[13px] font-bold uppercase tracking-[0.14em] text-white/75 transition-colors hover:text-brand-accent"
        >
          {{ link.label }}
        </NuxtLink>
      </nav>

      <!-- Actions -->
      <div class="flex items-center gap-4">
        <!-- Social dots -->
        <div class="hidden items-center gap-2 xl:flex">
          <a
            v-for="s in ['public', 'photo_camera', 'thumb_up']"
            :key="s"
            href="#"
            class="flex size-9 items-center justify-center rounded-full border border-white/20 text-white/70 transition-colors hover:border-brand-accent hover:text-brand-accent"
          >
            <span class="material-symbols-outlined text-[18px]">{{ s }}</span>
          </a>
        </div>
        <a
          :href="telHref"
          class="hidden items-center gap-2 text-sm font-bold tracking-tight text-white lg:flex"
        >
          <span class="material-symbols-outlined text-[20px] text-brand-accent">call</span>
          {{ demo.phone }}
        </a>
        <NuxtLink
          :to="contactTo"
          class="hidden items-center gap-2 rounded-md bg-brand-accent px-6 py-3 text-xs font-bold uppercase tracking-[0.12em] text-brand-dark shadow-lg shadow-brand-accent/30 transition-all hover:-translate-y-0.5 hover:brightness-95 sm:inline-flex"
        >
          Poptávka
          <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </NuxtLink>
        <button
          class="flex size-11 items-center justify-center rounded-md bg-white/10 text-white xl:hidden"
          aria-label="Menu"
          @click="mobileOpen = !mobileOpen"
        >
          <span class="material-symbols-outlined">{{ mobileOpen ? 'close' : 'menu' }}</span>
        </button>
      </div>
    </div>

    <!-- ============================================================= -->
    <!-- Shared mobile menu                                             -->
    <!-- ============================================================= -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-2"
    >
      <div v-if="mobileOpen" class="container-x mt-3 pb-3 xl:hidden">
        <div
          class="flex flex-col gap-1 rounded-2xl p-3 shadow-xl"
          :class="
            variant === 'restaurant'
              ? 'border border-white/10 bg-brand-dark'
              : 'border border-slate-100 bg-white'
          "
        >
          <NuxtLink
            v-for="link in links"
            :key="link.to"
            :to="link.to"
            class="rounded-xl px-4 py-3 text-base transition-colors"
            :class="
              variant === 'restaurant'
                ? 'font-medium uppercase tracking-wide text-white hover:bg-white/10 hover:text-brand'
                : 'font-semibold text-brand-ink hover:bg-brand-soft hover:text-brand'
            "
            @click="mobileOpen = false"
          >
            {{ link.label }}
          </NuxtLink>
          <a
            :href="telHref"
            class="flex items-center gap-2 rounded-xl px-4 py-3 text-base font-semibold"
            :class="variant === 'restaurant' ? 'text-white' : 'text-brand-ink'"
            @click="mobileOpen = false"
          >
            <span class="material-symbols-outlined text-[20px] text-brand">call</span>
            {{ demo.phone }}
          </a>
          <ThemeButton :to="contactTo" class="mt-2" @click="mobileOpen = false">{{
            variant === 'restaurant' ? 'Rezervovat stůl' : 'Nezávazná poptávka'
          }}</ThemeButton>
        </div>
      </div>
    </transition>
  </header>
</template>
