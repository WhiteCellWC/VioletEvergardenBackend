<script setup>
// Props
const props = defineProps({
    selectedValue: {
        type: [String, Number],
        required: false,
        default: ''
    },
    placeholder: {
        type: String,
        required: false,
        default: ''
    }
});
</script>

<template>
    <div v-if="selectedValue">
        {{ selectedValue }}
    </div>
    <div v-else class="opacity-50">
        {{ placeholder }}
    </div>
</template>
