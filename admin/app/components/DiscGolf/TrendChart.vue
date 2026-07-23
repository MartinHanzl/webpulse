<script setup lang="ts">
import { computed } from 'vue';
import type { ApexOptions } from 'apexcharts';

interface ChartPoint {
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

const resolvedSeries = computed(() => {
  if (props.series.length) {
    return props.series.map((s) => ({
      name: s.name,
      data: (s.data ?? []).map((p) => ({ x: p.date, y: p.relative })),
    }));
  }
  return [
    {
      name: '+/- par',
      data: (props.points ?? []).map((p) => ({ x: p.date, y: p.relative })),
    },
  ];
});

const hasData = computed(() => resolvedSeries.value.some((s) => s.data.length > 0));

const chartOptions = computed<ApexOptions>(() => {
  if (props.sparkline) {
    return {
      chart: {
        type: 'line',
        sparkline: { enabled: true },
        animations: { enabled: false },
      },
      stroke: { curve: 'straight', width: 2 },
      colors: [palette[0]],
      tooltip: {
        x: { show: false },
        y: {
          formatter: (val: number) => (val > 0 ? `+${val}` : val === 0 ? 'E' : `${val}`),
          title: { formatter: () => '' },
        },
      },
      markers: { size: 0 },
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
    markers: { size: 4, hover: { size: 6 } },
    dataLabels: { enabled: false },
    legend: { show: props.series.length > 1, position: 'top' },
    grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
    xaxis: {
      type: 'datetime',
      labels: { datetimeUTC: false, style: { colors: '#94a3b8' } },
    },
    yaxis: {
      reversed: true,
      labels: {
        style: { colors: '#94a3b8' },
        formatter: (val: number) => {
          const r = Math.round(val);
          return r > 0 ? `+${r}` : r === 0 ? 'E' : `${r}`;
        },
      },
    },
    tooltip: {
      x: { format: 'dd. MM. yyyy' },
      y: {
        formatter: (val: number) => (val > 0 ? `+${val}` : val === 0 ? 'E' : `${val}`),
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
