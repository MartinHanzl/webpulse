<script setup lang="ts">
import { ref, onMounted } from 'vue';
import draggable from 'vuedraggable';
import { Bars3Icon, ArrowPathIcon } from '@heroicons/vue/24/outline';

import { menuSections, type MenuSectionDef } from '~/components/Layout/navigation';
import {
  applyMenuOrder,
  menuOrderRowsFromSections,
  useMenuOrder,
} from '~/composables/useMenuOrder';

const { $toast } = useNuxtApp();
const { load, save, reset } = useMenuOrder();

const localSections = ref<MenuSectionDef[]>(applyMenuOrder(menuSections, []));
const loading = ref(false);
const saving = ref(false);

async function loadOrder() {
  loading.value = true;
  try {
    const rows = await load();
    localSections.value = applyMenuOrder(menuSections, rows);
  } finally {
    loading.value = false;
  }
}

async function saveOrder() {
  saving.value = true;
  try {
    const rows = menuOrderRowsFromSections(localSections.value);
    await save(rows);
    $toast.show({
      summary: 'Hotovo',
      detail: 'Pořadí menu bylo uloženo.',
      severity: 'success',
    });
  } catch {
    $toast.show({
      summary: 'Chyba',
      detail: 'Nepodařilo se uložit pořadí menu.',
      severity: 'error',
    });
  } finally {
    saving.value = false;
  }
}

async function resetOrder() {
  try {
    await reset();
    localSections.value = applyMenuOrder(menuSections, []);
    $toast.show({
      summary: 'Hotovo',
      detail: 'Pořadí menu bylo obnoveno na výchozí.',
      severity: 'success',
    });
  } catch {
    $toast.show({
      summary: 'Chyba',
      detail: 'Nepodařilo se obnovit výchozí pořadí menu.',
      severity: 'error',
    });
  }
}

onMounted(loadOrder);
</script>

<template>
  <LayoutContainer>
    <div class="mb-6 flex items-center justify-between gap-4">
      <div>
        <LayoutTitle class="!mb-0">Pořadí menu</LayoutTitle>
        <p class="mt-1 text-sm text-slate-500">
          Přetažením seřaďte sekce a odkazy v levém menu. Odkaz nelze přesunout do jiné sekce.
          Pořadí je uloženo jen pro váš účet a aktuálně vybraný web.
        </p>
      </div>
      <BaseButton variant="secondary" size="md" :disabled="loading" @click="resetOrder">
        <ArrowPathIcon class="mr-1.5 size-4" />
        Obnovit výchozí
      </BaseButton>
    </div>

    <draggable
      v-model="localSections"
      item-key="key"
      handle=".drag-handle-section"
      class="space-y-3"
      :animation="150"
    >
      <template #item="{ element: section }">
        <div class="rounded-xl border border-slate-100 bg-white">
          <div class="flex items-center gap-2 border-b border-slate-100 px-3 py-2.5">
            <button
              type="button"
              class="drag-handle-section cursor-grab text-slate-400 hover:text-slate-600 active:cursor-grabbing"
              aria-label="Přesunout sekci"
            >
              <Bars3Icon class="size-4" />
            </button>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-500">{{
              section.title
            }}</span>
          </div>

          <draggable
            v-model="section.menu"
            item-key="key"
            handle=".drag-handle-item"
            class="space-y-1.5 p-2.5"
            :animation="150"
          >
            <template #item="{ element: item }">
              <div
                class="flex items-center gap-3 rounded-lg px-2.5 py-2 ring-1 ring-transparent hover:bg-slate-50 hover:ring-slate-200"
              >
                <button
                  type="button"
                  class="drag-handle-item cursor-grab text-slate-400 hover:text-slate-600 active:cursor-grabbing"
                  aria-label="Přesunout odkaz"
                >
                  <Bars3Icon class="size-4" />
                </button>
                <component :is="item.icon" class="size-4 text-slate-400" />
                <span class="text-sm text-slate-700">{{ item.name }}</span>
              </div>
            </template>
          </draggable>
        </div>
      </template>
    </draggable>

    <div class="mt-6 flex justify-end border-t border-slate-100 pt-5">
      <BaseButton variant="primary" size="md" :disabled="saving || loading" @click="saveOrder">
        Uložit pořadí
      </BaseButton>
    </div>
  </LayoutContainer>
</template>
