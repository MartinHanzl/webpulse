<script setup lang="ts">
import { ref, computed, onBeforeUnmount } from 'vue';
import imposterData from '~/assets/data/imposter.json';

useHead({
  title: 'Imposter',
  meta: [
    {
      name: 'description',
      content:
        'Pass-and-play párty hra pro 3+ hráčů. Každý dostane stejné slovo nebo otázku – jen imposter dostane něco jiného. Najdete ho?',
    },
  ],
});

type Mode = 'word' | 'question';
type Phase = 'setup' | 'reveal' | 'discuss' | 'result';
type RevealStep = 'handoff' | 'shown';

interface Assignment {
  name: string;
  isImposter: boolean;
  // slovní mód
  word?: string;
  // otázkový mód
  question?: string;
}

const data = imposterData as {
  wordCategories: { name: string; words: string[] }[];
  questionPairs: { crew: string; imposter: string }[];
};

// ---- nastavení ----
const phase = ref<Phase>('setup');
const mode = ref<Mode>('word');

const players = ref<string[]>(['Hráč 1', 'Hráč 2', 'Hráč 3']);
const imposterCount = ref(1);

// kategorie pro slovní mód: '' = náhodná ze všech
const selectedCategory = ref('');

const maxImposters = computed(() => Math.max(1, Math.floor(players.value.length / 2)));

function addPlayer() {
  if (players.value.length >= 12) return;
  players.value.push(`Hráč ${players.value.length + 1}`);
}

function removePlayer(i: number) {
  if (players.value.length <= 3) return;
  players.value.splice(i, 1);
  if (imposterCount.value > maxImposters.value) imposterCount.value = maxImposters.value;
}

// ---- stav kola ----
const assignments = ref<Assignment[]>([]);
const revealIndex = ref(0);
const revealStep = ref<RevealStep>('handoff');
const starterName = ref('');

// odhalené info do výsledku
const roundCategory = ref('');
const roundCrewWord = ref('');
const roundCrewQuestion = ref('');

const imposterNames = computed(() =>
  assignments.value.filter((a) => a.isImposter).map((a) => a.name),
);

