<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { useApi } from '~/../app/composables/useApi';
import { useAsyncData } from '#app';
import { useSiteTheme } from '~/../app/composables/useSiteTheme';
import { useStockImages } from '~/../app/composables/useStockImages';

const { locale, t } = useI18n();
const api = useApi();
const { slug, dark } = useSiteTheme();

definePageMeta({ layout: false });

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

const pageMeta = ref({
  title: t('blog.title'),
  description: t('blog.meta_description'),
  meta_title: t('blog.meta_title'),
  meta_description: t('blog.meta_description'),
});

const { data: categoriesData } = useAsyncData('categories', () =>
  api.blog.categories(locale.value),
);

const getPosts = () => {
  return api.blog.posts(
    tableQuery.value.page,
    tableQuery.value.paginate,
    locale.value,
    route.params.id,
  );
};

const route = useRoute();

const tableQuery = ref({
  paginate: 12 as number,
  page: 1 as number,
});

const { data: postsData } = useAsyncData(
  () => `posts-${route.params.id}`,
  () =>
    api.blog.posts(tableQuery.value.page, tableQuery.value.paginate, locale.value, route.params.id),
  {
    watch: [locale],
  },
);

const localePath = useLocalePath();

async function updatePage(page: number) {
  tableQuery.value.page = page;
  postsData.value = await getPosts();
  if (import.meta.client) window.scrollTo({ top: 0, behavior: 'smooth' });
}

useHead({
  title: pageMeta.value.title,
  meta: [
    { name: 'description', content: pageMeta.value.meta_description },
    { property: 'og:title', content: pageMeta.value.meta_title },
    { property: 'og:description', content: pageMeta.value.meta_description },
  ],
  link: [
    {
      rel: 'canonical',
      href:
        useRuntimeConfig().public.appUrl +
        (locale.value !== 'cs' ? `/${locale.value}` : '') +
        `/${t('canonical.blog')}`,
    },
  ],
});
</script>

<template>
  <ThemeInnerLayout :slug="slug">
    <ThemeBreadcrumb
      :slug="slug"
      :title="t('blog.title')"
      subtitle="Blog"
      :crumbs="[{ label: t('blog.title') }]"
      :image="useStockImages().get(slug).hero"
    />

    <section class="section" :class="dark ? 'bg-neutral-950' : ''">
      <div class="container-x">
        <!-- Categories -->
        <div
          v-if="categoriesData && categoriesData.length"
          class="reveal mb-12 flex flex-wrap items-center justify-center gap-3"
        >
          <NuxtLink
            to="/blog"
            class="rounded-full bg-brand-pop px-5 py-2.5 text-sm font-semibold text-brand-ink"
          >
            Vše
          </NuxtLink>
          <NuxtLink
            v-for="cat in categoriesData"
            :key="cat.id"
            :to="`/blog/category/${cat.id}/${cat.slug}`"
            class="rounded-full px-5 py-2.5 text-sm font-semibold transition-colors hover:bg-brand-pop hover:text-brand-ink"
            :class="dark ? 'bg-white/10 text-white' : 'bg-brand-pop/10 text-brand-ink'"
          >
            {{ cat.name }}
          </NuxtLink>
        </div>

        <!-- Posts grid -->
        <div
          v-if="postsData && postsData.data && postsData.data.length"
          class="grid gap-7 md:grid-cols-2 lg:grid-cols-3"
        >
          <NuxtLink
            v-for="(p, i) in postsData.data"
            :key="p.id"
            :to="localePath({ name: 'blog-id-slug', params: { id: p.id, slug: p.slug } })"
            class="reveal group flex flex-col overflow-hidden rounded-3xl shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl"
            :class="
              dark ? 'bg-white/[0.04] ring-1 ring-white/10' : 'bg-white ring-1 ring-slate-100'
            "
            :style="{ transitionDelay: `${(i % 3) * 80}ms` }"
          >
            <div class="aspect-[16/10] overflow-hidden">
              <BaseImage
                :src="`/content/images/post/medium/${p.image}`"
                :alt="p.name"
                class="size-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
            </div>
            <div class="flex flex-1 flex-col p-7">
              <div class="flex items-center gap-3 text-xs font-semibold text-brand-pop">
                <span
                  v-if="p.categories?.length"
                  class="rounded-full px-3 py-1"
                  :class="dark ? 'bg-white/10 text-white' : 'bg-brand-pop/10'"
                  >{{ p.categories[0].name }}</span
                >
                <span :class="dark ? 'text-white/60' : 'text-brand-muted'">{{
                  fmtDate(p.created_at)
                }}</span>
              </div>
              <h3
                class="mt-4 text-lg font-bold leading-snug transition-colors group-hover:text-brand-pop"
                :class="dark ? 'text-white' : ''"
              >
                {{ p.name }}
              </h3>
              <p
                class="mt-3 line-clamp-3 text-[15px]"
                :class="dark ? 'text-white/60' : 'text-brand-muted'"
                v-html="p.perex"
              />
              <span
                class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-pop"
                >Číst více
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span></span
              >
            </div>
          </NuxtLink>
        </div>

        <!-- Empty state -->
        <div
          v-else
          class="rounded-3xl py-20 text-center"
          :class="dark ? 'bg-white/[0.04] text-white/60' : 'bg-brand-cream text-brand-muted'"
        >
          <span class="material-symbols-outlined text-5xl text-brand-pop/40">article</span>
          <p class="mt-4 text-lg font-semibold">Zatím tu nejsou žádné články.</p>
        </div>

        <!-- Pagination -->
        <div v-if="postsData && postsData.lastPage > 1" class="mt-14">
          <BasePagination
            :page="tableQuery.page"
            :per-page="tableQuery.paginate"
            :last-page="postsData.lastPage"
            :total="postsData.total"
            @update-page="updatePage"
          />
        </div>
      </div>
    </section>
  </ThemeInnerLayout>
</template>
