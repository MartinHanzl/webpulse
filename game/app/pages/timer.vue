<script setup lang="ts">
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';

useHead({
  title: 'Timer výzva',
  meta: [
    {
      name: 'description',
      content:
        'Trefíš se přesně do cílového času? Spusť stopky, zastav je ve správný okamžik. Dva módy: viditelný a skrytý čas.',
    },
  ],
});

type Mode = 'show' | 'hide';
type Status = 'idle' | 'running' | 'done';

const mode = ref<Mode>('show');
const status = ref<Status>('idle');

const targetSec = ref(5);
const customSec = ref<number | null>(null);

const elapsedMs = ref(0);
let startPerf = 0;
let rafId = 0;

const attempts = ref(0);
const bestDiffSec = ref<number | null>(null);

// ---- formátování MM:SS:CS ----
function pad2(n: number) {
  return String(n).padStart(2, '0');
}

function fmt(ms: number) {
  const totalCs = Math.floor(ms / 10);
  const cs = totalCs % 100;
  const totalS = Math.floor(totalCs / 100);
  const s = totalS % 60;
  const m = Math.floor(totalS / 60);
  return `${pad2(m)}:${pad2(s)}:${pad2(cs)}`;
}

const isHiddenRunning = computed(() => status.value === 'running' && mode.value === 'hide');

const displayMs = computed(() => (status.value === 'idle' ? 0 : elapsedMs.value));

const ledText = computed(() => (isHiddenRunning.value ? '--:--:--' : fmt(displayMs.value)));

const targetText = computed(() => fmt(targetSec.value * 1000));

// ---- vyhodnocení ----
const diffSec = computed(() =>
  status.value === 'done' ? elapsedMs.value / 1000 - targetSec.value : 0,
);
const absDiff = computed(() => Math.abs(diffSec.value));

const diffText = computed(() => `${diffSec.value >= 0 ? '+' : '−'}${absDiff.value.toFixed(2)} s`);

const result = computed(() => {
  const d = absDiff.value;
  if (d < 0.1) return { label: 'Perfektní! 🎯', tone: 'great' };
  if (d < 0.3) return { label: 'Výborné!', tone: 'great' };
  if (d < 0.7) return { label: 'Dobré', tone: 'good' };
  if (d < 1.5) return { label: 'Ucházející', tone: 'mid' };
  return { label: 'Mimo', tone: 'bad' };
});

const toneClass: Record<string, string> = {
  great: 'text-emerald-400',
  good: 'text-lime-400',
  mid: 'text-amber-400',
  bad: 'text-rose-400',
};

// ---- časovač ----
function loop() {
  elapsedMs.value = performance.now() - startPerf;
  rafId = requestAnimationFrame(loop);
}

function start() {
  cancelAnimationFrame(rafId);
  status.value = 'running';
  elapsedMs.value = 0;
  startPerf = performance.now();
  rafId = requestAnimationFrame(loop);
}

function stop() {
  cancelAnimationFrame(rafId);
  elapsedMs.value = performance.now() - startPerf;
  status.value = 'done';
  attempts.value += 1;
  if (bestDiffSec.value === null || absDiff.value < bestDiffSec.value) {
    bestDiffSec.value = absDiff.value;
  }
}

function toggle() {
  if (status.value === 'running') stop();
  else start();
}

function reset() {
  cancelAnimationFrame(rafId);
  status.value = 'idle';
  elapsedMs.value = 0;
}

// ---- nastavení cíle ----
const presets = [3, 5, 10];

function setMode(m: Mode) {
  mode.value = m;
  reset();
}

function setTarget(sec: number) {
  targetSec.value = sec;
  customSec.value = null;
  reset();
}

function applyCustom() {
  const v = Number(customSec.value);
  if (Number.isFinite(v) && v > 0) {
    targetSec.value = Math.min(v, 3599);
    reset();
  }
}

function randomTarget() {
  targetSec.value = Math.round((Math.random() * 13 + 2) * 10) / 10; // 2.0–15.0 s
  customSec.value = null;
  reset();
}

const isPreset = (sec: number) => customSec.value === null && targetSec.value === sec;