// ---- pomocné ----
function shuffle<T>(arr: T[]): T[] {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

function pick<T>(arr: T[]): T {
  return arr[Math.floor(Math.random() * arr.length)];
}

function buildRound() {
  const n = players.value.length;
  const impCount = Math.min(imposterCount.value, maxImposters.value);

  // kdo je imposter
  const indices = shuffle(Array.from({ length: n }, (_, i) => i));
  const imposterSet = new Set(indices.slice(0, impCount));

  const list: Assignment[] = [];

  if (mode.value === 'word') {
    const category = selectedCategory.value
      ? data.wordCategories.find((c) => c.name === selectedCategory.value)!
      : pick(data.wordCategories);
    roundCategory.value = category.name;

    const words = shuffle(category.words);
    const crewWord = words[0];
    // odlišné slovo pro impostery ze stejné kategorie
    const decoyPool = category.words.filter((w) => w !== crewWord);
    roundCrewWord.value = crewWord;
    roundCrewQuestion.value = '';

    players.value.forEach((name, i) => {
      const isImp = imposterSet.has(i);
      list.push({
        name,
        isImposter: isImp,
        word: isImp ? 'IMPOSTER' : crewWord,
      });
    });
  } else {
    const pair = pick(data.questionPairs);
    roundCrewQuestion.value = pair.crew;
    roundCrewWord.value = '';
    roundCategory.value = '';

    players.value.forEach((name, i) => {
      const isImp = imposterSet.has(i);
      list.push({
        name,
        isImposter: isImp,
        question: isImp ? pair.imposter : pair.crew,
      });
    });
  }

  assignments.value = list;
  starterName.value = pick(players.value);
  revealIndex.value = 0;
  revealStep.value = 'handoff';
  phase.value = 'reveal';
}

const current = computed(() => assignments.value[revealIndex.value]);
const isLast = computed(() => revealIndex.value === assignments.value.length - 1);

function showCard() {
  revealStep.value = 'shown';
}

function hideAndNext() {
  if (isLast.value) {
    phase.value = 'discuss';
    return;
  }
  revealIndex.value += 1;
  revealStep.value = 'handoff';
}

function revealResult() {
  phase.value = 'result';
}

function playAgainSame() {
  buildRound();
}

function backToSetup() {
  phase.value = 'setup';
}

// ---- volitelný diskuzní časovač ----
const discussSeconds = ref(120);
const timeLeft = ref(0);
const timerRunning = ref(false);
let timerId: ReturnType<typeof setInterval> | null = null;

function fmtTime(s: number) {
  const m = Math.floor(s / 60);
  const sec = s % 60;
  return `${m}:${String(sec).padStart(2, '0')}`;
}

function startTimer() {
  stopTimer();
  timeLeft.value = discussSeconds.value;
  timerRunning.value = true;
  timerId = setInterval(() => {
    if (timeLeft.value <= 1) {
      timeLeft.value = 0;
      stopTimer();
      return;
    }
    timeLeft.value -= 1;
  }, 1000);
}

function stopTimer() {
  timerRunning.value = false;
  if (timerId) {
    clearInterval(timerId);
    timerId = null;
  }
}

onBeforeUnmount(stopTimer);
</script>

<template>
  <main class="mx-auto flex min-h-[80vh] w-full max-w-xl flex-col items-center gap-8 py-6">
    <header class="text-center">
      <h1 class="font-winky text-3xl font-bold sm:text-4xl">Imposter</h1>
      <p class="mt-2 text-sm text-gray-500">
        Všichni dostanou stejné zadání – jen imposter něco jiného. Kdo je to?
      </p>
    </header>

    <!-- ====================== NASTAVENÍ ====================== -->
    <section v-if="phase === 'setup'" class="flex w-full flex-col gap-8">
      <!-- mód -->
      <div class="flex flex-col items-center gap-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Mód hry</span>
        <div class="inline-flex rounded-full border border-gray-300 bg-white p-1 shadow-sm">
          <button
            class="rounded-full px-5 py-2 text-sm font-semibold transition"
            :class="
              mode === 'word' ? 'bg-primary text-white shadow' : 'text-gray-600 hover:bg-gray-100'
            "
            @click="mode = 'word'"
          >
            Tajné slovo
          </button>
          <button
            class="rounded-full px-5 py-2 text-sm font-semibold transition"
            :class="
              mode === 'question'
                ? 'bg-primary text-white shadow'
                : 'text-gray-600 hover:bg-gray-100'
            "
            @click="mode = 'question'"
          >
            Otázka
          </button>
        </div>
        <p class="max-w-sm text-center text-xs text-gray-400">
          <template v-if="mode === 'word'">
            Všichni vidí stejné slovo, imposter vidí jiné slovo ze stejné kategorie. Postupně každý
            řekne nápovědu k „svému“ slovu.
          </template>
          <template v-else>
            Všichni dostanou stejnou otázku, imposter mírně jinou. Každý nahlas odpoví – odpověď
            impostera nebude úplně sedět.
          </template>
        </p>
      </div>

      <!-- kategorie (jen slovní mód) -->
      <div v-if="mode === 'word'" class="flex flex-col items-center gap-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kategorie</span>
        <div class="flex flex-wrap items-center justify-center gap-2">
          <button
            class="rounded-xl border px-4 py-2 text-sm font-semibold transition"
            :class="
              selectedCategory === ''
                ? 'border-primary bg-primary/10 text-primaryDark'
                : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
            "
            @click="selectedCategory = ''"
          >
            🎲 Náhodná
          </button>
          <button
            v-for="c in data.wordCategories"
            :key="c.name"
            class="rounded-xl border px-4 py-2 text-sm font-semibold transition"
            :class="
              selectedCategory === c.name
                ? 'border-primary bg-primary/10 text-primaryDark'
                : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
            "
            @click="selectedCategory = c.name"
          >
            {{ c.name }}
          </button>
        </div>
      </div>

      <!-- hráči -->
      <div class="flex flex-col items-center gap-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">
          Hráči ({{ players.length }})
        </span>
        <div class="flex w-full max-w-sm flex-col gap-2">
          <div v-for="(p, i) in players" :key="i" class="flex items-center gap-2">
            <span class="w-6 text-center text-sm font-bold text-gray-400">{{ i + 1 }}</span>
            <input
              v-model="players[i]"
              type="text"
              maxlength="20"
              class="flex-1 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm focus:border-primary focus:ring-0"
            />
            <button
              class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-500 transition hover:border-rose-300 hover:text-rose-500 disabled:cursor-not-allowed disabled:opacity-40"
              :disabled="players.length <= 3"
              @click="removePlayer(i)"
            >
              ✕
            </button>
          </div>
        </div>
        <button
          class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-gray-400 disabled:cursor-not-allowed disabled:opacity-40"
          :disabled="players.length >= 12"
          @click="addPlayer"
        >
          + Přidat hráče
        </button>
      </div>

      <!-- počet imposterů -->
      <div class="flex flex-col items-center gap-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">
          Počet imposterů
        </span>
        <div class="flex items-center gap-2">
          <button
            v-for="k in maxImposters"
            :key="k"
            class="h-10 w-10 rounded-xl border text-sm font-bold transition"
            :class="
              imposterCount === k
                ? 'border-primary bg-primary/10 text-primaryDark'
                : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
            "
            @click="imposterCount = k"
          >
            {{ k }}
          </button>
        </div>
      </div>

      <button
        class="mx-auto rounded-2xl bg-primary px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-primaryDark"
        @click="buildRound"
      >
        Spustit hru
      </button>
    </section>

    <!-- ====================== ROZDÁVÁNÍ KARET ====================== -->
    <section v-else-if="phase === 'reveal'" class="flex w-full flex-col items-center gap-6">
      <div class="text-xs text-gray-400">Hráč {{ revealIndex + 1 }} / {{ assignments.length }}</div>

      <!-- handoff -->
      <div
        v-if="revealStep === 'handoff'"
        class="flex w-full flex-col items-center gap-6 rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm"
      >
        <div class="text-5xl">📱➡️</div>
        <div>
          <div class="text-xs uppercase tracking-wide text-gray-400">Předej zařízení hráči</div>
          <div class="mt-1 font-winky text-2xl font-bold text-primaryDark">{{ current.name }}</div>
        </div>
        <p class="text-sm text-gray-500">Ať se nikdo jiný nedívá!</p>
        <button
          class="rounded-2xl bg-primary px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-primaryDark"
          @click="showCard"
        >
          Jsem {{ current.name }} – zobrazit
        </button>
      </div>

      <!-- shown -->
      <div
        v-else
        class="flex w-full flex-col items-center gap-6 rounded-3xl border-2 p-8 text-center shadow-lg"
        :class="
          current.isImposter ? 'border-rose-300 bg-rose-50' : 'border-primary/40 bg-primary/5'
        "
      >
        <div class="text-xs uppercase tracking-wide text-gray-400">{{ current.name }}</div>

        <!-- slovní mód -->
        <template v-if="mode === 'word'">
          <div class="text-xs uppercase tracking-wider text-gray-400">Tvé slovo</div>
          <div class="font-winky text-4xl font-bold text-gray-800">{{ current.word }}</div>
        </template>

        <!-- otázkový mód -->
        <template v-else>
          <div class="text-xs uppercase tracking-wider text-gray-400">Tvá otázka</div>
          <div class="font-winky text-2xl font-bold leading-snug text-gray-800">
            {{ current.question }}
          </div>
        </template>

        <p class="text-xs text-gray-400">Zapamatuj si to a schovej, ať tě nikdo neodhalí 🤫</p>

        <button
          class="rounded-2xl bg-gray-800 px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-gray-700"
          @click="hideAndNext"
        >
          {{ isLast ? 'Schovat a začít diskuzi' : 'Schovat a předat dál' }}
        </button>
      </div>
    </section>

    <!-- ====================== DISKUZE ====================== -->
    <section v-else-if="phase === 'discuss'" class="flex w-full flex-col items-center gap-6">
      <div
        class="flex w-full flex-col items-center gap-5 rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm"
      >
        <div class="text-5xl">🕵️</div>
        <h2 class="font-winky text-2xl font-bold">Diskuze</h2>
        <p class="max-w-sm text-sm text-gray-500">
          <template v-if="mode === 'word'">
            Postupně každý řekne jednu nápovědu ke svému slovu. Pak se domluvte, kdo je imposter.
          </template>
          <template v-else>
            Každý nahlas odpoví na svou otázku. Pak se domluvte, čí odpověď neseděla – to je
            imposter.
          </template>
        </p>
        <div class="rounded-2xl bg-primary/10 px-5 py-3">
          <span class="text-xs uppercase tracking-wide text-gray-400">Začíná</span>
          <div class="font-winky text-xl font-bold text-primaryDark">{{ starterName }}</div>
        </div>

        <!-- časovač -->
        <div class="flex flex-col items-center gap-3 border-t border-gray-100 pt-5">
          <div class="font-mono text-4xl font-bold tabular-nums text-gray-800">
            {{ fmtTime(timerRunning || timeLeft > 0 ? timeLeft : discussSeconds) }}
          </div>
          <div v-if="!timerRunning" class="flex items-center gap-2">
            <button
              v-for="s in [60, 120, 180]"
              :key="s"
              class="rounded-xl border px-3 py-1.5 text-xs font-semibold transition"
              :class="
                discussSeconds === s
                  ? 'border-primary bg-primary/10 text-primaryDark'
                  : 'border-gray-300 bg-white text-gray-600 hover:border-gray-400'
              "
              @click="discussSeconds = s"
            >
              {{ s / 60 }} min
            </button>
          </div>
          <button
            v-if="!timerRunning"
            class="rounded-xl border border-gray-300 bg-white px-5 py-2 text-sm font-semibold text-gray-700 transition hover:border-gray-400"
            @click="startTimer"
          >
            ▶ Spustit časovač
          </button>
          <button
            v-else
            class="rounded-xl border border-gray-300 bg-white px-5 py-2 text-sm font-semibold text-gray-700 transition hover:border-gray-400"
            @click="stopTimer"
          >
            ⏸ Zastavit
          </button>
        </div>

        <button
          class="mt-2 rounded-2xl bg-primary px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-primaryDark"
          @click="revealResult"
        >
          Odhalit impostera
        </button>
      </div>
    </section>

    <!-- ====================== VÝSLEDEK ====================== -->
    <section v-else class="flex w-full flex-col items-center gap-6">
      <div
        class="flex w-full flex-col items-center gap-4 rounded-3xl border-2 border-rose-300 bg-rose-50 p-8 text-center shadow-lg"
      >
        <div class="text-5xl">🎭</div>
        <div class="text-xs uppercase tracking-wide text-gray-400">
          {{ imposterNames.length > 1 ? 'Imposteři byli' : 'Imposter byl' }}
        </div>
        <div class="font-winky text-3xl font-bold text-rose-600">
          {{ imposterNames.join(', ') }}
        </div>

        <div class="mt-2 w-full border-t border-rose-200 pt-4 text-sm text-gray-600">
          <template v-if="mode === 'word'">
            <div class="text-xs uppercase tracking-wide text-gray-400">
              Slovo týmu ({{ roundCategory }})
            </div>
            <div class="font-winky text-xl font-bold text-gray-800">{{ roundCrewWord }}</div>
          </template>
          <template v-else>
            <div class="text-xs uppercase tracking-wide text-gray-400">Otázka týmu</div>
            <div class="font-winky text-lg font-bold leading-snug text-gray-800">
              {{ roundCrewQuestion }}
            </div>
          </template>
        </div>
      </div>

      <!-- přehled rolí -->
      <div class="w-full overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div
          v-for="(a, i) in assignments"
          :key="i"
          class="flex items-center justify-between border-b border-gray-100 px-4 py-2.5 text-sm last:border-0"
        >
          <span class="font-semibold text-gray-700">{{ a.name }}</span>
          <span
            class="rounded-full px-3 py-0.5 text-xs font-bold"
            :class="a.isImposter ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600'"
          >
            {{ a.isImposter ? 'IMPOSTER · ' + (a.word ?? a.question) : 'tým' }}
          </span>
        </div>
      </div>

      <div class="flex flex-wrap items-center justify-center gap-3">
        <button
          class="rounded-2xl bg-primary px-6 py-3 text-sm font-bold text-white shadow-md transition hover:bg-primaryDark"
          @click="playAgainSame"
        >
          🔁 Hrát znovu
        </button>
        <button
          class="rounded-2xl border border-gray-300 bg-white px-6 py-3 text-sm font-bold text-gray-700 transition hover:border-gray-400"
          @click="backToSetup"
        >
          ⚙️ Nové nastavení
        </button>
      </div>
    </section>
  </main>
</template>
