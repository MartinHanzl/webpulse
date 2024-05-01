<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid';
import { PencilIcon, EyeIcon, BoltIcon, TrashIcon, EllipsisHorizontalIcon } from '@heroicons/vue/24/outline';

import { defineProps } from 'vue';
defineProps({
  actions: {
    type: Object,
    required: false
  },
})
</script>
<template>
  <div class="w-auto text-right">
    <Menu as="div" class="relative inline-block text-center">
      <div>
        <MenuButton
            class="inline-flex w-full justify-center rounded-md px-4 py-1.5 text-sm font-medium text-white"
        >
          <EllipsisHorizontalIcon class="h-6 w-6 text-blue-600" aria-hidden="true" />
        </MenuButton>
      </div>

      <transition
          enter-active-class="transition duration-100 ease-out"
          enter-from-class="transform scale-95 opacity-0"
          enter-to-class="transform scale-100 opacity-100"
          leave-active-class="transition duration-75 ease-in"
          leave-from-class="transform scale-100 opacity-100"
          leave-to-class="transform scale-95 opacity-0"
      >
        <MenuItems
            class="absolute z-10 right-0 mt-2 w-auto origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
        >
          <div class="px-1 py-1">
            <MenuItem v-if="actions.edit === true" v-slot="{ active }">
              <button
                  :class="[
                  active ? 'bg-indigo-100 text-indigo-500' : 'text-gray-500',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
              >
                <PencilIcon
                    :active="active"
                    :class="[active ? 'mr-2 h-5 w-5 text-white-500' : 'mr-2 h-5 w-5 text-indigo-500']"
                    aria-hidden="true"
                />
                Upravit
              </button>
            </MenuItem>
          </div>
          <div class="px-1 py-1">
            <MenuItem v-if="actions.edit === true" v-slot="{ active }">
              <button
                  :class="[
                  active ? 'bg-green-100 text-green-500' : 'text-gray-500',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
              >
                <EyeIcon
                    :active="active"
                    :class="[active ? 'mr-2 h-5 w-5 text-white-500' : 'mr-2 h-5 w-5 text-green-500']"
                    aria-hidden="true"
                />
                Zobrazit
              </button>
            </MenuItem>
            <MenuItem v-if="actions.quick === true" v-slot="{ active }">
              <button
                  :class="[
                  active ? 'bg-yellow-100 text-yellow-500' : 'text-gray-500',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
              >
                <BoltIcon
                    :active="active"
                    :class="[active ? 'mr-2 h-5 w-5 text-white-500' : 'mr-2 h-5 w-5 text-yellow-500']"
                    aria-hidden="true"
                />
                Rychlý náhled
              </button>
            </MenuItem>
          </div>

          <div class="px-1 py-1">
            <MenuItem v-if="actions.delete === true" v-slot="{ active }">
              <button
                  :class="[
                  active ? 'bg-red-100 text-red-500' : 'text-gray-500',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
              >
                <TrashIcon
                    :active="active"
                    :class="[active ? 'mr-2 h-5 w-5 text-white-500' : 'mr-2 h-5 w-5 text-red-500']"
                    aria-hidden="true"
                />
                Smazat
              </button>
            </MenuItem>
          </div>
        </MenuItems>
      </transition>
    </Menu>
  </div>
</template>
