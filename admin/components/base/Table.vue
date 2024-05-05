<script setup lang="ts">
import {defineProps, watch, defineEmits} from 'vue';
import Pagination from "~/components/base/Pagination.vue";
import {PencilIcon, TrashIcon, BoltIcon, ArrowDownIcon, ArrowUpIcon} from "@heroicons/vue/24/outline";
import Slideover from "~/components/layout/Slideover.vue";
import StatusIcon from "~/components/props/StatusIcon.vue";

const order = defineModel('order', {
  type: Object,
  default: {
    orderBy: 'id',
    orderWay: 'desc',
  }
});

const props = defineProps({
  columns: {
    type: Array,
    required: true
  },
  items: {
    type: Object,
    required: true
  },
  detailUrl: {
    type: String,
    required: false
  },
  actions: {
    type: Object,
    required: true
  },
  pending: {
    type: Boolean,
    required: true
  },
  titles: {
    type: Object,
    required: true
  },
  pagination: {
    type: Object,
    required: true
  },
  slideover: {
    type: Object,
    required: false
  }
});

const page = ref(1);
const slideoverIsOpened = ref(false);
const slideOverData = ref({
  title: '',
  columns: [],
  api: '',
  detailUrl: ''
});

function defineSlideoverData(itemId: number) {
  slideOverData.value.title = props.slideover.title;
  slideOverData.value.columns = props.slideover.columns;
  slideOverData.value.api = props.slideover.api + itemId;
  slideOverData.detailUrl = props.detailUrl;
  slideoverIsOpened.value = true;
}

function changeOrder(column: { sortable: any; key: any; }) {
  if (column.sortable) {
    if (order.value.orderBy === column.key) {
      order.value.orderWay = order.value.orderWay === 'asc' ? 'desc' : 'asc';
    } else {
      order.value.orderBy = column.key;
      order.value.orderWay = 'asc';
    }
  }
}
const emit = defineEmits(['update-page', 'update-order']);
watch(page, () => {
  emit('update-page', page.value);
});
watch(order.value, () => {
  emit('update-order', order.value);
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
                  :class="[column.sortable ? 'cursor-pointer' : '', column.mobile === false ? 'hidden' : '', `w-[${column.width}/${columns.length + 1}], py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8 md:table-cell`]"
                  @click="changeOrder(column)"
                >
                  {{ column.name }}
                  <span v-if="order && column.sortable">
                    <ArrowUpIcon
                      v-if="order.orderBy === column.key && order.orderWay === 'asc'"
                      class="w-4 h-4 inline-block ml-1 text-blue-400"
                    />
                    <ArrowDownIcon
                      v-else-if="order.orderBy === column.key && order.orderWay === 'desc'"
                      class="w-4 h-4 inline-block ml-1 text-blue-400"
                    />
                  </span>
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
                  :class="[column.mobile === false ? 'hidden' : '', `whitespace-nowrap w-[${column.width}/${columns.length + 1}] py-3 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8 md:table-cell`]"
                >
                  <div v-if="column.type === 'text' || column.type === 'number'">
                    {{ item[column.key] }}
                  </div>
                  <div v-if="column.type === 'date'">
                    {{ item[column.key] !== null ? new Date(item[column.key]).toLocaleDateString() : '' }}
                  </div>
                  <div v-if="column.type === 'status'">
                    <StatusIcon
                      :status="item[column.key]"
                      :proportions="'w-5 h-5'"
                    />
                  </div>
                </td>
                <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8 flex flex-1 justify-evenly">
                  <NuxtLink
                    v-if="actions.edit"
                    :to="`${detailUrl}${item.id}`"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    <span class="sr-only">Edit</span>
                    <PencilIcon
                      class="h-5 w-5"
                      aria-hidden="true"
                    />
                  </NuxtLink>
                  <button
                    v-if="actions.view"
                    class="text-yellow-600 hover:text-yellow-900"
                    @click="defineSlideoverData(item.id)"
                  >
                    <span class="sr-only">Quick view</span>
                    <BoltIcon
                      class="h-5 w-5"
                      aria-hidden="true"
                    />
                  </button>
                  <NuxtLink
                    v-if="actions.delete"
                    to="/"
                    class="text-red-600 hover:text-red-900"
                  >
                    <span class="sr-only">Delete</span>
                    <TrashIcon
                      class="h-5 w-5"
                      aria-hidden="true"
                    />
                  </NuxtLink>
                </td>
              </tr>
            </tbody>
            <tbody
              v-else
              class="divide-y divide-gray-200 bg-white"
            >
              <tr>
                <td
                  :colspan="columns.length + 1"
                  class="py-4 text-center text-gray-400 text-sm"
                >
                  <span class="animate-pulse">
                    {{ titles.plural }} se načítají ...
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <Pagination
      v-if="pagination && !pending"
      v-model:page="page"
      :pagination="pagination"
      :items="items"
    />
    <Slideover
      v-if="slideover"
      v-model:open="slideoverIsOpened"
      :title="slideOverData.title"
      :columns="slideOverData.columns"
      :api="slideOverData.api"
      :detail-url="detailUrl"
    />
  </div>
</template>