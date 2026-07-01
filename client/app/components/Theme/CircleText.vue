<script setup lang="ts">
import { useId } from 'vue';

// Text wrapped around a circle (SVG textPath) that slowly rotates. Used for the
// spa demo's decorative badges. Put content (an icon/image) in the default slot.
withDefaults(defineProps<{ text: string; duration?: number }>(), { duration: 18 });

const pathId = `circletext-${useId()}`;
</script>

<template>
  <div class="relative flex items-center justify-center">
    <svg
      viewBox="0 0 100 100"
      class="size-full"
      :style="{ animation: `spin ${duration}s linear infinite` }"
    >
      <defs>
        <path :id="pathId" d="M 50,50 m -37,0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="none" />
      </defs>
      <text class="fill-current text-[7px] font-semibold uppercase tracking-[0.18em]">
        <textPath :href="`#${pathId}`" startOffset="0%">{{ text }}</textPath>
      </text>
    </svg>
    <div class="absolute inset-0 flex items-center justify-center">
      <slot />
    </div>
  </div>
</template>
