<script setup lang="ts">
import { computed } from 'vue';
import { InformationCircleIcon } from '@heroicons/vue/24/outline';
import TrendChart from '~/components/DiscGolf/TrendChart.vue';

const recommendedHandicapInfo =
  'Doporučený handicap = korekce, která by hráče dostala na par, tj. MÍNUS vážený průměr skóre ' +
  'vůči paru (počet hodů − par, BEZ handicapu) z posledních 5 her na tomto hřišti. ' +
  'Novější hry mají vyšší váhu (nejnovější hra váha 5, nejstarší z pětice váha 1). ' +
  'Např. průměr +5 nad par → handicap −5; průměr −2 pod par → handicap +2. Zaokrouhleno.';

interface PlayerStat {
  player_id: number;
  player_name: string;
  games_played: number;
  best_score: number | null;
  avg_score: number | null;
  worst_score: number | null;
  best_relative: number | null;
  avg_relative: number | null;
  last5_relative: number | null;
  last10_relative: number | null;
  trend: string;
  recommended_handicap?: number | null;
  chart: { date: string; relative: number }[];
}

const props = defineProps({
  players: {
    type: Array as () => PlayerStat[],
    required: true,
    default: () => [],
  },
  showRecommendedHandicap: {
    type: Boolean,
    required: false,
    default: false,
  },
});

const { formatNumber } = useFormat();
const { formatRelative, relativeColor, trendMeta } = useDiscGolf();

const hasPlayers = computed(() => props.players.length > 0);
</script>

<template>
  <div class="overflow-x-auto rounded-2xl ring-1 ring-slate-200">
    <table class="w-full min-w-[900px] text-left text-sm">
      <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
        <tr>
          <th class="px-4 py-3">Hráč</th>
          <th class="px-4 py-3 text-center">Odehráno her</th>
          <th class="px-4 py-3 text-center">Nejlepší skóre</th>
          <th class="px-4 py-3 text-center">Průměr skóre</th>
          <th class="px-4 py-3 text-center">Nejhorší skóre</th>
          <th class="px-4 py-3 text-center">Nejlepší +/- par</th>
          <th class="px-4 py-3 text-center">Průměr +/- par</th>
          <th class="px-4 py-3 text-center">Posl. 5 +/- par</th>
          <th class="px-4 py-3 text-center">Posl. 10 +/- par</th>
          <th v-if="showRecommendedHandicap" class="px-4 py-3 text-center">
            <span
              class="inline-flex cursor-help items-center gap-1"
              :title="recommendedHandicapInfo"
            >
              Doporučený handicap
              <InformationCircleIcon class="size-4 text-slate-400" />
            </span>
          </th>
          <th class="px-4 py-3 text-center">Trend</th>
          <th class="px-4 py-3 text-center">Vývoj</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <tr v-for="player in players" :key="player.player_id" class="hover:bg-slate-50">
          <td class="whitespace-nowrap px-4 py-3 font-semibold text-slate-800">
            {{ player.player_name }}
          </td>
          <td class="px-4 py-3 text-center tabular-nums text-slate-600">
            {{ player.games_played }}
          </td>
          <td class="px-4 py-3 text-center font-semibold tabular-nums text-slate-800">
            {{ player.best_score ?? '—' }}
          </td>
          <td class="px-4 py-3 text-center tabular-nums text-slate-600">
            {{ formatNumber(player.avg_score, 1) }}
          </td>
          <td class="px-4 py-3 text-center tabular-nums text-slate-600">
            {{ player.worst_score ?? '—' }}
          </td>
          <td
            class="px-4 py-3 text-center font-semibold tabular-nums"
            :class="relativeColor(player.best_relative)"
          >
            {{ formatRelative(player.best_relative) }}
          </td>
          <td
            class="px-4 py-3 text-center font-semibold tabular-nums"
            :class="relativeColor(player.avg_relative)"
          >
            {{ formatRelative(player.avg_relative) }}
          </td>
          <td
            class="px-4 py-3 text-center tabular-nums"
            :class="relativeColor(player.last5_relative)"
          >
            {{ formatRelative(player.last5_relative) }}
          </td>
          <td
            class="px-4 py-3 text-center tabular-nums"
            :class="relativeColor(player.last10_relative)"
          >
            {{ formatRelative(player.last10_relative) }}
          </td>
          <td
            v-if="showRecommendedHandicap"
            class="px-4 py-3 text-center font-semibold tabular-nums text-indigo-600"
          >
            {{ player.recommended_handicap ?? '—' }}
          </td>
          <td class="px-4 py-3 text-center">
            <span
              class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
              :class="[
                trendMeta(player.trend).text,
                trendMeta(player.trend).bg,
                trendMeta(player.trend).ring,
              ]"
            >
              <span>{{ trendMeta(player.trend).arrow }}</span>
              {{ trendMeta(player.trend).label }}
            </span>
          </td>
          <td class="px-4 py-3">
            <div class="flex justify-center">
              <TrendChart :points="player.chart" sparkline />
            </div>
          </td>
        </tr>
        <tr v-if="!hasPlayers">
          <td
            :colspan="showRecommendedHandicap ? 12 : 11"
            class="px-4 py-10 text-center text-sm italic text-slate-400"
          >
            Zatím žádná data. Dokončete alespoň jednu hru.
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
