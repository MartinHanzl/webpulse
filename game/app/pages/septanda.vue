<script setup lang="ts">
import { ref, computed, onBeforeUnmount } from 'vue';
import septandaData from '~/assets/data/septanda.json';

useHead({
  title: 'Šeptanda',
  meta: [
    {
      name: 'description',
      content:
        'Jeden neví. Ostatní mluví. Jeden lže schválně. Pass-and-play párty hra pro 4–8 hráčů.',
    },
  ],
});

type Zpusob = 'REKNI' | 'PREDVED' | 'POPIS';
interface Otazka {
  otazka: string;
  odpoved: string;
}
interface Karta {
  cislo: string;
  obor: string;
  zpusob: Zpusob;
  otazky: Otazka[];
}

const data = septandaData as Karta[];

const zpusobLabel: Record<Zpusob, string> = {
  REKNI: 'ŘEKNI',
  PREDVED: 'PŘEDVEĎ',
  POPIS: 'POPIŠ',
};

const zpusobPopis: Record<Zpusob, string> = {
  REKNI: 'Všichni najednou zakřičí svou odpověď. Přes sebe.',
  PREDVED: 'Beze slov, jen tělem. Po kole, každý pět vteřin.',
  POPIS: 'Mluvit se smí, ale odpověď ani žádnou její část říct nesmíš. Po kole, pět vteřin.',
};

type Phase = 'setup' | 'draw' | 'roles' | 'answer' | 'guess' | 'result' | 'gameEnd' | 'tiebreak';
type RevealStep = 'handoff' | 'shown';

// ---- nastavení ----
const phase = ref<Phase>('setup');
const players = ref<string[]>(['Hráč 1', 'Hráč 2', 'Hráč 3', 'Hráč 4']);
const withoutZradce = ref(true);
const startingHadacIndex = ref(0);

function addPlayer() {
  if (players.value.length >= 8) return;
  players.value.push(`Hráč ${players.value.length + 1}`);
}

function removePlayer(i: number) {
  if (players.value.length <= 4) return;
  players.value.splice(i, 1);
  if (i < startingHadacIndex.value) startingHadacIndex.value -= 1;
  else if (i === startingHadacIndex.value) startingHadacIndex.value = 0;
  if (startingHadacIndex.value >= players.value.length) startingHadacIndex.value = 0;
}

// ---- stav celé hry ----
const beans = ref<number[]>([]);
const hadacIndex = ref(0);
const roundsPlayed = ref(0);
const totalRounds = computed(() => players.value.length * 2);
const usedCards = ref<Set<string>>(new Set());

