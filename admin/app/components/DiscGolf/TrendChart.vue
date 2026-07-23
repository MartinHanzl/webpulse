<script setup lang="ts">
import { computed } from 'vue';
import type { ApexOptions } from 'apexcharts';

interface ChartPoint {
  game_id: number;
  date: string;
  relative: number;
}

interface SeriesInput {
  name: string;
  data: ChartPoint[];
}

const props = defineProps({
  /**
   * Either a single player's chart array (ChartPoint[]) via `points`,
   * or multiple series via `series` for the combined multi-player chart.
   */
  points: {
    type: Array as () => ChartPoint[],
    required: false,
    default: () => [],
  },
  series: {
    type: Array as () => SeriesInput[],
    required: false,
    default: () => [],
  },
  /**
   * Sparkline mode: tiny, chromeless inline chart for table rows.
   */
  sparkline: {
    type: Boolean,
    required: false,
    default: false,
  },
  height: {
    type: Number,
    required: false,
    default: 320,
  },
});

const palette = [
  '#4f46e5',
  '#10b981',
  '#f59e0b',
  '#ef4444',
  '#8b5cf6',
  '#0ea5e9',
  '#ec4899',
  '#14b8a6',
  '#f97316',
  '#6366f1',
];

/** Source series (normalize single-points input into a one-series shape). */
const sourceSeries = computed<SeriesInput[]>(() =>
  props.series.length ? props.series : [{ name: '+/- par', data: props.points ?? [] }],
);

function fmtDate(d: string) {
  const [y, m, day] = d.split('-');
  return `${Number(day)}. ${Number(m)}. ${y}`;
}

/**
 * Unified, chronologically ordered list of GAMES across every series
 * (by date, then game id). One category per game — so multiple games on the
 * same day stay as separate points instead of collapsing.
 */
const games = computed(() => {
  const map = new Map<number, string>(); // game_id -> date
  sourceSeries.value.forEach((s) =>
    (s.data ?? []).forEach((p) => {
      if (!map.has(p.game_id)) map.set(p.game_id, p.date);
    }),
  );
  return Array.from(map.entries())
    .map(([id, date]) => ({ id, date }))
    .sort((a, b) => (a.date === b.date ? a.id - b.id : a.date < b.date ? -1 : 1));
});

/** Category labels (date) shown on the x-axis — one per game. */
const categories = computed(() => games.value.map((g) => fmtDate(g.date)));

/**
 * Series aligned to the shared per-game axis: each player gets one value per
 * game (null where they didn't play it). Aligned arrays make the shared
 * tooltip list every player for the hovered game — same as the Growth chart.
 */
const resolvedSeries = computed(() =>
  sourceSeries.value.map((s) => {
    const byGame: Record<number, number> = {};
    (s.data ?? []).forEach((p) => {
      byGame[p.game_id] = p.relative;
    });
    return {
      name: s.name,
      data: games.value.map((g) => (g.id in byGame ? byGame[g.id] : null)),
    };
  }),
);

const hasData = computed(() =>
  resolvedSeries.value.some((s) => s.data.some((v) => v !== null && v !== undefined)),
);

const signed = (val: number) => (val > 0 ? `+${val}` : val === 0 ? 'E' : `${val}`);

const chartOptions = computed<ApexOptions>(() => {
  if (props.sparkline) {
    return {
      chart: {
        type: 'line',
        sparkline: { enabled: true },
        animations: { enabled: false },
      },
      xaxis: { categories: categories.value },
      stroke: { curve: 'straight', width: 2 },
      colors: [palette[0]],
      tooltip: {
        x: { show: false },
        y: {
          formatter: (val: number) => signed(val),
          title: { formatter: () => '' },
        },
      },
      // size 3 so a player with a single game still shows a visible point
      markers: { size: 3 },
    };
  }

  return {
    chart: {
      type: 'line',
      height: props.height,
      toolbar: { show: false },
      zoom: { enabled: false },
    },
    colors: palette,
    stroke: { curve: 'straight', width: 2.5 },
    markers: { size: 5, hover: { size: 7 } },
    dataLabels: { enabled: false },
    legend: { show: resolvedSeries.value.length > 1, position: 'top' },
    grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
    xaxis: {
      categories: categories.value,
      labels: { style: { colors: '#94a3b8' } },
      tooltip: { enabled: false },
    },
    yaxis: {
      reversed: true,
      labels: {
        style: { colors: '#94a3b8' },
        formatter: (val: number) => signed(Math.round(val)),
      },
    },
    tooltip: {
      shared: true,
      intersect: false,
      y: {
        formatter: (val: number) => (val === null || val === undefined ? '—' : signed(val)),
      },
    },
  };
});
</script>

<template>
  <div>
    <apexchart
      v-if="hasData"
      :type="'line'"
      :height="sparkline ? 40 : height"
      :width="sparkline ? 120 : '100%'"
      :options="chartOptions"
      :series="resolvedSeries"
    />
    <div
      v-else-if="!sparkline"
      class="flex items-center justify-center py-12 text-sm italic text-slate-400"
    >
      Zatím není dostatek dat pro graf.
    </div>
    <span v-else class="text-xs text-slate-300">—</span>
  </div>
</template>
