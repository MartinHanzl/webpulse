<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
  color: {
    type: String,
    required: false,
    default: 'slate',
  },
});

// Mapování barev na Tailwind třídy (zaručuje, že Tailwind třídy správně vyexportuje)
const colorMap: Record<string, string> = {
  red: 'bg-red-50 text-red-700 ring-red-600/20',
  orange: 'bg-orange-50 text-orange-700 ring-orange-600/20',
  amber: 'bg-amber-50 text-amber-700 ring-amber-600/20',
  yellow: 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
  lime: 'bg-lime-50 text-lime-700 ring-lime-600/20',
  green: 'bg-green-50 text-green-700 ring-green-600/20',
  emerald: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
  teal: 'bg-teal-50 text-teal-700 ring-teal-600/20',
  cyan: 'bg-cyan-50 text-cyan-700 ring-cyan-600/20',
  sky: 'bg-sky-50 text-sky-700 ring-sky-600/20',
  blue: 'bg-blue-50 text-blue-700 ring-blue-600/20',
  indigo: 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
  violet: 'bg-violet-50 text-violet-700 ring-violet-600/20',
  purple: 'bg-purple-50 text-purple-700 ring-purple-600/20',
  fuchsia: 'bg-fuchsia-50 text-fuchsia-700 ring-fuchsia-600/20',
  pink: 'bg-pink-50 text-pink-700 ring-pink-600/20',
  rose: 'bg-rose-50 text-rose-700 ring-rose-600/20',
  slate: 'bg-slate-50 text-slate-700 ring-slate-600/20',
  gray: 'bg-gray-50 text-gray-700 ring-gray-600/20',
  zinc: 'bg-zinc-50 text-zinc-700 ring-zinc-600/20',
  stone: 'bg-stone-50 text-stone-700 ring-stone-600/20',
  neutral: 'bg-neutral-50 text-neutral-700 ring-neutral-600/20',
};

// Barevná pole v DB byla dřív ukládána jako hex (starý default #6366f1 z formulářů
// před zavedením BaseFormColorPicker) — pro takovou hodnotu vykreslíme inline styl
// místo tichého fallbacku na šedou, aby štítek skutečně měl uloženou barvu.
const isHexColor = computed(() => /^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(props.color || ''));

const badgeClass = computed(() => {
  const normalizedColor = (props.color || '').trim().toLowerCase();
  const selectedColor = colorMap[normalizedColor] || (isHexColor.value ? '' : colorMap.slate);
  return `inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold ring-1 ring-inset ${selectedColor}`;
});

const badgeStyle = computed(() => {
  if (!isHexColor.value) return {};
  return {
    backgroundColor: `${props.color}1a`,
    color: props.color,
    boxShadow: `inset 0 0 0 1px ${props.color}33`,
  };
});
</script>

<template>
  <span :class="badgeClass" :style="badgeStyle">
    <slot />
  </span>
</template>
