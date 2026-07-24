<script setup lang="ts">
import { computed } from 'vue';
import { TrophyIcon } from '@heroicons/vue/24/solid';

interface PodiumPlayer {
  id: number;
  player_id?: number;
  player_name: string;
  net_score: number | null;
  relative_to_par: number | null;
}

const props = defineProps({
  players: {
    type: Array as () => PodiumPlayer[],
    required: true,
    default: () => [],
  },
});

function relativeLabel(relative: number | null): string {
  if (relative === null || relative === undefined) return '-';
  if (relative === 0) return 'E';
  return relative > 0 ? `+${relative}` : `${relative}`;
}

// Rank by net_score ascending (lowest wins), ties share the same place.
const ranked = computed(() => {
  const scored = props.players
    .filter((p) => p.net_score !== null && p.net_score !== undefined)
    .slice()
    .sort((a, b) => (a.net_score as number) - (b.net_score as number));
  let place = 0;
  let prevScore: number | null = null;
  return scored.map((p, idx) => {
    if (prevScore === null || p.net_score !== prevScore) {
      place = idx + 1;
      prevScore = p.net_score;
    }
    return { ...p, place };
  });
});

// Podium slots ordered visually: 2nd (left), 1st (center), 3rd (right).
const podium = computed(() => {
  const byPlace = (n: number) => ranked.value.filter((p) => p.place === n);
  return [
    { place: 2, players: byPlace(2) },
    { place: 1, players: byPlace(1) },
    { place: 3, players: byPlace(3) },
  ].filter((slot) => slot.players.length);
});

const meta: Record<number, { block: string; badge: string; height: string; ring: string }> = {
  1: {
    block: 'bg-gradient-to-b from-amber-300 to-amber-400',
    badge: 'bg-amber-100 text-amber-700 ring-amber-300',
    height: 'h-28',
    ring: 'ring-amber-300',
  },
  2: {
    block: 'bg-gradient-to-b from-slate-300 to-slate-400',
    badge: 'bg-slate-100 text-slate-600 ring-slate-300',
    height: 'h-20',
    ring: 'ring-slate-300',
  },
  3: {
    block: 'bg-gradient-to-b from-orange-300 to-orange-400',
    badge: 'bg-orange-100 text-orange-700 ring-orange-300',
    height: 'h-16',
    ring: 'ring-orange-300',
  },
};

function initials(name: string): string {
  return name
    .split(/\s+/)
    .filter(Boolean)
    .slice(0, 2)
    .map((w) => w[0]?.toUpperCase())
    .join('');
}
</script>

<template>
  <div v-if="podium.length" class="flex items-end justify-center gap-3 sm:gap-6">
    <div v-for="slot in podium" :key="slot.place" class="flex w-24 flex-col items-center sm:w-32">
      <!-- Players on this place (usually one; multiple only on a tie) -->
      <div class="mb-2 flex flex-col items-center gap-2">
        <template v-for="player in slot.players" :key="player.id">
          <div class="flex flex-col items-center">
            <div
              class="flex size-12 items-center justify-center rounded-full bg-white text-sm font-extrabold text-slate-600 shadow-sm ring-2"
              :class="meta[slot.place]?.ring"
            >
              {{ initials(player.player_name) }}
            </div>
            <div class="mt-1.5 max-w-[7rem] truncate text-center text-xs font-bold text-slate-800">
              {{ player.player_name }}
            </div>
            <div class="text-[11px] font-semibold tabular-nums text-slate-500">
              {{ player.net_score }} ({{ relativeLabel(player.relative_to_par) }})
            </div>
          </div>
        </template>
      </div>

      <!-- Podium block -->
      <div
        class="flex w-full flex-col items-center justify-start rounded-t-xl pt-2 text-white shadow-inner"
        :class="[meta[slot.place]?.block, meta[slot.place]?.height]"
      >
        <span
          class="inline-flex size-7 items-center justify-center rounded-full text-xs font-extrabold ring-1 ring-inset"
          :class="meta[slot.place]?.badge"
        >
          {{ slot.place }}
        </span>
        <TrophyIcon v-if="slot.place === 1" class="mt-1 size-5 text-amber-50" />
      </div>
    </div>
  </div>
</template>
