<script setup lang="ts">
import axios from 'axios'
import { computed, ref, watch } from 'vue'
import {
    PopoverRoot,
    PopoverTrigger,
    PopoverContent,
    ListboxRoot,
    ListboxContent,
    ListboxGroup,
    ListboxGroupLabel,
    ListboxItem,
    Separator,
} from 'radix-vue'

// TODO highligher vue-highlight-words
// TODO import Bloodhound from 'corejs-typeahead/dist/bloodhound' instead of using Axios
// TODO submitting, keyboard navigation
// TODO Collections
// TODO Articles

type Option = {
    value: string
    url: string
}

type ItemOption = Option & {
    image: string
    author: string
    title: string
}
type AuthorOption = Option & {
    image: string
    name: string
    birthYear: number | null
    deathYear: number | null
}

const searchTerm = ref('')
const itemOptions = ref<ItemOption[]>([])
const authorOptions = ref<AuthorOption[]>([])

function fetchItemOptions(searchTerm: string) {
    axios
        .get('/katalog/suggestions', {
            params: {
                search: searchTerm,
            },
        })
        .then(
            ({ data }) =>
                (itemOptions.value = data.map((item) => ({
                    value: `${item.author}: ${item.title}`,
                    url: route('dielo', { id: item.id }),
                    image: item.image,
                    author: item.author,
                    title: item.title,
                }))),
        )
}

function fetchAuthorOptions(searchTerm: string) {
    axios
        .get('/autori/suggestions', {
            params: {
                search: searchTerm,
            },
        })
        .then(
            ({ data }) =>
                (authorOptions.value = data.map((author) => ({
                    value: author.name,
                    url: `/autor/${author.id}`,
                    image: author.image,
                    name: author.name,
                    birthYear: author.birth_year,
                    deathYear: author.death_year,
                }))),
        )
}

watch(searchTerm, (searchTerm) => {
    fetchItemOptions(searchTerm)
    fetchAuthorOptions(searchTerm)
})

const allOptions = computed(() => {
    return [...authorOptions.value, ...itemOptions.value]
})

watch(allOptions, () => {
    // Do not lose focus to the Listbox
    input.value?.focus()
})

const input = ref<HTMLInputElement>()

function onKeyDown() {
    console.log('onKeyDown')
}
</script>
<template>
    <div class="tw-relative tw-flex tw-items-center tw-bg-red-400">
        <input
            ref="input"
            autocomplete="off"
            v-model="searchTerm"
            class="tw-peer tw-z-10 tw-w-full tw-border tw-border-gray-600 tw-bg-white tw-px-3 tw-py-1.5 tw-text-sm tw-ring-0 placeholder:tw-text-sky-300 focus:tw-outline-none"
            :placeholder="$t('master.search_placeholder')"
            @keydown.down="onKeyDown"
        />
        <i
            class="fa fa-search tw-pointer-events-none tw-absolute tw-right-3 tw-z-20 tw-text-sm"
        ></i>

        <!-- Ukraine -->
        <div
            class="tw-absolute tw-flex tw-h-full tw-w-full tw-flex-col tw-opacity-0 tw-transition-opacity peer-focus:tw-opacity-50"
        >
            <div
                class="tw-h-full tw-shadow-[0px_0px_5px_2px_#0057b7] sm:tw-shadow-[0px_0px_10px_3px_#0057b7]"
            ></div>
            <div
                class="tw-h-full tw-shadow-[0px_0px_5px_2px_#ffd700] sm:tw-shadow-[0px_0px_10px_3px_#ffd700]"
            ></div>
        </div>

        <div class="tw-absolute tw-top-4 tw-w-full">
            <PopoverRoot :open="allOptions.length > 0">
                <!-- Used for measuring with --radis-popover-trigger-width -->
                <PopoverTrigger class="tw-pointer-events-none tw-invisible tw-w-full" />

                <PopoverContent
                    :trapFocus="false"
                    class="tw-z-10 tw-mt-1 tw-w-[--radix-popover-trigger-width] tw-border tw-bg-white tw-shadow-md"
                >
                    <ListboxRoot @entryFocus.prevent highlightOnHover>
                        <ListboxContent>
                            <ListboxGroup v-show="authorOptions.length > 0">
                                <ListboxGroupLabel
                                    class="tw-mt-2.5 tw-px-2.5 tw-text-sm tw-font-thin tw-capitalize"
                                >
                                    {{ $t('authority.authors') }}
                                </ListboxGroupLabel>
                                <template v-for="author in authorOptions" :key="author.value">
                                    <ListboxItem
                                        :value="author.url"
                                        class="tw-flex tw-cursor-pointer tw-space-x-3 tw-px-2.5 tw-py-2 focus:tw-outline-none data-[highlighted]:tw-bg-gray-800 data-[highlighted]:tw-text-white"
                                    >
                                        <img
                                            :src="author.image"
                                            class="tw-h-9 tw-w-9 tw-rounded-full"
                                        />
                                        <div
                                            class="tw-flex tw-flex-col tw-text-xs tw-leading-4 tw-tracking-wider"
                                        >
                                            <span class="tw-italic">
                                                {{ author.name }}
                                            </span>
                                            <span v-if="author.birthYear">
                                                <span v-if="author.deathYear">
                                                    (✴ {{ author.birthYear }} – ✝
                                                    {{ author.deathYear }})
                                                </span>
                                                <span v-else>(✴ {{ author.birthYear }})</span>
                                            </span>
                                        </div>
                                    </ListboxItem>
                                    <Separator
                                        class="tw-z-20 tw-h-px tw-bg-gray-300 last:tw-hidden"
                                    />
                                </template>
                            </ListboxGroup>
                            <ListboxGroup v-show="itemOptions.length > 0">
                                <ListboxGroupLabel
                                    class="tw-mt-2.5 tw-px-2.5 tw-text-sm tw-font-thin tw-capitalize"
                                >
                                    {{ $t('katalog.title') }}
                                </ListboxGroupLabel>
                                <template v-for="item in itemOptions" :key="item.id">
                                    <ListboxItem
                                        class="tw-flex tw-cursor-pointer tw-space-x-3 tw-px-2.5 tw-py-2 focus:tw-outline-none data-[highlighted]:tw-bg-gray-800 data-[highlighted]:tw-text-white"
                                        :value="item.url"
                                    >
                                        <img :src="item.image" class="tw-h-9 tw-w-9" />
                                        <div
                                            class="tw-flex tw-flex-col tw-text-xs tw-leading-4 tw-tracking-wider"
                                        >
                                            <span class="tw-italic">
                                                {{ item.author }}
                                            </span>
                                            <span>{{ item.title }}</span>
                                        </div>
                                    </ListboxItem>
                                    <Separator
                                        class="tw-z-20 tw-h-px tw-bg-gray-300 last:tw-hidden"
                                    />
                                </template>
                            </ListboxGroup>
                        </ListboxContent>
                    </ListboxRoot>
                </PopoverContent>
            </PopoverRoot>
        </div>
    </div>
</template>
