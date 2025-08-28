<script setup lang="ts">
import { ref, computed } from "vue"
import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
  ComboboxButton,
} from "@headlessui/vue"
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid"

// ডেমো ডাটা
const people = [
  { id: 1, name: "Ashraf" },
  { id: 2, name: "Rakib" },
  { id: 3, name: "Sumi" },
  { id: 4, name: "Hasan" },
]

const selectedPerson = ref(people[0]) // ডিফল্ট সিলেকশন
const query = ref("") // সার্চ টেক্সট

// সার্চ ফিল্টার
const filteredPeople = computed(() =>
  query.value === ""
    ? people
    : people.filter((person) =>
        person.name.toLowerCase().includes(query.value.toLowerCase())
      )
)
</script>

<template>
  
    <Combobox v-model="selectedPerson">
      <div class="relative">
        <!-- Input Box (Search সহ) -->
        <div class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md">
          <ComboboxInput
            class="w-full border-none py-2 pl-3 pr-10 leading-5 text-gray-900 focus:ring-0"
            :displayValue="(person) => person?.name"
            @input="query = $event.target.value" 
          />
          <ComboboxButton class="absolute inset-y-0 right-0 flex items-center pr-2">
            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" />
          </ComboboxButton>
        </div>

        <!-- Dropdown Options -->
        <ComboboxOptions
          class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
        >
          <template v-if="filteredPeople.length === 0 && query !== ''">
            <div class="cursor-default select-none px-4 py-2 text-gray-700">
              কিছু পাওয়া যায়নি।
            </div>
          </template>

          <ComboboxOption
            v-for="person in filteredPeople"
            :key="person.id"
            :value="person"
            v-slot="{ selected, active }"
          >
            <li
              class="relative cursor-default select-none py-2 pl-10 pr-4"
              :class="{
                'bg-indigo-600 text-white': active,
                'text-gray-900': !active
              }"
            >
              <span
                class="block truncate"
                :class="{ 'font-medium': selected, 'font-normal': !selected }"
              >
                {{ person.name }}
              </span>
              <span
                v-if="selected"
                class="absolute inset-y-0 left-0 flex items-center pl-3"
              >
                <CheckIcon class="h-5 w-5" />
              </span>
            </li>
          </ComboboxOption>
        </ComboboxOptions>
      </div>
    </Combobox>

</template>
