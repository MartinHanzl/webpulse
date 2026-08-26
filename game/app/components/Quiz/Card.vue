<script setup lang="ts">
import { StarIcon } from '@heroicons/vue/24/solid';
import {
  AcademicCapIcon,
  BoltIcon,
  FireIcon,
  QuestionMarkCircleIcon,
  SparklesIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps<{
  quiz: {
    id: string;
    name: string;
    slug: string;
    description: string;
    accuracy: number;
    attempts: number;
    is_new?: boolean;
    tags_array?: string[];
    questions_count?: number;
  };
}>();

const difficulty = computed(() => {
  if (!props.quiz.attempts) {
    return {
      label: 'Nehráno',
      icon: SparklesIcon,
      pill: 'bg-secondaryLight/30 text-secondaryDark ring-secondaryLight',
      bar: 'bg-secondaryLight',
    };
  }
  if (props.quiz.accuracy >= 75) {
    return {
      label: 'Snadný',
      icon: AcademicCapIcon,
      pill: 'bg-green-50 text-green-700 ring-green-200',
      bar: 'bg-green-500',
    };
  }
  if (props.quiz.accuracy >= 50) {
    return {
      label: 'Střední',
      icon: FireIcon,
      pill: 'bg-amber-50 text-amber-700 ring-amber-200',
      bar: 'bg-amber-500',
    };
  }
  return {
    label: 'Těžký',
    icon: BoltIcon,
    pill: 'bg-red-50 text-red-700 ring-red-200',
    bar: 'bg-red-500',
  };
});

const localePath = useLocalePath();
</script>

<template>
  <NuxtLink
    :to="localePath(`/kvizy/${props.quiz.id}/${props.quiz.slug}`)"
    class="group flex h-full flex-col rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-100 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:ring-primaryLight"
  >
    <div class="mb-3 flex items-start justify-between gap-2">
      <span
        :class="[
          difficulty.pill,
          'inline-flex items-center gap-x-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset',
        ]"
      >
        <component :is="difficulty.icon" class="h-4 w-4" />
        {{ difficulty.label }}
      </span>
      <span
        v-if="quiz.is_new"
        class="inline-flex items-center gap-x-1 rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-300"
      >
        <StarIcon class="h-3.5 w-3.5 text-yellow-400" />
        Novinka
      </span>
    </div>

    <BasePropsHeading
      type="h6"
      margin-bottom="mb-2"
      class="!text-left font-semibold transition-colors group-hover:text-primaryDark"
    >
      {{ props.quiz.name }}
    </BasePropsHeading>

    <p
      v-if="props.quiz.description"
      class="mb-4 line-clamp-3 font-quicksand text-xs leading-5 text-gray-500"
      v-html="props.quiz.description"
    />

    <div v-if="quiz.tags_array?.length" class="mb-4 flex flex-wrap gap-1.5">
      <span
        v-for="(tag, index) in quiz.tags_array"
        :key="index"
        class="rounded-full bg-backgroundLight px-2.5 py-1 text-[11px] text-gray-600 ring-1 ring-inset ring-gray-100"
      >
        {{ tag }}
      </span>
    </div>

    <div class="mt-auto border-t border-gray-100 pt-4">
      <div class="mb-1.5 flex items-center justify-between text-xs text-gray-500">
        <span>{{ quiz.accuracy }}% úspěšnost</span>
        <span class="inline-flex items-center gap-x-1">
          <QuestionMarkCircleIcon class="h-4 w-4" />
          {{ quiz.questions_count }} otázek
        </span>
      </div>
      <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
        <div
          :class="[difficulty.bar, 'h-full rounded-full transition-all duration-500']"
          :style="{ width: `${Math.min(Math.max(quiz.accuracy, 0), 100)}%` }"
        />
      </div>
    </div>
  </NuxtLink>
</template>
