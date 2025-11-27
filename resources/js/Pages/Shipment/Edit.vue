<script setup>
// Layout
import AdminLayout from '@/Layouts/AdminLayout.vue';
// Components
import Label from '@/Components/Label/Label.vue';
import ToggleInput from '@/Components/Input/ToggleInput.vue';
import Button from '@/Components/Button/Button.vue';
// Libs
import { route } from 'ziggy-js';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    'shipment': {
        type: Object,
        required: true,
    }
})

// Form
const form = useForm({
    _method: 'PUT',
    recipients: props.shipment.data.recipients.map(recipient => ({
        recipient_id: recipient.id,
        recipient_name: recipient.name,
        letter_delivery_id: recipient.letter_deliveries[0]?.id,
        delivery_status: recipient.letter_deliveries[0]?.delivery_status === 'delivered',
    })),
})

// Handlers
const submit = () => {
    form
        .transform(data => ({
            ...data,
            recipients: data.recipients.map(r => ({
                recipient_id: r.recipient_id,
                letter_delivery_id: r.letter_delivery_id,
                delivery_status: r.delivery_status ? 'delivered' : 'pending'
            })),
        }))
        .post(route('shipments.update', { shipment: props.shipment.data.id }))
}

const cancel = () => {
    form.reset();
    history.back();
}
</script>

<template>
    <AdminLayout>
        <template #content>
            <div class="px-4 py-2 bg-gray-600">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Edit Shipment</h2>
                </div>
            </div>

            <div class="p-4">
                <form @submit.prevent="submit" class="grid grid-cols-1 gap-4">
                    <div v-for="(recipient, index) in form.recipients" :key="recipient.recipient_id" class="p-4 border rounded-md">
                        <div class="flex items-center justify-between">
                            <Label :for="'delivery_status_' + index" :value="recipient.recipient_name" />
                            <ToggleInput
                                :id="'delivery_status_' + index"
                                v-model="recipient.delivery_status"
                                label="Delivered"
                            />
                        </div>
                         <div v-if="form.errors[`recipients.${index}.delivery_status`]" class="text-red-500 text-sm mt-1">
                            {{ form.errors[`recipients.${index}.delivery_status`] }}
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <Button @click="cancel" variant="secondary" type="button">Cancel</Button>
                        <Button :is-loading="form.processing" type="submit">Update</Button>
                    </div>
                </form>
            </div>
        </template>
    </AdminLayout>
</template>
