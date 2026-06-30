<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { useApi } from '~/../app/composables/useApi';
import { useAsyncData } from '#app';

const { locale, t } = useI18n();
const api = useApi();

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
  <ThemeInnerLayout>
    <ThemeBreadcrumb
      :title="t('blog.title')"
      subtitle="Blog"
      :crumbs="[{ label: t('blog.title') }]"
    />

    <section class="section">
      <div class="container-x">
        <!-- Categories -->
        <div
          v-if="categoriesData && categoriesData.length"
          class="reveal mb-12 flex flex-wrap items-center justify-center gap-3"
        >
          <NuxtLink
            to="/blog"
            class="rounded-full bg-brand px-5 py-2.5 text-sm font-semibold text-white"
          >
            Vše
          </NuxtLink>
          <NuxtLink
            v-for="cat in categoriesData"
            :key="cat.id"
            :to="`/blog/category/${cat.id}/${cat.slug}`"
            class="rounded-full bg-brand-soft px-5 py-2.5 text-sm font-semibold text-brand-ink transition-colors hover:bg-brand hover:text-white"
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
            class="reveal group flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-100 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl"
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
              <p class="mt-3 line-clamp-3 text-[15px] text-brand-muted" v-html="p.perex" />
              <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand"
                >Číst více
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span></span
              >
            </div>
          </NuxtLink>
        </div>

        <!-- Empty state -->
        <div v-else class="rounded-3xl bg-brand-cream py-20 text-center text-brand-muted">
          <span class="material-symbols-outlined text-5xl text-brand/40">article</span>
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
