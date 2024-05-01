<script setup lang="ts">
import { defineProps } from 'vue';
import Dropdown from "~/components/base/Dropdown.vue";
import Pagination from "~/components/base/Pagination.vue";
import {CheckIcon, XMarkIcon} from "@heroicons/vue/20/solid";

defineProps({
  columns: {
    type: Array,
    required: true
  },
  items: {
    type: Object,
    required: true
  },
  actions: {
    type: Object,
    required: false
  },
  pending: {
    type: Boolean,
    required: false
  },
  titles: {
    type: Object,
    required: false
  },
  pagination: {
    type: Object,
    required: false
  }
});
</script>

<template>
  <div class="px-4 sm:px-6 lg:px-8 bg-white rounded drop-shadow-sm">
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle">
          <table
            class="min-w-full divide-y divide-gray-300"
            :columns="columns"
          >
            <thead>
              <tr>
                <th
                  v-for="(column, key) in columns"
                  :key="key"
                  :class="[column.mobile === false ? 'hidden' : '', 'py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8 md:table-cell']"
                >
                  {{ column.name }}
                </th>
                <th
                  scope="col"
                  class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8"
                >
                  <span class="sr-only">Edit</span>
                </th>
              </tr>
            </thead>
            <tbody
              v-if="!pending"
              class="divide-y divide-gray-200 bg-white"
            >
              <tr
                v-for="(item, key) in items.data"
                :key="key"
                class="even:bg-gray-50"
              >
                <td
                  v-for="(column, index) in columns"
                  :key="index"
                  :class="[column.mobile === false ? 'hidden' : '', `whitespace-nowrap w-[${column.width}]/12 py-3 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8 md:table-cell`]"
                >
                  <div v-if="column.type === 'text' || column.type === 'number'">
                    {{ item[column.key] }}
                  </div>
                  <div v-if="column.type === 'date'">
                    {{ item[column.key] !== null ? new Date(item[column.key]).toLocaleDateString() : '' }}
                  </div>
                  <div v-if="column.type === 'status'">
                    <div
                      v-if="item[column.key] === true"
                      class="text-green-600 text-sm"
                    >
                      <CheckIcon class="w-4 h-4" />
                    </div>
                    <div
                      v-else
                      class="text-red-600 text-sm"
                    >
                      <XMarkIcon class="w-4 h-4" />
                    </div>
                  </div>
                </td>
                <!--                <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                  <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                </td>-->
                <td class="relative whitespace-nowrap text-center text-sm font-medium">
                  <Dropdown :actions="actions" />
                </td>
              </tr>
            </tbody>
            <tbody
              v-else
              class="divide-y divide-gray-200 bg-white"
            >
              <div class="py-1.5 text-center">
                {{ titles.plural }} se načítají...
              </div>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <Pagination
      v-if="pagination"
      :pagination="pagination"
    />
  </div>
</template>