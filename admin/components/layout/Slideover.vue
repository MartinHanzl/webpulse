<script setup>
import { ref, watch, defineEmits, defineProps } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline'
import {CheckIcon} from "@heroicons/vue/24/solid";
import StatusIcon from "~/components/props/StatusIcon.vue";

const open = defineModel('open', {
  type: Boolean,
  default: false
});

const props = defineProps({
  title: {
    type: String,
    default: '',
    required: true
  },
  columns: {
    type: Array,
    required: true
  },
  api: {
    type: String,
    required: true
  },
  detailUrl: {
    type: String,
    required: true,
    default: ''
  }
});

const item = ref({data:[]});
const pending = ref(false);
async function loadItem() {
  pending.value = true;
  await useFetch(props.api, {
    method: 'GET',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
    },
  }).then((response) => {
    item.value = response.data.value.data;
    pending.value = false;
  })
}
watch(open, () => {
  if (open.value === true) {
    loadItem();
  }
});
</script>

<template>
  <TransitionRoot
    as="template"
    :show="open"
  >
    <Dialog
      class="relative z-50"
      @close="open = false"
    >
      <TransitionChild
        as="template"
        enter="ease-in-out duration-500"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in-out duration-500"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
          <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
            <TransitionChild
              as="template"
              enter="transform transition ease-in-out duration-500 sm:duration-700"
              enter-from="translate-x-full"
              enter-to="translate-x-0"
              leave="transform transition ease-in-out duration-500 sm:duration-700"
              leave-from="translate-x-0"
              leave-to="translate-x-full"
            >
              <DialogPanel class="pointer-events-auto relative w-screen max-w-md">
                <TransitionChild
                  as="template"
                  enter="ease-in-out duration-500"
                  enter-from="opacity-0"
                  enter-to="opacity-100"
                  leave="ease-in-out duration-500"
                  leave-from="opacity-100"
                  leave-to="opacity-0"
                >
                  <div class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4">
                    <button
                      type="button"
                      class="relative rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                      @click="open = false"
                    >
                      <span class="absolute -inset-2.5" />
                      <span class="sr-only">Close panel</span>
                      <XMarkIcon
                        class="h-6 w-6"
                        aria-hidden="true"
                      />
                    </button>
                  </div>
                </TransitionChild>
                <div class="bg-blue-700 px-4 py-8 sm:px-6">
                  <div class="flex items-center justify-between">
                    <DialogTitle class="text-base font-semibold leading-6 text-white">
                      {{ title }}
                    </DialogTitle>
                  </div>
                </div>
                <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl">
                  <div
                    v-if="pending"
                    class="relative mt-6 flex-1 px-4 sm:px-6 text-gray-400 text-sm"
                  >
                    <span class="animate-pulse">
                      {{ title }} se načítá ...
                    </span>
                  </div>
                  <div
                    v-else
                    class="relative flex-1 px-4 sm:px-6"
                  >
                    <div class="mb-6">
                      <NuxtLink 
                        :to="`${detailUrl}${item.id}`"
                        type="button"
                        class="mr-2 inline-flex items-center gap-x-2 rounded-md text-blue-600 ring-1 ring-inset ring-blue-600 px-3.5 py-2.5 text-sm font-medium shadow-sm hover:bg-blue-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                      >
                        <PencilIcon
                          class="-ml-0.5 h-5 w-5"
                          aria-hidden="true"
                        />
                        Upravit
                      </NuxtLink>
                      <button
                        type="button"
                        class="inline-flex items-center gap-x-2 rounded-md text-red-600 ring-1 ring-inset ring-red-600 px-3.5 py-2.5 text-sm font-medium shadow-sm hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                      >
                        <TrashIcon
                          class="-ml-0.5 h-5 w-5"
                          aria-hidden="true"
                        />
                        Odstranit
                      </button>
                    </div>
                    <div
                      v-for="(column, index) in columns"
                      :key="index"
                      class="space-y-2 px-2 sm:grid sm:grid-cols-3 sm:gap-4 sm:space-y-0 sm:py-2 sm:px-0"
                    >
                      <div>
                        <p class="block text-sm font-bold leading-6 text-gray-900 sm:mt-1.5">
                          {{ column.name }}:
                        </p>
                      </div>
                      <div class="sm:col-span-2">
                        <p
                          v-if="column.type === 'date'"
                          class="block w-full py-1.5 text-gray-900 sm:text-sm sm:leading-6"
                        >
                          {{ new Date(item[column.key]).toLocaleString() }}
                        </p>
                        <p
                          v-if="column.type === 'status'"
                          class="block w-full py-1.5 text-gray-900 sm:text-sm sm:leading-6"
                        >
                          <StatusIcon
                            :status="item[column.key]"
                            :proportions="'w-6 h-6'"
                          />
                        </p>
                        <p
                          v-if="column.type === 'text' || column.type === 'number'"
                          class="block w-full py-1.5 text-gray-900 sm:text-sm sm:leading-6"
                        >
                          {{ item[column.key] }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>