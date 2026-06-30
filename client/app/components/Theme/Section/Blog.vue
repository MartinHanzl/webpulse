<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useApi } from '~/../app/composables/useApi';
import type { Post } from '~/types/Post';

interface FallbackPost {
  name: string;
  perex: string;
  image: string;
  category: string;
  date: string;
}

defineProps<{
  subtitle: string;
  title: string;
  fallback: FallbackPost[];
}>();

const api = useApi();
const { locale } = useI18n();
const localePath = useLocalePath();

const { data } = useAsyncData('demo-blog', () => api.blog.posts(1, 3, locale.value, null, ''), {
  watch: [locale],
});

const posts = computed<Post[]>(() => (data.value as { data?: Post[] } | null)?.data ?? []);
const hasApi = computed(() => posts.value.length > 0);

function fmtDate(d: string) {
  try {
    return new Date(d).toLocaleDateString('cs-CZ', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
    });
  } catch {
    return d;
  }
}
</script>

<template>
  <section id="blog" class="section bg-brand-cream">
    <div class="container-x">
      <div class="flex flex-col items-end justify-between gap-6 sm:flex-row">
        <ThemeSectionHeading :subtitle="subtitle" :title="title" align="left" max="max-w-2xl" />
        <ThemeButton :to="localePath('/blog')" variant="outline" class="reveal shrink-0"
          >Všechny články</ThemeButton
        >
      </div>

      <div class="mt-12 grid gap-7 md:grid-cols-3">
        <!-- API posts -->
        <template v-if="hasApi">
          <NuxtLink
            v-for="(p, i) in posts"
            :key="p.id"
            :to="localePath({ name: 'blog-id-slug', params: { id: p.id, slug: p.slug } })"
            class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl"
            :style="{ transitionDelay: `${i * 90}ms` }"
          >
            <div class="aspect-[16/10] overflow-hidden">
              <BaseImage
                :src="`/content/images/post/medium/${p.image}`"
                :alt="p.name"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
            </div>
            <div class="flex flex-1 flex-col p-7">
              <div class="flex items-center gap-3 text-xs font-semibold text-brand">
                <span v-if="p.categories?.length" class="rounded-full bg-brand-soft px-3 py-1">{{
                  p.categories[0].name
                }}</span>
                <span class="text-brand-muted">{{ fmtDate(p.created_at) }}</span>
              </div>
              <h3
                class="mt-4 text-lg font-bold leading-snug transition-colors group-hover:text-brand"
              >
                {{ p.name }}
              </h3>
              <p class="mt-3 line-clamp-2 text-[15px] text-brand-muted" v-html="p.perex" />
              <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand"
                >Číst více
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span></span
              >
            </div>
          </NuxtLink>
        </template>

        <!-- Fallback -->
        <template v-else>
          <div
            v-for="(p, i) in fallback"
            :key="p.name"
            class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl"
            :style="{ transitionDelay: `${i * 90}ms` }"
          >
            <div class="aspect-[16/10] overflow-hidden">
              <img
                :src="p.image"
                :alt="p.name"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
            </div>
            <div class="flex flex-1 flex-col p-7">
              <div class="flex items-center gap-3 text-xs font-semibold text-brand">
                <span class="rounded-full bg-brand-soft px-3 py-1">{{ p.category }}</span>
                <span class="text-brand-muted">{{ p.date }}</span>
              </div>
              <h3
                class="mt-4 text-lg font-bold leading-snug transition-colors group-hover:text-brand"
              >
                {{ p.name }}
              </h3>
              <p class="mt-3 text-[15px] text-brand-muted">{{ p.perex }}</p>
              <NuxtLink
                to="#contact"
                class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand"
                >Číst více
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span></NuxtLink
              >
            </div>
          </div>
        </template>
      </div>
    </div>
  </section>
</template>
