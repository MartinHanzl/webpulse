<script setup lang="ts">
import {
  ArrowLeftIcon,
  ArrowPathIcon,
  ArrowRightIcon,
  CheckCircleIcon,
  ChartBarIcon,
  ListBulletIcon,
  PlayIcon,
  QuestionMarkCircleIcon,
  TrophyIcon,
  XCircleIcon,
} from '@heroicons/vue/24/outline';
import { CheckIcon } from '@heroicons/vue/24/solid';
import { useApi } from '~/composables/useApi';
import { useAsyncData } from '#app';

const route = useRoute();

const stats = ref([]);
const quizStarted = ref(false);
const quizFinished = ref(false);
const currentQuestionIndex = ref(0);
const onlyWrong = ref(false);

const questionCount = computed(() => quizData.value?.questions?.length ?? 0);
const currentQuestion = computed(() => quizData.value?.questions?.[currentQuestionIndex.value]);
const progress = computed(() =>
  questionCount.value ? ((currentQuestionIndex.value + 1) / questionCount.value) * 100 : 0,
);
const isLastQuestion = computed(() => currentQuestionIndex.value === questionCount.value - 1);
const answeredCount = computed(
  () => quizData.value?.questions?.filter((q) => q.answers.some((a) => a.is_selected)).length ?? 0,
);

function isAnswered(index: number) {
  return quizData.value?.questions?.[index]?.answers?.some((a) => a.is_selected) ?? false;
}

function getNextQuestion() {
  if (currentQuestionIndex.value < questionCount.value - 1) {
    currentQuestionIndex.value++;
  }
}

function getPreviousQuestion() {
  if (currentQuestionIndex.value > 0) {
    currentQuestionIndex.value--;
  }
}

function markSelected(answer) {
  if (currentQuestion.value) {
    currentQuestion.value.answers.forEach((ans) => {
      ans.is_selected = ans.id === answer.id;
    });
  }
}

// send the quiz data to the server when finished
function submitQuiz() {
  if (quizData.value) {
    api.quiz
      .submit(route.params.id, quizData.value)
      .then((response) => {
        stats.value = response;
        quizFinished.value = true;
      })
      .catch((error) => {
        console.error('Error submitting quiz:', error);
      });
  }
}

function restartQuiz() {
  if (quizDataRaw.value) {
    quizData.value = reactive(JSON.parse(JSON.stringify(quizDataRaw.value)));
  }
  stats.value = [];
  currentQuestionIndex.value = 0;
  quizFinished.value = false;
  onlyWrong.value = false;
  quizStarted.value = true;
}

const api = useApi();
const quizData = ref(null);

const { data: quizDataRaw } = await useAsyncData(`quiz-${route.params.id}`, () =>
  api.quiz.quiz(route.params.id),
);

// Po načtení převeď data na reaktivní objekt
watchEffect(() => {
  if (quizDataRaw.value) {
    // vytvoříme hlubokou kopii, abychom zajistili reaktivitu všech úrovní
    quizData.value = reactive(JSON.parse(JSON.stringify(quizDataRaw.value)));
  }
});

const resultTone = computed(() => {
  const accuracy = stats.value?.accuracy ?? 0;
  if (accuracy >= 75) {
    return { ring: 'text-green-500', text: 'text-green-600', title: 'Skvělá práce!' };
  }
  if (accuracy >= 50) {
    return { ring: 'text-amber-500', text: 'text-amber-600', title: 'Slušný výsledek!' };
  }
  return { ring: 'text-red-500', text: 'text-red-600', title: 'Příště to půjde líp!' };
});

const CIRCUMFERENCE = 2 * Math.PI * 52;
const ringOffset = computed(
  () =>
    CIRCUMFERENCE - (Math.min(Math.max(stats.value?.accuracy ?? 0, 0), 100) / 100) * CIRCUMFERENCE,
);

const visibleAnswers = computed(() => {
  const answers = stats.value?.answers ?? [];
  return onlyWrong.value ? answers.filter((a) => !a.isCorrect) : answers;
});

useHead(() => {
  return {
    title: 'Kvízy',
    meta: [
      {
        name: 'description',
        content: 'Zde najdete všechny kvízy, které jsou dostupné na našem webu.',
      },
    ],
  };
});
</script>