// ---- klávesnice (mezerník) ----
function onKey(e: KeyboardEvent) {
  if (e.code === 'Space') {
    const el = e.target as HTMLElement;
    if (el && (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA')) return;
    e.preventDefault();
    toggle();
  }
}

onMounted(() => window.addEventListener('keydown', onKey));
onBeforeUnmount(() => {
  cancelAnimationFrame(rafId);
  window.removeEventListener('keydown', onKey);
});
</script>

<template>
  <main class="flex min-h-[80vh] flex-col items-center justify-center gap-8 py-6">
    <header class="text-center">
      <h1 class="font-winky text-3xl font-bold sm:text-4xl">Timer výzva</h1>
      <p class="mt-2 max-w-md text-sm text-gray-500">
        Trefíš se přesně do cílového času? Spusť stopky a zastav je ve správný okamžik.
      </p>
    </header>

    <!-- Výběr módu -->
    <div class="inline-flex rounded-full border border-gray-300 bg-white p-1 shadow-sm">
      <button
        class="rounded-full px-5 py-2 text-sm font-semibold transition"
        :class="
          mode === 'show' ? 'bg-primary text-white shadow' : 'text-gray-600 hover:bg-gray-100'
        "
        @click="setMode('show')"
      >
        Zobrazit čas
      </button>
      <button
        class="rounded-full px-5 py-2 text-sm font-semibold transition"
        :class="
          mode === 'hide' ? 'bg-primary text-white shadow' : 'text-gray-600 hover:bg-gray-100'
        "
        @click="setMode('hide')"
      >
        Skrýt čas
      </button>
    </div>

    <!-- Cílový čas -->
    <div class="flex flex-col items-center gap-3">
      <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Cílový čas</span>
      <div class="flex flex-wrap items-center justify-center gap-2">
        <button
          v-for="p in presets"
          :key="p"
          class="rounded-xl border px-4 py-2 text-sm font-semibold transition"
          :class="
            isPreset(p)
              ? 'border-primary bg-primary/10 text-primaryDark'
              : 'border-gray-300 bg-white text-gray-700 hover:border-gray-400'
          "
          @click="setTarget(p)"
        >
          {{ p }} s
        </button>

        <div class="flex items-center overflow-hidden rounded-xl border border-gray-300 bg-white">
          <input
            v-model="customSec"
            type="number"
            min="0.1"
            step="0.1"
            inputmode="decimal"
            placeholder="vlastní"
            class="w-24 border-0 bg-transparent px-3 py-2 text-sm focus:ring-0"
            @keyup.enter="applyCustom"
          />
          <button
            class="border-l border-gray-300 px-3 py-2 text-sm font-semibold text-primaryDark transition hover:bg-primary/10"
            @click="applyCustom"
          >
            Nastavit
          </button>
        </div>

        <button
          class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-gray-400"
          @click="randomTarget"
        >
          🎲 Náhodný
        </button>
      </div>
    </div>

    <!-- LED zařízení -->
    <div class="led-device">
      <div class="led-slot"></div>
      <div class="led-screen">
        <span class="led-ghost">88:88:88</span>
        <span class="led-text" :class="{ 'led-dim': isHiddenRunning }">{{ ledText }}</span>
      </div>
      <div class="led-label">CÍL {{ targetText }}</div>
    </div>

    <!-- Velké tlačítko -->
    <button
      class="big-btn"
      :class="{ 'big-btn--running': status === 'running' }"
      :aria-label="status === 'running' ? 'Stop' : 'Start'"
      @click="toggle"
    >
      <span class="big-btn__dome">
        <span class="big-btn__text">{{ status === 'running' ? 'STOP' : 'START' }}</span>
      </span>
    </button>

    <p class="text-xs text-gray-400">Tip: stopky spustíš i zastavíš mezerníkem.</p>

    <!-- Výsledek -->
    <div
      v-if="status === 'done'"
      class="flex w-full max-w-sm flex-col items-center gap-2 rounded-2xl border border-gray-200 bg-white p-5 text-center shadow-sm"
    >
      <span class="text-2xl font-bold" :class="toneClass[result.tone]">{{ result.label }}</span>
      <div class="flex items-center gap-6 text-sm text-gray-600">
        <div>
          <div class="text-xs uppercase tracking-wide text-gray-400">Tvůj čas</div>
          <div class="font-mono text-lg font-bold text-gray-800">{{ fmt(elapsedMs) }}</div>
        </div>
        <div>
          <div class="text-xs uppercase tracking-wide text-gray-400">Rozdíl</div>
          <div class="font-mono text-lg font-bold" :class="toneClass[result.tone]">
            {{ diffText }}
          </div>
        </div>
      </div>
      <button
        class="mt-2 rounded-xl bg-primary px-5 py-2 text-sm font-semibold text-white transition hover:bg-primaryDark"
        @click="start"
      >
        Nový pokus
      </button>
    </div>

    <!-- Statistika -->
    <div v-if="attempts > 0" class="text-xs text-gray-400">
      Pokusů: {{ attempts }}
      <template v-if="bestDiffSec !== null">
        · Nejlepší odchylka: {{ bestDiffSec.toFixed(2) }} s
      </template>
    </div>
  </main>
</template>

<style scoped>
.led-device {
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.75rem;
  padding: 1.5rem 1.75rem 1.25rem;
  border-radius: 1.25rem;
  background: linear-gradient(160deg, #2a2a2e 0%, #161618 55%, #0c0c0d 100%);
  box-shadow:
    inset 0 1px 0 rgba(255, 255, 255, 0.08),
    0 18px 40px -12px rgba(0, 0, 0, 0.55),
    0 4px 10px rgba(0, 0, 0, 0.4);
  border: 1px solid #000;
}

/* horní štěrbina jako na zařízení */
.led-slot {
  width: 70%;
  height: 6px;
  border-radius: 999px;
  background: linear-gradient(180deg, #000 0%, #1c1c1e 100%);
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.8);
}

.led-screen {
  position: relative;
  padding: 0.6rem 1.2rem;
  border-radius: 0.5rem;
  background: #050807;
  box-shadow: inset 0 0 18px rgba(0, 0, 0, 0.9);
}

.led-ghost,
.led-text {
  font-family: 'Courier New', ui-monospace, monospace;
  font-size: clamp(2.5rem, 11vw, 4.5rem);
  font-weight: 700;
  letter-spacing: 0.06em;
  line-height: 1;
  font-variant-numeric: tabular-nums;
}

/* slabý „duch" segmentů 88:88:88 v pozadí */
.led-ghost {
  color: rgba(34, 197, 94, 0.08);
  display: block;
}

.led-text {
  position: absolute;
  inset: 0.6rem 1.2rem;
  color: #2bff88;
  text-shadow:
    0 0 6px rgba(43, 255, 136, 0.8),
    0 0 16px rgba(43, 255, 136, 0.5),
    0 0 32px rgba(43, 255, 136, 0.3);
}

.led-dim {
  color: rgba(43, 255, 136, 0.35);
  text-shadow: 0 0 6px rgba(43, 255, 136, 0.25);
}

.led-label {
  font-family: ui-monospace, monospace;
  font-size: 0.7rem;
  letter-spacing: 0.2em;
  color: rgba(43, 255, 136, 0.55);
}

/* velké kulaté tlačítko */
.big-btn {
  position: relative;
  width: 9rem;
  height: 9rem;
  border-radius: 50%;
  padding: 0.6rem;
  background: linear-gradient(180deg, #2a2a2e 0%, #19191b 60%, #0c0c0d 100%);
  box-shadow:
    0 14px 28px -8px rgba(0, 0, 0, 0.6),
    inset 0 2px 3px rgba(255, 255, 255, 0.12);
  border: 1px solid #000;
  cursor: pointer;
  transition: transform 0.08s ease;
}

.big-btn__dome {
  display: flex;
  height: 100%;
  width: 100%;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: radial-gradient(circle at 50% 35%, #ffffff 0%, #f2f2f2 45%, #d2d2d2 100%);
  box-shadow:
    inset 0 -6px 12px rgba(0, 0, 0, 0.18),
    inset 0 4px 8px rgba(255, 255, 255, 0.9),
    0 2px 4px rgba(0, 0, 0, 0.25);
  transition:
    background 0.15s ease,
    box-shadow 0.08s ease;
}

.big-btn__text {
  font-family: ui-monospace, monospace;
  font-size: 1.1rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  color: #3a3a3a;
}

.big-btn:hover .big-btn__dome {
  background: radial-gradient(circle at 50% 35%, #ffffff 0%, #fafafa 45%, #dcdcdc 100%);
}

.big-btn:active {
  transform: translateY(2px) scale(0.98);
}

.big-btn:active .big-btn__dome {
  box-shadow:
    inset 0 6px 14px rgba(0, 0, 0, 0.25),
    inset 0 1px 3px rgba(255, 255, 255, 0.6);
}

.big-btn--running .big-btn__dome {
  background: radial-gradient(circle at 50% 35%, #ff9a9a 0%, #f8534f 50%, #c81e1e 100%);
}

.big-btn--running .big-btn__text {
  color: #fff;
}

.big-btn--running .big-btn__dome {
  box-shadow:
    inset 0 -6px 12px rgba(0, 0, 0, 0.25),
    inset 0 4px 8px rgba(255, 255, 255, 0.4),
    0 0 22px rgba(248, 83, 79, 0.55);
}
</style>
