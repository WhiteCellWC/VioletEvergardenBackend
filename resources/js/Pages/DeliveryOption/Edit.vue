<script setup>
// Layout
import AdminLayout from '@/Layouts/AdminLayout.vue';
// Components
import TextInput from '@/Components/Input/TextInput.vue';
import NumberInput from '@/Components/Input/NumberInput.vue';
import Label from '@/Components/Label/Label.vue';
import ToggleInput from '@/Components/Input/ToggleInput.vue';
import Button from '@/Components/Button/Button.vue';
//Libs
import { route } from 'ziggy-js';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    'deliveryOption': {
        type: Object,
        required: true,
        default: () => ({})
    }
})
// Ref
const form = useForm({
    _method: 'PUT',
    name: props.deliveryOption.data.name,
    base_cost: props.deliveryOption.data.base_cost,
    delivery_type: props.deliveryOption.data.delivery_type,
    estimated_days: props.deliveryOption.data.estimated_days,
    is_weight_based: props.deliveryOption.data.is_weight_based,
    has_tracking: props.deliveryOption.data.has_tracking,
    status: props.deliveryOption.data.status
})

// Handlers
const submit = () => {
    form.post(route('delivery-options.update', { delivery_option: props.deliveryOption.data.id }))
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
                    <h2 class="text-xl font-bold">Delivery Options</h2>
                </div>
            </div>

            <form @submit.prevent="submit" class="p-8 flex flex-col w-1/2 gap-6">
                <div class="flex flex-col gap-2">
                    <Label for="name" mandatory>Delivery Option</Label>
                    <TextInput id="name" placeholder="Input Name" v-model="form.name" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="base_cost" mandatory>Base Cost</Label>
                    <NumberInput id="base_cost" placeholder="Input Price" v-model="form.base_cost" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="delivery_type">Delivery Type</Label>
                    <TextInput id="delivery_type" placeholder="Input Type" v-model="form.delivery_type" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="estimated_days" mandatory>Estimated Days</Label>
                    <NumberInput id="estimated_days" placeholder="Input Day" v-model="form.estimated_days" />
                </div>

                <ToggleInput v-model="form.is_weight_based" label="Weight Based" />

                <ToggleInput v-model="form.has_tracking" label="Include Tracking" />

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
