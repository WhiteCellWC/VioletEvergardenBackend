<script setup>
// Components
import DropdownSelect from './DropdownSelect.vue';
import DropdownSelectMenu from './DropdownSelectMenu.vue';
import DropdownSearch from './DropdownSearch.vue';

import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';

// Props
const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    modelLabel: { type: [String, Number], default: '' },
    fetchRoute: { type: String, default: '' },
    options: { type: Array, default: () => [] },
    allowSearch: { type: Boolean, default: false },
    allowClear: { type: Boolean, default: false },
    valueKey: { type: String, default: '' },
    labelKey: { type: String, default: '' },
    placeholder: { type: String, default: '' },
    background: { type: [String, Array], default: () => ['bg-gray-50', 'dark:bg-[var(--dark-bg-150)]'] },
    border: { type: [String, Array], default: () => ['border', 'border-gray-300', 'dark:border-[var(--dark-bg-50)]', 'focus:border-[var(--primary-50)]', 'rounded-md'] },
    padding: { type: [String, Array], default: () => ['p-2'] },
    position: { type: [String, Array], default: () => ['relative'] },
    transition: { type: [String, Array], default: () => ['transition'] },
    focus: { type: [String, Array], default: () => ['focus:outline-none'] },
    extraClass: { type: [String, Array], default: () => [] },
    openUpwards: { type: Boolean, default: false }
});

// Refs
const dropdownId = crypto.randomUUID();
const dropdownOpen = ref(false);
const allOptions = ref([...props.options]);
const searchTerm = ref('');
const lastPage = ref(1);
const nextPage = ref(1);
const currentFetchToken = ref(0);
const label = ref('');

// Emits
const emit = defineEmits(['update:modelValue', 'update:modelLabel']);

// Computed
const filteredOptions = computed(() => {
    if (!searchTerm.value) return allOptions.value;
    return allOptions.value.filter(option =>
        option[props.labelKey]?.toString().toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});

const showLoadMore = computed(() => allOptions.value.length > 0 && nextPage.value <= lastPage.value);

// Functions
const toggleDropdown = () => dropdownOpen.value = !dropdownOpen.value;

const optionOnClick = (option) => {
    const newLabel = allOptions.value.find(o => o[props.valueKey] == option)?.[props.labelKey];
    if (props.modelLabel) {
        emit('update:modelLabel', newLabel);
    } else {
        label.value = newLabel
    }
    emit('update:modelValue', option);
}

const clear = () => {
    emit('update:modelValue', '');
    if (props.modelLabel) {
        emit('update:modelLabel', '');
    } else {
        label.value = ''
    }
}

const fetchData = async (reset = false) => {
    const token = ++currentFetchToken.value;
    const page = reset ? 1 : nextPage.value;

    const params = { page };
    if (searchTerm.value && props.labelKey) params[props.labelKey] = searchTerm.value;

    try {
        const response = await axios.get(props.fetchRoute, { params });
        if (token !== currentFetchToken.value) return;

        const data = response.data.data ?? response.data;
        lastPage.value = response.data.meta?.last_page ?? 1;

        if (reset) {
            allOptions.value = data;
            nextPage.value = 2;
        } else {
            allOptions.value = [...allOptions.value, ...data];
            nextPage.value++;
        }
    } catch (error) {
        console.error('Dropdown fetch error:', error);
    }
}

const handleClickOutside = (e) => {
    const dropdownEl = document.getElementById(dropdownId);
    if (!dropdownEl) return;

    const isOutside = !dropdownEl.contains(e.target);
    const isCloseDropdown = e.target.closest('[data-close-dropdown]');
    const isInsideSearchDrop = e.target.closest('[data-loadmore]');

    if (isOutside || (isCloseDropdown && !isInsideSearchDrop)) {
        dropdownOpen.value = false;

        if (props.fetchRoute && searchTerm.value) {
            searchTerm.value = '';
            nextPage.value = 1;
            fetchData(true);
        }
        if (searchTerm.value) searchTerm.value = '';
    }
};

// Debounced search handler
const handleSearch = debounce((term) => {
    searchTerm.value = term;
    if (!props.fetchRoute) return;
    nextPage.value = 1;
    fetchData(true);
}, 300);

// Lifecycle
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    if (props.fetchRoute) fetchData();
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div :id="dropdownId"
        :class="[...background, ...border, ...padding, ...position, ...transition, ...focus, ...extraClass]">

        <!-- Dropdown toggle -->
        <div class="flex items-center justify-between cursor-pointer" @click="toggleDropdown">
            <slot name="select">
                <DropdownSelect :placeholder="placeholder" :selectedValue="modelLabel || label" />
            </slot>
            <svg class="w-4 h-4 ml-2 transition-transform duration-200"
                :class="dropdownOpen ? 'rotate-180' : 'rotate-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>

        <!-- Dropdown menu -->
        <transition enter-active-class="transition transform duration-200 ease-out"
            enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100"
            leave-active-class="transition transform duration-150 ease-in" leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95">
            <div v-if="dropdownOpen" class="absolute z-10" :class="[
                openUpwards ? 'bottom-full mb-2' : 'top-full mt-2',
                ...background,
                ...border
            ]">

                <!-- Search input -->
                <DropdownSearch v-if="allowSearch" :modelValue="searchTerm" @update:modelValue="handleSearch"
                    placeholder="Search" />

                <!-- Options -->
                <div class="max-h-[20svh] overflow-auto custom-scroll" data-close-dropdown>
                    <slot name="selectMenu">
                        <DropdownSelectMenu :options="props.fetchRoute ? allOptions : filteredOptions"
                            :valueKey="props.valueKey" :labelKey="props.labelKey" :modelValue="props.modelValue"
                            @update:modelValue="optionOnClick" :loadMore="props.fetchRoute ? showLoadMore : false"
                            @loadMore="() => props.fetchRoute && fetchData()" />
                    </slot>
                </div>
            </div>
        </transition>

        <!-- Clear button -->
        <button v-if="allowClear && modelValue" type="button" @click="clear"
            class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 bg-red-600 text-white px-1 text-xs rounded-full cursor-pointer">
            ✕
        </button>
    </div>
</template>
