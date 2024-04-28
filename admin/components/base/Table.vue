<template>
  <div class="px-4 sm:px-6 lg:px-8 bg-white rounded drop-shadow-sm">
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
            <tr>
              <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6 lg:pl-8">Název</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Title</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
              <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
              <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 lg:pr-8">
                <span class="sr-only">Edit</span>
              </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-for="person in people" :key="person.email">
              <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">{{ person.name }}</td>
              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ person.title }}</td>
              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ person.email }}</td>
              <td class="whitespace-nowrap px-3 py-5 text-sm text-gray-500">
                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
              </td>
              <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6 lg:pr-8">
<!--                <EllipsisHorizontalIcon class="text-blue-600 hover:text-blue-900 cursor-pointer" />-->
                <Dropdown />
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <Pagination />
  </div>
</template>

<script setup>
import {PencilIcon, BoltIcon, TrashIcon, EllipsisHorizontalIcon} from "@heroicons/vue/24/outline";
import Dropdown from "~/components/base/Dropdown.vue";
import Pagination from "~/components/base/Pagination.vue";
const people = [
  { name: 'Lindsay Walton', title: 'Front-end Developer', email: 'lindsay.walton@example.com', role: 'Member' },
]
</script>

<script>
export default {
  props: {
    cells: {
      type: Array,
      required: false
    }
  }
}
</script>
