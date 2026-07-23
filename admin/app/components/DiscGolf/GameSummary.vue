<script setup lang="ts">
import { computed } from 'vue';
import { TrophyIcon } from '@heroicons/vue/24/solid';

interface GamePlayer {
  id: number;
  player_id: number;
  player_name: string;
  handicap: number;
  total_throws: number;
  net_score: number;
  relative_to_par: number;
  position: number;
}

const props = defineProps({
  players: {
    type: Array as () => GamePlayer[],
    required: true,
    default: () => [],
  },
  par: {
    type: Number,
    required: false,
    default: 0,
  },
});

const { formatRelative, relativeColor } = useDiscGolf();

const standings = computed(() => [...props.players].sort((a, b) => a.net_score - b.net_score));
</script>

<template>
  <div class="space-y-4">
    <div class="flex items-center gap-3">
      <div class="flex size-9 items-center justify-center rounded-lg bg-amber-50 text-amber-500">
        <TrophyIcon class="size-5" />
      </div>
      <div>
        <h3 class="text-lg font-bold text-slate-900">Výsledky hry</h3>
        <p class="text-xs text-slate-500">Par hřiště: {{ par }} · Řazeno podle čistého skóre</p>
      </div>
    </div>

    <div class="overflow-x-auto rounded-2xl ring-1 ring-slate-200">
      <table class="w-full min-w-[600px] text-left text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
          <tr>
            <th class="px-4 py-3 text-center">Pořadí</th>
            <th class="px-4 py-3">Hráč</th>
            <th class="px-4 py-3 text-center">Skóre (hody)</th>
            <th class="px-4 py-3 text-center">Handicap</th>
            <th class="px-4 py-3 text-center">Čisté skóre</th>
            <th class="px-4 py-3 text-center">+/- par</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          <tr
            v-for="(player, index) in standings"
            :key="player.id"
            :class="index === 0 ? 'bg-amber-50/60' : 'hover:bg-slate-50'"
          >
            <td class="px-4 py-3 text-center">
              <span
                v-if="index === 0"
                class="inline-flex items-center gap-1 font-bold text-amber-600"
              >
                <TrophyIcon class="size-4" /> 1.
              </span>
              <span v-else class="font-semibold text-slate-500">{{ index + 1 }}.</span>
            </td>
            <td class="px-4 py-3 font-semibold text-slate-800">{{ player.player_name }}</td>
            <td class="px-4 py-3 text-center tabular-nums text-slate-700">
              {{ player.total_throws }}
            </td>
            <td class="px-4 py-3 text-center tabular-nums text-slate-500">
              {{ player.handicap }}
            </td>
            <td class="px-4 py-3 text-center font-semibold tabular-nums text-slate-900">
              {{ player.net_score }}
            </td>
            <td
              class="px-4 py-3 text-center font-bold tabular-nums"
              :class="relativeColor(player.relative_to_par)"
            >
              {{ formatRelative(player.relative_to_par) }}
            </td>
          </tr>
          <tr v-if="!standings.length">
            <td colspan="6" class="px-4 py-8 text-center text-sm italic text-slate-400">
              Žádní hráči.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
