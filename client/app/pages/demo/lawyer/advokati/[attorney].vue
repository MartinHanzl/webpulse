<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, createError } from '#imports';
import { useLawyerContent } from '~/../app/composables/useLawyerContent';
import { useStockImages } from '~/../app/composables/useStockImages';

definePageMeta({ layout: false });

const slug = 'lawyer';
const route = useRoute();
const c = useLawyerContent();
const ph = useStockImages().get('lawyer');

const person = computed(() => c.getAttorney(String(route.params.attorney)));
if (!person.value) throw createError({ statusCode: 404, statusMessage: 'Nenalezeno', fatal: true });

const socials = ['public', 'alternate_email', 'call', 'share'];

useHead(() => ({ title: `${person.value?.name} — Veritas` }));
</script>

<template>
  <ThemeInnerLayout v-slot="{ demo }" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Advokát"
      :title="person!.name"
      :crumbs="[{ label: 'Advokáti', to: '/demo/lawyer/advokati' }, { label: person!.name }]"
      :image="ph.hero"
    />

    <section class="bg-white py-20 lg:py-28">
      <div class="container-x">
        <div class="grid gap-12 lg:grid-cols-12">
          <!-- LEFT: BIO -->
          <div class="lg:col-span-7">
            <p
              class="flex items-center gap-3 text-sm font-semibold uppercase tracking-wider text-brand"
            >
              <span class="h-px w-8 bg-brand" />
              {{ person!.role }}
            </p>
            <h2 class="mt-4 text-3xl leading-tight sm:text-4xl">{{ person!.name }}</h2>
            <p class="mt-2 text-lg font-semibold text-brand">{{ person!.specialization }}</p>

            <h3 class="mt-10 text-2xl">Biografie</h3>
            <p class="mt-4 text-lg leading-relaxed text-brand-muted">{{ person!.bio }}</p>
            <p class="mt-4 text-lg leading-relaxed text-brand-muted">
              Ke každému klientovi přistupuje individuálně, s důrazem na srozumitelnou komunikaci a
              transparentní vedení případu. Klade důraz na etiku, diskrétnost a dosažení nejlepšího
              možného výsledku.
            </p>

            <h3 class="mt-10 text-2xl">Vzdělání</h3>
            <ul class="mt-4 space-y-3">
              <li
                v-for="edu in person!.education"
                :key="edu"
                class="flex items-start gap-3 text-brand-ink"
              >
                <span class="material-symbols-outlined mt-0.5 text-brand">school</span>
                <span class="font-medium">{{ edu }}</span>
              </li>
            </ul>

            <h3 class="mt-10 text-2xl">Praxe</h3>
            <div class="mt-4 flex flex-wrap items-center gap-3">
              <span
                class="inline-flex items-center gap-2 rounded-full bg-brand px-5 py-2.5 text-sm font-semibold text-white"
              >
                <span class="material-symbols-outlined text-[20px]">workspace_premium</span>
                {{ person!.experience }} praxe
              </span>
              <span
                class="inline-flex items-center gap-2 rounded-full bg-brand-soft px-5 py-2.5 text-sm font-semibold text-brand-ink"
              >
                <span class="material-symbols-outlined text-[20px]">gavel</span>
                {{ person!.specialization }}
              </span>
            </div>
          </div>

          <!-- RIGHT: STICKY CARD -->
          <aside class="lg:col-span-5">
            <div class="lg:sticky lg:top-28">
              <div class="overflow-hidden rounded-3xl bg-brand-cream shadow-sm">
                <img :src="person!.image" alt="" class="h-96 w-full object-cover" />
                <div class="p-8 text-center">
                  <h3 class="text-2xl">{{ person!.name }}</h3>
                  <p class="mt-1 text-sm uppercase tracking-wider text-brand-muted">
                    {{ person!.role }}
                  </p>
                  <p class="mt-2 text-base font-semibold text-brand">
                    {{ person!.specialization }}
                  </p>

                  <div class="mt-6 flex justify-center gap-2.5">
                    <span
                      v-for="icon in socials"
                      :key="icon"
                      class="flex size-10 items-center justify-center rounded-full bg-white text-brand-dark transition-colors hover:bg-brand hover:text-white"
                    >
                      <span class="material-symbols-outlined text-lg">{{ icon }}</span>
                    </span>
                  </div>

                  <div class="mt-8 space-y-4 border-t border-brand-dark/10 pt-8 text-left">
                    <a
                      :href="`tel:${demo.phone.replace(/\s/g, '')}`"
                      class="flex items-center gap-3 text-brand-ink transition-colors hover:text-brand"
                    >
                      <span
                        class="flex size-11 items-center justify-center rounded-full bg-brand-soft text-brand"
                      >
                        <span class="material-symbols-outlined">call</span>
                      </span>
                      <span>
                        <span class="block text-xs uppercase tracking-wider text-brand-muted"
                          >Telefon</span
                        >
                        <span class="font-semibold">{{ demo.phone }}</span>
                      </span>
                    </a>
                    <a
                      :href="`mailto:${demo.email}`"
                      class="flex items-center gap-3 text-brand-ink transition-colors hover:text-brand"
                    >
                      <span
                        class="flex size-11 items-center justify-center rounded-full bg-brand-soft text-brand"
                      >
                        <span class="material-symbols-outlined">mail</span>
                      </span>
                      <span>
                        <span class="block text-xs uppercase tracking-wider text-brand-muted"
                          >E-mail</span
                        >
                        <span class="font-semibold">{{ demo.email }}</span>
                      </span>
                    </a>
                  </div>

                  <ThemeButton
                    to="/demo/lawyer/kontakt"
                    variant="solid"
                    size="md"
                    class="mt-8 w-full"
                    >Kontaktovat advokáta</ThemeButton
                  >
                </div>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
