<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { XMarkIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';

const props = defineProps<{
  show: boolean;
  contactId: number | null;
}>();

const emit = defineEmits<{
  (e: 'update:show', value: boolean): void;
  (e: 'created'): void;
}>();

const { $toast } = useNuxtApp();

const loadingSections = ref(false);
const saving = ref(false);
const sections = ref([] as any[]);
const targetSectionId = ref(null as number | null);

const sectionOptions = computed(() => sections.value.map((s) => ({ value: s.id, name: s.name })));

const form = ref({ title: '', due_date: null as string | null, note: '' });

async function loadSections() {
  loadingSections.value = true;
  const client = useSanctumClient();
  await client('/api/admin/contact/board/section', {
    method: 'GET',
    headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
  })
    .then((r: any) => {
      sections.value = r;
      targetSectionId.value = r?.[0]?.id || null;
    })
    .catch(() => {
      sections.value = [];
      targetSectionId.value = null;
    })
    .finally(() => {
      loadingSections.value = false;
    });
}

watch(
  () => props.show,
  (show) => {
    if (show) {
      form.value = { title: '', due_date: null, note: '' };
      loadSections();
    }
  },
);

function close() {
  emit('update:show', false);
}

async function submit() {
  if (!form.value.title) {
    $toast.show({ summary: 'Chybí údaje', detail: 'Vyplňte nadpis kartičky.', severity: 'error' });
    return;
  }
  if (!targetSectionId.value) {
    $toast.show({
      summary: 'Chybí sekce',
      detail: 'Nejdřív vytvořte sekci na nástěnce kontaktů.',
      severity: 'error',
    });
    return;
  }

  saving.value = true;
  const client = useSanctumClient();
  await client('/api/admin/contact/board/card', {
    method: 'POST',
    body: JSON.stringify({
      title: form.value.title,
      due_date: form.value.due_date,
      note: form.value.note,
      contact_id: props.contactId,
      contact_board_section_id: targetSectionId.value,
      priority: 'critical',
      pin_to_top: true,
    }),
    headers: { Accept: 'application/json', 'Content-Type': 'application/json' },
  })
    .then(() => {
      $toast.show({
        summary: 'Hotovo',
        detail: 'Kartička byla vytvořena na nástěnce.',
        severity: 'success',
      });
      emit('created');
      close();
    })
    .catch(() => {
      $toast.show({
        summary: 'Chyba',
        detail: 'Nepodařilo se vytvořit kartičku.',
        severity: 'error',
      });
    })
    .finally(() => {
      saving.value = false;
    });
}
</script>

<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        @click.self="close"
      >
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
          <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Nová kartička na nástěnce</h3>
            <button
              type="button"
              class="rounded-lg p-1 text-slate-400 hover:bg-slate-100"
              @click="close"
            >
              <XMarkIcon class="size-5" />
            </button>
          </div>

          <div class="space-y-4">
            <BaseFormInput
              v-model="form.title"
              label="Nadpis"
              name="board_card_title"
              rules="required"
            />
            <BaseFormSelect
              v-model="targetSectionId"
              label="Sekce"
              name="board_card_section"
              :options="sectionOptions"
              :disabled="loadingSections"
            />
            <BaseFormInput
              v-model="form.due_date"
              label="Datum splnění"
              type="date"
              name="board_card_due_date"
            />
            <BaseFormTextarea
              v-model="form.note"
              label="Poznámka"
              name="board_card_note"
              rows="3"
            />

            <p class="text-xs text-slate-500">
              Priorita:
              <span class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-700"
                >Kritická</span
              >
              — karta bude v dané sekci nahoře.
            </p>

            <div class="flex justify-end gap-3 pt-2">
              <button
                type="button"
                class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100"
                @click="close"
              >
                Zrušit
              </button>
              <button
                type="button"
                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-500 disabled:opacity-50"
                :disabled="saving"
                @click="submit"
              >
                <CheckCircleIcon class="mr-1 inline size-4" /> Vytvořit
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>
