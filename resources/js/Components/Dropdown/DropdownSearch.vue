<script setup>
import TextInput from '@/Components/Input/TextInput.vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        required: false,
        default: ''
    },
    placeholder: {
        type: String,
        required: false,
        default: ''
    }
})

const emits = defineEmits(['update:modelValue']);

const onInput = (value) => {
    emits('update:modelValue', value)
}
</script>

<template>
    <div class="relative">
        <TextInput :extraClass="['w-full']" :placeholder="placeholder" :modelValue="modelValue" @update:modelValue="onInput" />
        <FontAwesomeIcon :icon="['fas', 'magnifying-glass']" class="absolute end-2 top-1/2 -translate-y-1/2" />
    </div>
</template>
