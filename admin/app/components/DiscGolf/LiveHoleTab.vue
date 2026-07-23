<script setup lang="ts">
import PlayerScoreRow from '~/components/DiscGolf/PlayerScoreRow.vue';

interface HoleRow {
  game_player_id: number;
  player_name: string;
  handicap: number;
  throws: number | null;
  liveTotal: number;
  liveNet: number;
  liveRelative: number;
}

defineProps({
  hole: {
    type: Object as () => { number: number; par: number; meters?: number | null },
    required: true,
  },
  rows: {
    type: Array as () => HoleRow[],
    required: true,
    default: () => [],
  },
});

const emit = defineEmits<{
  (e: 'update', payload: { game_player_id: number; throws: number | null }): void;
}>();
</script>

<template>
  <div class="space-y-4">
    <div
      class="flex items-center justify-between rounded-2xl bg-indigo-600 px-5 py-4 text-white shadow-sm"
    >
      <div>
        <div class="text-xs font-semibold uppercase tracking-widest text-indigo-200">
          Jamka {{ hole.number }}
        </div>
        <div class="text-2xl font-extrabold">Par {{ hole.par }}</div>
      </div>
      <div v-if="hole.meters" class="text-right text-sm text-indigo-100">{{ hole.meters }} m</div>
    </div>

    <div class="space-y-3">
      <PlayerScoreRow
        v-for="row in rows"
        :key="row.game_player_id"
        :player-name="row.player_name"
        :handicap="row.handicap"
        :throws="row.throws"
        :hole-par="hole.par"
        :live-total="row.liveTotal"
        :live-net="row.liveNet"
        :live-relative="row.liveRelative"
        @update="(t) => emit('update', { game_player_id: row.game_player_id, throws: t })"
      />
    </div>
  </div>
</template>