function shuffle<T>(arr: T[]): T[] {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

function startGame() {
  beans.value = players.value.map(() => 3);
  hadacIndex.value = startingHadacIndex.value;
  roundsPlayed.value = 0;
  usedCards.value = new Set();
  startRound();
}

// ---- stav kola ----
const currentCard = ref<Karta | null>(null);
const chosenOtazkaIndex = ref(0); // 0,1,2 — hadač si vybírá naslepo
const roleAssignments = ref<{ playerIndex: number; isZradce: boolean }[]>([]);
const revealIndex = ref(0);
const revealStep = ref<RevealStep>('handoff');

const hadacName = computed(() => players.value[hadacIndex.value]);
const currentQuestion = computed(() => currentCard.value?.otazky[chosenOtazkaIndex.value] ?? null);

const nonHadacOrder = computed(() =>
  shuffle(players.value.map((_, i) => i).filter((i) => i !== hadacIndex.value)),
);

function drawCard() {
  let pool = data.filter((k) => !usedCards.value.has(k.cislo));
  if (pool.length === 0) {
    usedCards.value = new Set();
    pool = data;
  }
  const card = pool[Math.floor(Math.random() * pool.length)];
  usedCards.value.add(card.cislo);
  currentCard.value = card;
  chosenOtazkaIndex.value = 0;
  phase.value = 'draw';
}

function pickOtazka(i: number) {
  chosenOtazkaIndex.value = i;
  buildRoles();
}

function buildRoles() {
  const order = nonHadacOrder.value;

  if (withoutZradce.value) {
    roleAssignments.value = order.map((playerIndex) => ({ playerIndex, isZradce: false }));
  } else {
    // 8 karet rolí: 1 ZRÁDCE, 7 čistých — jedna se odloží neviděná
    const deck = shuffle([true, false, false, false, false, false, false, false]);
    deck.shift(); // jedna karta stranou
    const dealt = deck.slice(0, order.length);
    roleAssignments.value = order.map((playerIndex, i) => ({
      playerIndex,
      isZradce: dealt[i] ?? false,
    }));
  }

  revealIndex.value = 0;
  revealStep.value = 'handoff';
  phase.value = 'roles';
}

const currentReveal = computed(() => roleAssignments.value[revealIndex.value]);
const currentRevealName = computed(
  () => players.value[currentReveal.value?.playerIndex ?? 0] ?? '',
);
const isLastReveal = computed(() => revealIndex.value === roleAssignments.value.length - 1);

function showRole() {
  revealStep.value = 'shown';
}

function hideAndNextRole() {
  if (isLastReveal.value) {
    phase.value = 'answer';
    return;
  }
  revealIndex.value += 1;
  revealStep.value = 'handoff';
}

// ---- odpovídání ----
const discussSeconds = 90;
const timeLeft = ref(0);
const timerRunning = ref(false);
const timerStarted = ref(false);
let timerId: ReturnType<typeof setInterval> | null = null;

function fmtTime(s: number) {
  const m = Math.floor(s / 60);
  const sec = s % 60;
  return `${m}:${String(sec).padStart(2, '0')}`;
}

function startTimer() {
  stopTimer();
  timeLeft.value = discussSeconds;
  timerRunning.value = true;
  timerStarted.value = true;
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

function goToGuess() {
  stopTimer();
  timerStarted.value = false;
  selectedGuessIndex.value = null;
  guessedCorrectly.value = null;
  phase.value = 'guess';
}

// ---- hádání hadače ----
const selectedGuessIndex = ref<number | null>(null); // index do nonHadacOrder
const guessedCorrectly = ref<boolean | null>(null);

function confirmGuess() {
  if (selectedGuessIndex.value === null || guessedCorrectly.value === null) return;
  phase.value = 'result';
}

const selectedPlayer = computed(() =>
  selectedGuessIndex.value !== null ? roleAssignments.value[selectedGuessIndex.value] : null,
);
const selectedPlayerIsZradce = computed(() => selectedPlayer.value?.isZradce ?? false);

const roundResult = computed(() => {
  if (!selectedPlayer.value || guessedCorrectly.value === null) return null;
  const isZradce = selectedPlayerIsZradce.value;
  const correct = guessedCorrectly.value;

  if (!isZradce && correct) return { hadac: 2, vybrany: 1, label: 'Čistý a hadač hádal správně' };
  if (!isZradce && !correct) return { hadac: 0, vybrany: -1, label: 'Čistý a hadač hádal špatně' };
  if (isZradce && !correct) return { hadac: 0, vybrany: 3, label: 'Zrádce a hadač hádal špatně' };
  return { hadac: 2, vybrany: 0, label: 'Zrádce, ale hadač hádal správně' };
});

function applyResultAndNext() {
  const result = roundResult.value;
  const selected = selectedPlayer.value;
  if (result && selected) {
    beans.value[hadacIndex.value] = Math.max(0, beans.value[hadacIndex.value] + result.hadac);
    beans.value[selected.playerIndex] = Math.max(
      0,
      beans.value[selected.playerIndex] + result.vybrany,
    );
  }

  roundsPlayed.value += 1;

  if (roundsPlayed.value >= totalRounds.value) {
    phase.value = 'gameEnd';
    return;
  }

  hadacIndex.value = (hadacIndex.value + 1) % players.value.length;
  startRound();
}

function startRound() {
  drawCard();
}

// ---- konec hry / vyrovnané skóre ----
const ranking = computed(() =>
  players.value
    .map((name, i) => ({ name, beans: beans.value[i] ?? 0, index: i }))
    .sort((a, b) => b.beans - a.beans),
);

const topScore = computed(() => ranking.value[0]?.beans ?? 0);
const tiedPlayers = computed(() => ranking.value.filter((p) => p.beans === topScore.value));

const tiebreakCard = ref<Karta | null>(null);
const tiebreakOtazkaIndex = ref(0);
const tiebreakWinnerIndex = ref<number | null>(null);

function startTiebreak() {
  const pool = data.filter((k) => !usedCards.value.has(k.cislo));
  const source = pool.length ? pool : data;
  tiebreakCard.value = source[Math.floor(Math.random() * source.length)];
  tiebreakOtazkaIndex.value = Math.floor(Math.random() * 3);
  tiebreakWinnerIndex.value = null;
  phase.value = 'tiebreak';
}

function finishTiebreak() {
  if (tiebreakWinnerIndex.value !== null && tiebreakWinnerIndex.value >= 0) {
    beans.value[tiebreakWinnerIndex.value] = (beans.value[tiebreakWinnerIndex.value] ?? 0) + 1;
  }
  phase.value = 'gameEnd';
}

function restartGame() {
  phase.value = 'setup';
}
onBeforeUnmount(stopTimer);
</script>

<template>
  <main class="mx-auto flex min-h-[80vh] w-full max-w-xl flex-col items-center gap-8 py-6">
    <header class="text-center">
      <h1 class="font-winky text-3xl font-bold sm:text-4xl">Šeptanda</h1>
      <p class="mt-2 text-sm text-gray-500">Jeden neví. Ostatní mluví. Jeden lže schválně.</p>
    </header>

    <!-- ====================== NASTAVENÍ ====================== -->
    <section v-if="phase === 'setup'" class="flex w-full flex-col gap-8">
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
              :disabled="players.length <= 4"
              @click="removePlayer(i)"
            >
              ✕
            </button>
          </div>
        </div>
        <button
          class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-gray-400 disabled:cursor-not-allowed disabled:opacity-40"
          :disabled="players.length >= 8"
          @click="addPlayer"
        >
          + Přidat hráče
        </button>
      </div>

      <div class="flex flex-col items-center gap-3">
        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">
          Kdo začíná jako hadač
        </span>
        <div class="flex flex-wrap items-center justify-center gap-2">
          <button
            v-for="(p, i) in players"
            :key="i"
            class="rounded-xl border px-4 py-2 text-sm font-semibold transition"
            :class="
              startingHadacIndex === i
                ? 'border-primary bg-primary/10 text-primaryDark'
                : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
            "
            @click="startingHadacIndex = i"
          >
            {{ p }}
          </button>
        </div>
      </div>

      <div class="flex flex-col items-center gap-3">
        <label class="flex items-center gap-3 rounded-xl border border-gray-300 bg-white px-4 py-3">
          <input v-model="withoutZradce" type="checkbox" class="h-4 w-4" />
          <span class="text-sm text-gray-700">
            Hrát bez zrádce
            <span class="block text-xs text-gray-400">Doporučeno na první hru</span>
          </span>
        </label>
      </div>

      <button
        class="mx-auto rounded-2xl bg-primary px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-primaryDark"
        @click="startGame"
      >
        Spustit hru
      </button>
    </section>

    <!-- ====================== HADAČ TÁHNE KARTU ====================== -->
    <section v-else-if="phase === 'draw'" class="flex w-full flex-col items-center gap-6">
      <div class="text-xs text-gray-400">Kolo {{ roundsPlayed + 1 }} / {{ totalRounds }}</div>
      <div
        class="flex w-full flex-col items-center gap-6 rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm"
      >
        <div class="text-5xl">🤫</div>
        <div>
          <div class="text-xs uppercase tracking-wide text-gray-400">Hadač je</div>
          <div class="mt-1 font-winky text-2xl font-bold text-primaryDark">{{ hadacName }}</div>
        </div>
        <p class="max-w-sm text-sm text-gray-500">
          {{ hadacName }} drží zařízení otočené od sebe jako jídelní lístek. Vidí jen tohle. Ostatní
          vidí otázky na zadní straně.
        </p>
        <div class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-6 py-5">
          <div class="text-xs uppercase tracking-wide text-gray-400">Obor</div>
          <div class="font-winky text-2xl font-bold text-gray-800">{{ currentCard?.obor }}</div>
          <div class="mt-2 text-xs uppercase tracking-wide text-gray-400">
            Karta {{ currentCard?.cislo }} ·
            {{ currentCard ? zpusobLabel[currentCard.zpusob] : '' }}
          </div>
        </div>
        <p class="text-xs text-gray-400">
          {{ hadacName }} nahlas přečte obor, číslo karty a způsob — a naslepo vybere jednu ze tří
          otázek.
        </p>
        <div class="flex items-center gap-3">
          <button
            v-for="i in [0, 1, 2]"
            :key="i"
            class="h-12 w-12 rounded-xl border text-lg font-bold transition hover:border-primary hover:bg-primary/10"
            @click="pickOtazka(i)"
          >
            {{ i + 1 }}
          </button>
        </div>
      </div>
    </section>

    <!-- ====================== ROZDÁVÁNÍ ROLÍ ====================== -->
    <section v-else-if="phase === 'roles'" class="flex w-full flex-col items-center gap-6">
      <div class="text-xs text-gray-400">
        Role {{ revealIndex + 1 }} / {{ roleAssignments.length }}
      </div>

      <div
        v-if="revealStep === 'handoff'"
        class="flex w-full flex-col items-center gap-6 rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm"
      >
        <div class="text-5xl">📱➡️</div>
        <div>
          <div class="text-xs uppercase tracking-wide text-gray-400">Předej zařízení hráči</div>
          <div class="mt-1 font-winky text-2xl font-bold text-primaryDark">
            {{ currentRevealName }}
          </div>
        </div>
        <p class="text-sm text-gray-500">Hadač se nedívá. Ať se nikdo jiný nedívá taky!</p>
        <button
          class="rounded-2xl bg-primary px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-primaryDark"
          @click="showRole"
        >
          Jsem {{ currentRevealName }} – zobrazit roli
        </button>
      </div>

      <div
        v-else
        class="flex w-full flex-col items-center gap-6 rounded-3xl border-2 p-8 text-center shadow-lg"
        :class="
          currentReveal?.isZradce ? 'border-rose-300 bg-rose-50' : 'border-primary/40 bg-primary/5'
        "
      >
        <div class="text-xs uppercase tracking-wide text-gray-400">{{ currentRevealName }}</div>
        <div
          class="font-winky text-3xl font-bold"
          :class="currentReveal?.isZradce ? 'text-rose-600' : 'text-gray-800'"
        >
          {{ currentReveal?.isZradce ? 'ZRÁDCE' : 'čistý' }}
        </div>
        <p v-if="currentReveal?.isZradce" class="max-w-sm text-xs text-gray-500">
          Musíš odpovědět špatně a nechat se hadačem vybrat. Nepřeháněj to.
        </p>
        <p v-else class="max-w-sm text-xs text-gray-500">Odpověz podle svého nejlepšího vědomí.</p>
        <button
          class="rounded-2xl bg-gray-800 px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-gray-700"
          @click="hideAndNextRole"
        >
          {{ isLastReveal ? 'Schovat a začít odpovídat' : 'Schovat a předat dál' }}
        </button>
      </div>
    </section>

    <!-- ====================== ODPOVÍDÁNÍ ====================== -->
    <section v-else-if="phase === 'answer'" class="flex w-full flex-col items-center gap-6">
      <div
        class="flex w-full flex-col items-center gap-5 rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm"
      >
        <div class="text-xs uppercase tracking-wide text-gray-400">
          {{ currentCard ? zpusobLabel[currentCard.zpusob] : '' }}
        </div>
        <div class="font-winky text-2xl font-bold leading-snug text-gray-800">
          {{ currentQuestion?.otazka }}
        </div>
        <p class="max-w-sm text-sm text-gray-500">
          {{ currentCard ? zpusobPopis[currentCard.zpusob] : '' }}
        </p>
        <p class="text-xs text-gray-400">
          Odpovídají všichni kromě hadače – {{ hadacName }} se nedívá.
        </p>

        <div class="flex flex-col items-center gap-3 border-t border-gray-100 pt-5">
          <div class="font-mono text-4xl font-bold tabular-nums text-gray-800">
            {{ fmtTime(timerStarted ? timeLeft : discussSeconds) }}
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
          @click="goToGuess"
        >
          Předat zařízení hadači
        </button>
      </div>
    </section>

    <!-- ====================== HADAČ HÁDÁ ====================== -->
    <section v-else-if="phase === 'guess'" class="flex w-full flex-col items-center gap-6">
      <div
        class="flex w-full flex-col items-center gap-5 rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm"
      >
        <div class="text-5xl">👉</div>
        <h2 class="font-winky text-2xl font-bold">{{ hadacName }} ukazuje a hádá</h2>
        <p class="max-w-sm text-sm text-gray-500">
          Vyber, na koho hadač ukázal, a řekni nahlas, co si myslíš, že odpověděl.
        </p>

        <div class="grid w-full grid-cols-2 gap-2">
          <button
            v-for="(a, i) in roleAssignments"
            :key="i"
            class="rounded-xl border px-3 py-2 text-sm font-semibold transition"
            :class="
              selectedGuessIndex === i
                ? 'border-primary bg-primary/10 text-primaryDark'
                : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
            "
            @click="selectedGuessIndex = i"
          >
            {{ players[a.playerIndex] }}
          </button>
        </div>

        <div
          v-if="selectedGuessIndex !== null"
          class="flex w-full flex-col items-center gap-3 border-t border-gray-100 pt-5"
        >
          <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">
            Hadačova odpověď byla správná?
          </span>
          <div class="flex gap-3">
            <button
              class="rounded-xl border px-4 py-2 text-sm font-semibold transition"
              :class="
                guessedCorrectly === true
                  ? 'border-emerald-400 bg-emerald-50 text-emerald-600'
                  : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
              "
              @click="guessedCorrectly = true"
            >
              ✅ Trefil
            </button>
            <button
              class="rounded-xl border px-4 py-2 text-sm font-semibold transition"
              :class="
                guessedCorrectly === false
                  ? 'border-rose-400 bg-rose-50 text-rose-600'
                  : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
              "
              @click="guessedCorrectly = false"
            >
              ❌ Vedle
            </button>
          </div>
        </div>

        <button
          class="mt-2 rounded-2xl bg-primary px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-primaryDark disabled:cursor-not-allowed disabled:opacity-40"
          :disabled="selectedGuessIndex === null || guessedCorrectly === null"
          @click="confirmGuess"
        >
          Odhalit
        </button>
      </div>
    </section>

    <!-- ====================== ODHALENÍ KOLA ====================== -->
    <section v-else-if="phase === 'result'" class="flex w-full flex-col items-center gap-6">
      <div
        class="flex w-full flex-col items-center gap-4 rounded-3xl border-2 border-primary/40 bg-primary/5 p-8 text-center shadow-lg"
      >
        <div class="text-5xl">🎭</div>
        <div class="text-xs uppercase tracking-wide text-gray-400">Správná odpověď</div>
        <div class="font-winky text-2xl font-bold text-gray-800">
          {{ currentQuestion?.odpoved }}
        </div>

        <div class="mt-2 w-full border-t border-gray-200 pt-4 text-sm text-gray-600">
          <div class="text-xs uppercase tracking-wide text-gray-400">
            {{ selectedPlayer ? players[selectedPlayer.playerIndex] : '' }} byl(a)
          </div>
          <div
            class="font-winky text-xl font-bold"
            :class="selectedPlayerIsZradce ? 'text-rose-600' : 'text-emerald-600'"
          >
            {{ selectedPlayerIsZradce ? 'ZRÁDCE' : 'čistý' }}
          </div>
        </div>

        <div
          v-if="roundResult"
          class="mt-2 w-full rounded-2xl bg-white px-5 py-4 text-sm text-gray-600"
        >
          <div class="text-xs uppercase tracking-wide text-gray-400">{{ roundResult.label }}</div>
          <div class="mt-1 flex justify-center gap-6 font-winky text-lg font-bold">
            <span
              >{{ hadacName }}: {{ roundResult.hadac >= 0 ? '+' : '' }}{{ roundResult.hadac }}</span
            >
            <span>
              {{ selectedPlayer ? players[selectedPlayer.playerIndex] : '' }}:
              {{ roundResult.vybrany >= 0 ? '+' : '' }}{{ roundResult.vybrany }}
            </span>
          </div>
        </div>
      </div>

      <!-- fazole -->
      <div class="w-full overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div
          v-for="(name, i) in players"
          :key="i"
          class="flex items-center justify-between border-b border-gray-100 px-4 py-2.5 text-sm last:border-0"
        >
          <span class="font-semibold text-gray-700">{{ name }}</span>
          <span class="rounded-full bg-primary/10 px-3 py-0.5 text-xs font-bold text-primaryDark">
            🫘 {{ beans[i] }}
          </span>
        </div>
      </div>

      <button
        class="rounded-2xl bg-primary px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-primaryDark"
        @click="applyResultAndNext"
      >
        {{ roundsPlayed + 1 >= totalRounds ? 'Zobrazit konečné pořadí' : 'Další kolo' }}
      </button>
    </section>

    <!-- ====================== KONEC HRY ====================== -->
    <section v-else-if="phase === 'gameEnd'" class="flex w-full flex-col items-center gap-6">
      <div class="text-5xl">🏆</div>
      <h2 class="font-winky text-2xl font-bold">Konec hry</h2>

      <div class="w-full overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div
          v-for="(p, i) in ranking"
          :key="p.index"
          class="flex items-center justify-between border-b border-gray-100 px-4 py-3 text-sm last:border-0"
          :class="p.beans === topScore ? 'bg-primary/5' : ''"
        >
          <span class="flex items-center gap-2 font-semibold text-gray-700">
            <span class="text-xs text-gray-400">{{ i + 1 }}.</span>
            {{ p.name }}
          </span>
          <span class="rounded-full bg-primary/10 px-3 py-0.5 text-xs font-bold text-primaryDark">
            🫘 {{ p.beans }}
          </span>
        </div>
      </div>

      <div v-if="tiedPlayers.length > 1" class="flex flex-col items-center gap-3">
        <p class="text-sm text-gray-500">
          Shoda na prvním místě mezi: {{ tiedPlayers.map((p) => p.name).join(', ') }}
        </p>
        <button
          class="rounded-2xl border border-gray-300 bg-white px-6 py-3 text-sm font-bold text-gray-700 transition hover:border-gray-400"
          @click="startTiebreak"
        >
          Rozhodující otázka
        </button>
      </div>

      <button
        class="rounded-2xl bg-primary px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-primaryDark"
        @click="restartGame"
      >
        ⚙️ Nová hra
      </button>
    </section>

    <!-- ====================== ROZHODUJÍCÍ OTÁZKA ====================== -->
    <section v-else-if="phase === 'tiebreak'" class="flex w-full flex-col items-center gap-6">
      <div
        class="flex w-full flex-col items-center gap-5 rounded-3xl border border-gray-200 bg-white p-8 text-center shadow-sm"
      >
        <div class="text-5xl">🎲</div>
        <h2 class="font-winky text-xl font-bold">Rozhodující otázka</h2>
        <p class="max-w-sm text-sm text-gray-500">
          Odpovídají jen: {{ tiedPlayers.map((p) => p.name).join(', ') }}. Zbytek stolu mlčí.
        </p>
        <div class="w-full rounded-2xl bg-gray-50 px-5 py-4">
          <div class="text-xs uppercase tracking-wide text-gray-400">
            {{ tiebreakCard?.obor }}
          </div>
          <div class="mt-1 font-winky text-xl font-bold text-gray-800">
            {{ tiebreakCard?.otazky[tiebreakOtazkaIndex]?.otazka }}
          </div>
        </div>

        <div class="flex flex-col items-center gap-3 border-t border-gray-100 pt-5">
          <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">
            Kdo trefil odpověď?
          </span>
          <div class="flex flex-wrap justify-center gap-2">
            <button
              v-for="p in tiedPlayers"
              :key="p.index"
              class="rounded-xl border px-4 py-2 text-sm font-semibold transition"
              :class="
                tiebreakWinnerIndex === p.index
                  ? 'border-primary bg-primary/10 text-primaryDark'
                  : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
              "
              @click="tiebreakWinnerIndex = p.index"
            >
              {{ p.name }}
            </button>
            <button
              class="rounded-xl border px-4 py-2 text-sm font-semibold transition"
              :class="
                tiebreakWinnerIndex === -1
                  ? 'border-gray-500 bg-gray-100 text-gray-700'
                  : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
              "
              @click="tiebreakWinnerIndex = -1"
            >
              Nikdo netrefil
            </button>
          </div>
        </div>

        <div v-if="tiebreakWinnerIndex !== null" class="text-sm text-gray-500">
          Odpověď:
          <span class="font-semibold text-gray-700">{{
            tiebreakCard?.otazky[tiebreakOtazkaIndex]?.odpoved
          }}</span>
        </div>

        <button
          class="mt-2 rounded-2xl bg-primary px-8 py-3 text-base font-bold text-white shadow-md transition hover:bg-primaryDark disabled:cursor-not-allowed disabled:opacity-40"
          :disabled="tiebreakWinnerIndex === null"
          @click="finishTiebreak"
        >
          Ukončit hru
        </button>
      </div>
    </section>
  </main>
</template>
