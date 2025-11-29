<script setup>
// Props
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    minHeight: {
        type: String,
        default: 'min-h-[300px]',
    },
    padding: {
        type: String,
        default: 'p-6',
    },
    extraClass: {
        type: [String, Array],
        default: () => [],
    },
});
</script>

<template>
    <div :class="['bg-white dark:bg-[var(--dark-bg-100)] rounded-xl shadow-sm transition duration-150 ease-in-out', padding, extraClass]">
        <div v-if="title" class="mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ title }}</h3>
            <p v-if="subtitle" class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ subtitle }}</p>
        </div>
        <div :class="['flex items-center justify-center', minHeight]">
            <slot />
        </div>
    </div>
</template>
