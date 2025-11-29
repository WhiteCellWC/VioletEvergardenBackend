<script setup>
// Props
const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
    },
    icon: {
        type: Array,
        required: true,
    },
    trend: {
        type: Object,
        default: null, // { value: Number, isPositive: Boolean }
    },
    iconColor: {
        type: String,
        default: 'text-white',
    },
    iconBgColor: {
        type: String,
        default: 'bg-[var(--primary-50)]',
    },
});
</script>

<template>
    <div class="bg-white dark:bg-[var(--dark-bg-100)] rounded-xl shadow-sm p-6 transition duration-150 ease-in-out hover:shadow-md">
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ title }}</span>
                <span class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ value }}</span>
                <div v-if="trend" class="flex items-center mt-1" :class="trend.isPositive ? 'text-green-500' : 'text-red-500'">
                    <FontAwesomeIcon :icon="trend.isPositive ? ['fas', 'sort-up'] : ['fas', 'sort-down']" class="mr-1"/>
                    <span class="text-sm font-medium">{{ trend.value }}%</span>
                </div>
            </div>
            <div :class="[iconBgColor, 'rounded-full p-4']">
                <FontAwesomeIcon :icon="icon" :class="[iconColor, 'text-2xl']" />
            </div>
        </div>
    </div>
</template>