<template>
  <div>
    <!-- Úvod do kvízu -->
    <div v-if="!quizStarted" class="mx-auto max-w-2xl">
      <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-100">
        <div
          class="bg-gradient-to-br from-primaryLight/50 via-white to-secondaryLight/40 px-6 py-10 text-center lg:px-10"
        >
          <div
            class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-primaryLight"
          >
            <TrophyIcon class="h-8 w-8 text-primaryDark" />
          </div>
          <BasePropsHeading type="h2" margin-bottom="mb-4">{{ quizData?.name }}</BasePropsHeading>
          <p
            v-if="quizData?.description"
            class="mx-auto max-w-xl font-quicksand text-sm leading-6 text-gray-600"
            v-html="quizData?.description"
          />
        </div>

        <div class="grid grid-cols-2 divide-x divide-gray-100 border-y border-gray-100">
          <div class="flex flex-col items-center gap-1 px-4 py-6">
            <QuestionMarkCircleIcon class="h-6 w-6 text-primary" />
            <p class="text-2xl font-semibold text-primaryDark">{{ questionCount }}</p>
            <p class="font-quicksand text-xs text-gray-500">otázek</p>
          </div>
          <div class="flex flex-col items-center gap-1 px-4 py-6">
            <ChartBarIcon class="h-6 w-6 text-primary" />
            <p class="text-2xl font-semibold text-primaryDark">{{ quizData?.accuracy }}%</p>
            <p class="font-quicksand text-xs text-gray-500">průměrná úspěšnost</p>
          </div>
        </div>

        <div class="flex flex-col items-center gap-4 px-6 py-8 lg:px-10">
          <BaseButton
            size="lg"
            variant="primary"
            class="w-full sm:w-auto"
            @click="quizStarted = true"
          >
            <span class="inline-flex items-center gap-x-2 px-4">
              <PlayIcon class="h-5 w-5" />
              Zahájit kvíz
            </span>
          </BaseButton>
          <NuxtLink
            to="/kvizy"
            class="inline-flex items-center gap-x-1 font-quicksand text-xs text-gray-500 transition hover:text-primaryDark"
          >
            <ArrowLeftIcon class="h-4 w-4" />
            Zpět na kvízy
          </NuxtLink>
        </div>
      </div>
    </div>

    <!-- Průběh kvízu -->
    <template v-if="quizStarted && !quizFinished">
      <div class="mx-auto max-w-3xl">
        <BasePropsHeading type="h4" margin-bottom="mb-6">{{ quizData?.name }}</BasePropsHeading>

        <div class="mb-2 flex items-center justify-between font-quicksand text-xs text-gray-500">
          <span>Otázka {{ currentQuestionIndex + 1 }} z {{ questionCount }}</span>
          <span>{{ answeredCount }} zodpovězeno</span>
        </div>
        <div class="mb-4 h-2 w-full overflow-hidden rounded-full bg-gray-100">
          <div
            class="h-full rounded-full bg-primary transition-all duration-500"
            :style="{ width: `${progress}%` }"
          />
        </div>
        <div class="mb-10 flex w-full justify-center gap-1.5">
          <button
            v-for="(question, index) in quizData?.questions"
            :key="index"
            type="button"
            :class="[
              index === currentQuestionIndex
                ? 'bg-primaryDark'
                : isAnswered(index)
                  ? 'bg-primary'
                  : 'bg-gray-200',
              'h-1.5 w-full rounded-full transition hover:opacity-80',
            ]"
            @click="currentQuestionIndex = index"
          />
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-100 lg:p-8">
          <div v-if="currentQuestion?.image" class="mb-6 flex justify-center">
            <BaseImage
              :image="currentQuestion.image"
              type="quiz"
              size="screen"
              :width="512"
              :height="288"
              class="overflow-hidden rounded-2xl"
            />
          </div>
          <p
            v-if="currentQuestion?.name"
            class="mb-8 text-center text-lg font-semibold text-primaryDark lg:text-xl"
            v-html="currentQuestion?.name"
          />
          <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:gap-4">
            <button
              v-for="(answer, index) in currentQuestion?.answers"
              :key="index"
              type="button"
              :class="[
                answer.is_selected
                  ? 'bg-primaryDark text-white ring-primaryDark'
                  : 'bg-white text-text ring-gray-200 hover:bg-primaryLight/20 hover:ring-primaryLight',
                'flex items-center gap-x-3 rounded-2xl p-4 text-left font-quicksand text-sm ring-1 ring-inset transition duration-200 lg:p-5',
              ]"
              @click="markSelected(answer)"
            >
              <span
                :class="[
                  answer.is_selected
                    ? 'border-white bg-white text-primaryDark'
                    : 'border-gray-300 text-transparent',
                  'flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 transition',
                ]"
              >
                <CheckIcon class="h-4 w-4" />
              </span>
              <span class="text-wrap">{{ answer.name }}</span>
            </button>
          </div>
        </div>

        <div class="mt-8 flex items-center justify-between gap-4">
          <BaseButton
            v-if="currentQuestionIndex > 0"
            variant="secondary"
            size="md"
            @click="getPreviousQuestion"
          >
            <span class="inline-flex items-center gap-x-2 px-2">
              <ArrowLeftIcon class="h-4 w-4" />
              Předchozí
            </span>
          </BaseButton>
          <div v-else />

          <BaseButton v-if="!isLastQuestion" variant="secondary" size="md" @click="getNextQuestion">
            <span class="inline-flex items-center gap-x-2 px-2">
              Další
              <ArrowRightIcon class="h-4 w-4" />
            </span>
          </BaseButton>
          <BaseButton v-else variant="primary" size="md" @click="submitQuiz">
            <span class="inline-flex items-center gap-x-2 px-2">
              <CheckCircleIcon class="h-5 w-5" />
              Dokončit kvíz
            </span>
          </BaseButton>
        </div>

        <div class="mt-8 text-center">
          <NuxtLink
            to="/kvizy"
            class="inline-flex items-center gap-x-1 font-quicksand text-xs text-gray-500 transition hover:text-primaryDark"
          >
            <ArrowLeftIcon class="h-4 w-4" />
            Zpět na kvízy
          </NuxtLink>
        </div>
      </div>
    </template>

    <!-- Vyhodnocení -->
    <div v-if="quizFinished && stats" class="mx-auto max-w-3xl">
      <div
        class="mb-8 flex flex-col items-center rounded-3xl bg-gradient-to-br from-primaryLight/40 via-white to-secondaryLight/30 px-6 py-10 text-center shadow-sm ring-1 ring-gray-100"
      >
        <div class="relative mb-6 h-32 w-32">
          <svg class="h-32 w-32 -rotate-90" viewBox="0 0 120 120">
            <circle
              cx="60"
              cy="60"
              r="52"
              fill="none"
              stroke="currentColor"
              stroke-width="10"
              class="text-gray-200"
            />
            <circle
              cx="60"
              cy="60"
              r="52"
              fill="none"
              stroke="currentColor"
              stroke-width="10"
              stroke-linecap="round"
              :stroke-dasharray="CIRCUMFERENCE"
              :stroke-dashoffset="ringOffset"
              :class="[resultTone.ring, 'transition-all duration-1000']"
            />
          </svg>
          <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span :class="[resultTone.text, 'text-3xl font-bold']">{{ stats?.accuracy }}%</span>
            <span class="font-quicksand text-[11px] text-gray-500">úspěšnost</span>
          </div>
        </div>

        <BasePropsHeading type="h4" margin-bottom="mb-2">{{ resultTone.title }}</BasePropsHeading>
        <p class="font-quicksand text-sm text-gray-600">
          Správně jste odpověděli
          <strong class="text-primaryDark">{{ stats?.correctAnswers }}</strong>
          z {{ questionCount }} otázek.
        </p>
      </div>

      <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <BasePropsHeading type="h6" margin-bottom="mb-0" class="!text-left">
          Vaše odpovědi
        </BasePropsHeading>
        <button
          type="button"
          :class="[
            onlyWrong
              ? 'bg-red-50 text-red-700 ring-red-200'
              : 'bg-white text-gray-600 ring-gray-200 hover:bg-gray-50',
            'inline-flex items-center gap-x-2 rounded-full px-3 py-1.5 font-quicksand text-xs ring-1 ring-inset transition',
          ]"
          @click="onlyWrong = !onlyWrong"
        >
          <ListBulletIcon class="h-4 w-4" />
          {{ onlyWrong ? 'Zobrazit všechny' : 'Jen chybné' }}
        </button>
      </div>

      <div class="flex flex-col gap-3">
        <div
          v-for="(answer, index) in visibleAnswers"
          :key="index"
          :class="[
            answer.isCorrect ? 'border-green-400' : 'border-red-400',
            'overflow-hidden rounded-2xl border-l-4 bg-white p-4 shadow-sm ring-1 ring-gray-100 lg:p-5',
          ]"
        >
          <div class="flex items-start gap-3">
            <component
              :is="answer.isCorrect ? CheckCircleIcon : XCircleIcon"
              :class="[
                answer.isCorrect ? 'text-green-500' : 'text-red-500',
                'mt-0.5 h-6 w-6 shrink-0',
              ]"
            />
            <div class="min-w-0 flex-1">
              <p
                v-if="answer.question"
                class="mb-3 text-wrap font-quicksand text-sm font-semibold text-text"
              >
                {{ answer.question }}
              </p>

              <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                <BaseImage
                  v-if="answer.image"
                  :image="answer.image"
                  type="quiz"
                  size="screen"
                  :width="128"
                  :height="128"
                  class="w-28 shrink-0 overflow-hidden rounded-xl"
                />
                <div class="grid min-w-0 flex-1 grid-cols-1 gap-2 sm:grid-cols-2">
                  <div class="min-w-0">
                    <p class="font-quicksand text-[11px] uppercase tracking-wide text-gray-400">
                      Vaše odpověď
                    </p>
                    <p
                      :class="[
                        answer.isCorrect ? 'text-green-700' : 'text-red-700',
                        'text-wrap font-quicksand text-sm',
                      ]"
                    >
                      {{ answer.userAnswer || '—' }}
                    </p>
                  </div>
                  <div v-if="!answer.isCorrect" class="min-w-0">
                    <p class="font-quicksand text-[11px] uppercase tracking-wide text-gray-400">
                      Správná odpověď
                    </p>
                    <p class="text-wrap font-quicksand text-sm text-green-700">
                      {{ answer.correctAnswer }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row">
        <BaseButton size="md" variant="secondary" @click="restartQuiz">
          <span class="inline-flex items-center gap-x-2 px-2">
            <ArrowPathIcon class="h-5 w-5" />
            Zkusit znovu
          </span>
        </BaseButton>
        <NuxtLink to="/kvizy">
          <BaseButton size="md" variant="primary">
            <span class="inline-flex items-center gap-x-2 px-2">
              Zahrát si další kvízy
              <ArrowRightIcon class="h-5 w-5" />
            </span>
          </BaseButton>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>
