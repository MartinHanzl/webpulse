<script setup lang="ts">
import {ref, defineModel, defineProps, watch, defineEmits} from 'vue';
import {Switch} from "@headlessui/vue";

const form = defineModel('form', {
  type: Boolean,
  default: false
});

const props = defineProps({
  rows: {
    type: Object,
    required: false,
    default: () => ({}),
  }
});
</script>

<template>
  <div class="mt-6 space-y-8 bg-white shadow-md rounded-lg p-8">
    <div
      v-for="(rowValue, index) in rows"
      :key="index"
      class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-3"
    >
      <div
        v-for="(rowSubValue, key) in rowValue.row"
        :key="key"
      >
        <div
          v-if="rowSubValue.type !== 'switch'"
          :class="`sm:col-span-${rowSubValue.span}`"
        >
          <label
            :for="rowSubValue.name"
            class="block text-sm font-medium leading-6 text-gray-900"
          >{{ rowSubValue.label }}</label>
          <div class="mt-2">
            <input 
              v-if="rowSubValue.type === 'text'"
              :id="rowSubValue.name"
              v-model="form[rowSubValue.name]"
              :type="rowSubValue.type"
              :name="rowSubValue.name"
              autocomplete="none"
              :minlength="rowSubValue.minlength ?? 0"
              :maxlength="rowSubValue.maxlength ?? 255"
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
            >
            <input
              v-else-if="rowSubValue.type === 'number'"
              :id="rowSubValue.name"
              v-model="form[rowSubValue.name]"
              :type="rowSubValue.type"
              :name="rowSubValue.name"
              autocomplete="none"
              :min="rowSubValue.min ?? 0"
              :max="rowSubValue.max ?? 999999999"
              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
            >
          </div>
        </div>
        <div
          v-else
          :class="`sm:col-span-${rowSubValue.span}`"
        >
          <label
            for="postal-code"
            class="block text-sm font-medium leading-6 text-gray-900"
          >{{ rowSubValue.label }}</label>
          <div class="mt-2">
            <Switch
              v-model="form[rowSubValue.name]"
              :class="[form[rowSubValue.name] ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']"
            >
              <span class="sr-only">Use setting</span>
              <span :class="[form[rowSubValue.name] ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']">
                <span
                  :class="[form[rowSubValue.name] ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']"
                  aria-hidden="true"
                >
                  <svg
                    class="h-3 w-3 text-gray-400"
                    fill="none"
                    viewBox="0 0 12 12"
                  >
                    <path
                      d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                      stroke="currentColor"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                </span>
                <span
                  :class="[form[rowSubValue.name] ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out', 'absolute inset-0 flex h-full w-full items-center justify-center transition-opacity']"
                  aria-hidden="true"
                >
                  <svg
                    class="h-3 w-3 text-blue-600"
                    fill="currentColor"
                    viewBox="0 0 12 12"
                  >
                    <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                  </svg>
                </span>
              </span>
            </Switch>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>