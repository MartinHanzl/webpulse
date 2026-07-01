<script setup lang="ts">
// Decorative line-art fern/palm frond used by the spa demo. Positioned by the
// parent (absolute). Inherits colour via `currentColor`, low opacity by default.
withDefaults(defineProps<{ flip?: boolean }>(), { flip: false });

// Symmetric leaflets along a curved stem.
const leaflets = Array.from({ length: 11 }, (_, i) => i);
</script>

<template>
  <svg
    viewBox="0 0 120 200"
    fill="none"
    class="pointer-events-none select-none"
    :class="flip ? '-scale-x-100' : ''"
  >
    <!-- stem -->
    <path
      d="M60 198 C 60 150, 58 90, 60 6"
      stroke="currentColor"
      stroke-width="1.4"
      stroke-linecap="round"
    />
    <!-- leaflets -->
    <g stroke="currentColor" stroke-width="1.1" stroke-linecap="round">
      <template v-for="i in leaflets" :key="i">
        <path
          :d="`M60 ${190 - i * 16} C ${44 - i} ${182 - i * 16}, ${26 - i} ${176 - i * 16}, ${14} ${168 - i * 16}`"
        />
        <path
          :d="`M60 ${190 - i * 16} C ${76 + i} ${182 - i * 16}, ${94 + i} ${176 - i * 16}, ${106} ${168 - i * 16}`"
        />
      </template>
    </g>
  </svg>
</template>
