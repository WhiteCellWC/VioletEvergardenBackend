<script setup>
// Layout
import AdminLayout from '@/Layouts/AdminLayout.vue';
// Components
import TextInput from '@/Components/Input/TextInput.vue';
import Label from '@/Components/Label/Label.vue';
import ToggleInput from '@/Components/Input/ToggleInput.vue';
import Button from '@/Components/Button/Button.vue';
//Libs
import { route } from 'ziggy-js';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    'letterType': {
        type: Object,
        required: true,
        default: () => ({})
    }
})
// Ref
const form = useForm({
    _method: 'PUT',
    name: props.letterType.data.name,
    status: true
})

// Handlers
const submit = () => {
    form.put(route('letter-types.update', { letter_type: props.letterType.data.id }))
}

const cancel = () => {
    form.reset()
    history.back()
}
</script>

<template>
    <AdminLayout>
        <template #content>
            <div class="px-4 py-2 bg-gray-600">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Letter Types</h2>
                </div>
            </div>

            <form @submit.prevent="submit" class="p-8 flex flex-col w-1/2 gap-6">
                <div class="flex flex-col gap-2">
                    <Label for="name" mandatory>Letter Type</Label>
                    <TextInput id="name" placeholder="Input Name" v-model="form.name" />
                </div>

                <ToggleInput v-model="form.status" label="Status" />

                <div class="flex justify-end gap-4">
                    <Button type="button" background="bg-gray-500 text-white"
                        hover="hover:bg-gray-600 hover:outline-none hover:ring hover:ring-gray-500" @click="cancel">
                        Cancel
                    </Button>
                    <Button :disabled="form.processing">
                        Update
                    </Button>
                </div>
            </form>
        </template>
    </AdminLayout>
</template>
