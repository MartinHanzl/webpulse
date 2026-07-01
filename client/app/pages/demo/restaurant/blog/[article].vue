<script setup lang="ts">
import { computed } from 'vue';
import { useRoute, createError } from '#imports';
import { useRestaurantContent } from '~/../app/composables/useRestaurantContent';

definePageMeta({ layout: false });

const slug = 'restaurant';
const c = useRestaurantContent();

const route = useRoute();
const article = computed(() => c.getArticle(String(route.params.article)));
if (!article.value)
  throw createError({ statusCode: 404, statusMessage: 'Nenalezeno', fatal: true });

const others = computed(() =>
  c.articles.filter((a) => a.slug !== route.params.article).slice(0, 3),
);

useHead(() => ({ title: `${article.value?.title} — Savoria` }));
</script>

<template>
  <ThemeInnerLayout v-if="article" :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      subtitle="Blog"
      :title="article.title"
      :image="article.image"
      :crumbs="[{ label: 'Blog', to: `/demo/${slug}/blog` }, { label: article.category }]"
    />

    <!-- ARTICLE -->
    <article class="bg-white py-24">
      <div class="container-x">
        <div class="mx-auto max-w-3xl">
          <div class="reveal flex flex-wrap items-center gap-4 text-sm">
            <span
              class="rounded-full bg-brand px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-dark"
            >
              {{ article.category }}
            </span>
            <span class="flex items-center gap-2 text-brand-muted">
              <span class="material-symbols-outlined text-lg text-brand-accent"
                >calendar_today</span
              >
              {{ article.date }}
            </span>
          </div>

          <h1 class="reveal mt-6 text-4xl uppercase leading-tight tracking-wide sm:text-5xl">
            {{ article.title }}
          </h1>
        </div>

        <div class="reveal mx-auto mt-10 max-w-4xl overflow-hidden rounded-3xl shadow-sm">
          <img :src="article.image" :alt="article.title" class="h-[420px] w-full object-cover" />
        </div>

        <div class="mx-auto mt-12 max-w-3xl">
          <p class="reveal text-xl font-medium leading-relaxed text-brand-ink">
            {{ article.perex }}
          </p>
          <p
            v-for="(p, i) in article.body"
            :key="i"
            class="reveal mt-6 text-lg leading-relaxed text-brand-muted"
          >
            {{ p }}
          </p>

          <div class="reveal mt-12 border-t border-brand-ink/10 pt-8">
            <ThemeButton :to="`/demo/${slug}/blog`" variant="outline" size="md" :icon="false">
              ← Zpět na blog
            </ThemeButton>
          </div>
        </div>
      </div>
    </article>

    <!-- FURTHER ARTICLES -->
    <section class="bg-brand-cream py-24">
      <div class="container-x">
        <ThemeSectionHeading subtitle="Čtěte dál" title="Další články" align="center" />

        <div class="mt-14 grid gap-8 md:grid-cols-3">
          <NuxtLink
            v-for="a in others"
            :key="a.slug"
            :to="`/demo/${slug}/blog/${a.slug}`"
            class="reveal group flex flex-col overflow-hidden rounded-3xl border border-brand-ink/5 bg-white shadow-sm transition-transform duration-300 hover:-translate-y-2"
          >
            <div class="relative h-52 overflow-hidden">
              <img
                :src="a.image"
                :alt="a.title"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-105"
              />
              <span
                class="absolute left-5 top-5 rounded-full bg-brand px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-brand-dark shadow"
              >
                {{ a.category }}
              </span>
            </div>
            <div class="flex flex-1 flex-col p-6">
              <span class="text-sm text-brand-muted">{{ a.date }}</span>
              <h3 class="mt-2 text-lg uppercase leading-snug tracking-wide">{{ a.title }}</h3>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
