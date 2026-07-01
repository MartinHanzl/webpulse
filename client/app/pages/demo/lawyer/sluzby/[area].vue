<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRoute, createError } from '#imports';
import { useLawyerContent } from '~/../app/composables/useLawyerContent';

definePageMeta({ layout: false });

const slug = 'lawyer';
const route = useRoute();
const c = useLawyerContent();

const area = computed(() => c.getArea(String(route.params.area)));
if (!area.value) throw createError({ statusCode: 404, statusMessage: 'Nenalezeno', fatal: true });

const attorneys = computed(() => c.attorneys.slice(0, 4));

const openFaq = ref<number>(0);
const toggle = (i: number): void => {
  openFaq.value = openFaq.value === i ? -1 : i;
};

useHead(() => ({ title: `${area.value?.name} — Veritas` }));
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Oblast práva"
      :title="area!.name"
      :crumbs="[{ label: 'Právní služby', to: '/demo/lawyer/sluzby' }, { label: area!.name }]"
      :image="area!.image"
    />

    <section class="bg-white py-20 lg:py-28">
      <div class="container-x">
        <div class="grid gap-12 lg:grid-cols-12">
          <!-- SIDEBAR -->
          <aside class="lg:col-span-4">
            <div class="rounded-3xl bg-brand-cream p-6">
              <h3 class="text-lg">Oblasti práva</h3>
              <ul class="mt-4 space-y-2">
                <li v-for="a in c.practiceAreas" :key="a.slug">
                  <NuxtLink
                    :to="`/demo/lawyer/sluzby/${a.slug}`"
                    class="flex items-center justify-between rounded-xl px-4 py-3 text-sm font-medium transition-colors"
                    :class="
                      a.slug === area!.slug
                        ? 'bg-brand text-white'
                        : 'bg-white text-brand-ink hover:bg-brand-soft'
                    "
                  >
                    <span class="flex items-center gap-2.5">
                      <span class="material-symbols-outlined text-[20px]">{{ a.icon }}</span>
                      {{ a.name }}
                    </span>
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                  </NuxtLink>
                </li>
              </ul>
            </div>

            <!-- CONTACT MINI-CTA -->
            <div class="mt-6 overflow-hidden rounded-3xl bg-brand-dark p-8 text-white">
              <span class="material-symbols-outlined text-4xl text-brand">support_agent</span>
              <h3 class="mt-4 text-xl !text-white">Jak vám můžeme pomoci?</h3>
              <p class="mt-3 text-sm leading-relaxed text-white/70">
                Popište nám svůj případ. První nezávazná konzultace je zdarma.
              </p>
              <a
                href="tel:+420234567800"
                class="mt-5 flex items-center gap-2 text-lg font-semibold text-brand"
              >
                <span class="material-symbols-outlined">call</span>
                +420 234 567 800
              </a>
              <ThemeButton to="/demo/lawyer/kontakt" variant="accent" size="md" class="mt-6 w-full"
                >Nezávazná konzultace</ThemeButton
              >
            </div>
          </aside>

          <!-- CONTENT -->
          <div class="lg:col-span-8">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-8 bg-brand" />
              {{ area!.name }}
            </p>
            <h2 class="mt-4 text-3xl leading-tight sm:text-4xl">{{ area!.name }}</h2>
            <p class="mt-6 text-lg leading-relaxed text-brand-muted">{{ area!.description }}</p>

            <div class="mt-8 overflow-hidden rounded-3xl">
              <img :src="area!.image" alt="" class="h-[380px] w-full object-cover" />
            </div>

            <h3 class="mt-12 text-2xl">Co pro vás zajistíme</h3>
            <ul class="mt-6 grid gap-4 sm:grid-cols-2">
              <li
                v-for="feature in area!.features"
                :key="feature"
                class="flex items-start gap-3 rounded-2xl bg-brand-cream p-4"
              >
                <span class="material-symbols-outlined mt-0.5 text-brand">task_alt</span>
                <span class="font-medium text-brand-ink">{{ feature }}</span>
              </li>
            </ul>

            <!-- FAQ ACCORDION -->
            <h3 class="mt-12 text-2xl">Časté dotazy</h3>
            <div class="mt-6 space-y-3">
              <div
                v-for="(faq, i) in c.faqs"
                :key="faq.question"
                class="overflow-hidden rounded-2xl border border-brand-dark/10"
              >
                <button
                  type="button"
                  class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left transition-colors hover:bg-brand-cream"
                  @click="toggle(i)"
                >
                  <span class="text-base font-semibold text-brand-ink">{{ faq.question }}</span>
                  <span
                    class="material-symbols-outlined shrink-0 text-brand transition-transform duration-300"
                    :class="openFaq === i ? 'rotate-45' : ''"
                    >add</span
                  >
                </button>
                <div v-show="openFaq === i" class="px-6 pb-5 text-brand-muted">
                  {{ faq.answer }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ATTORNEYS -->
    <section class="bg-brand-cream py-20 lg:py-28">
      <div class="container-x">
        <ThemeSectionHeading
          subtitle="Náš tým"
          title="Advokáti pro tuto oblast"
          text="Zkušení odborníci, kteří se o váš případ osobně postarají."
          align="center"
        />
        <div class="mt-14 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
          <NuxtLink
            v-for="person in attorneys"
            :key="person.slug"
            :to="`/demo/lawyer/advokati/${person.slug}`"
            class="reveal group overflow-hidden rounded-3xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl"
          >
            <div class="relative overflow-hidden">
              <img
                :src="person.image"
                alt=""
                class="h-72 w-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
            </div>
            <div class="p-6 text-center">
              <h3 class="text-lg">{{ person.name }}</h3>
              <p class="mt-1 text-sm font-semibold text-brand">{{ person.specialization }}</p>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
