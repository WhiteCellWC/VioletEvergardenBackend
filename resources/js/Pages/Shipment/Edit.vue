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
        recipient_email: recipient.email,
        recipient_address1: recipient.address_line_1,
        recipient_address2: recipient.address_line_2,
        country: recipient.country.name,
        state: recipient.state.name,
        postal_code: recipient.postal_code,
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
                    <div class="grid grid-cols-2">
                        <div class="flex flex-col">
                            <Label :for="'paper_type_' + index">
                                {{ props.shipment.data.paper_type?.name }}
                            </Label>
                            <img class="h-64 w-64 object-cover rounded-md bg-gray-100"
                                :src="props.shipment.data.paper_type?.images?.[0]?.image_path || '/placeholder.png'"
                                alt="Paper Type" />
                        </div>

                        <div class="flex flex-col">
                            <Label :for="'envelope_type_' + index">
                                {{ props.shipment.data.envelope_type?.name }}
                            </Label>
                            <img class="h-64 w-64 object-cover rounded-md bg-gray-100"
                                :src="props.shipment.data.envelope_type?.images?.[0]?.image_path || '/placeholder.png'"
                                alt="Envelope Type" />
                        </div>

                        <div class="flex flex-col">
                            <Label :for="'wax_seal_type_' + index">
                                {{ props.shipment.data.wax_seal_type?.name }}
                            </Label>
                            <img class="h-64 w-64 object-cover rounded-md bg-gray-100"
                                :src="props.shipment.data.wax_seal_type?.images?.[0]?.image_path || '/placeholder.png'"
                                alt="Wax Seal Type" />
                        </div>

                        <div class="flex flex-col">
                            <Label :for="'fragrance_type_' + index">
                                {{ props.shipment.data.fragrance_type?.name }}
                            </Label>
                            <img class="h-64 w-64 object-cover rounded-md bg-gray-100"
                                :src="props.shipment.data.fragrance_type?.images?.[0]?.image_path || '/placeholder.png'"
                                alt="Fragrance Type" />
                        </div>
                    </div>

                    <div v-for="(recipient, index) in form.recipients" :key="recipient.recipient_id"
                        class="p-4 border rounded-md">
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col gap-2">
                                <Label :for="'delivery_status_' + index">
                                    Name: {{ recipient.recipient_name }}
                                </Label>
                                <Label :for="'delivery_status_' + index">
                                    Email: {{ recipient.recipient_email }}
                                </Label>
                                <Label :for="'delivery_status_' + index">
                                    Address Line 1: {{ recipient.recipient_address1 }}
                                </Label>
                                <Label :for="'delivery_status_' + index">
                                    Address Line 2: {{ recipient.recipient_address2 }}
                                </Label>
                                <Label :for="'delivery_status_' + index">
                                    Country: {{ recipient.country }}
                                </Label>
                                <Label :for="'delivery_status_' + index">
                                    State: {{ recipient.state }}
                                </Label>
                                <Label :for="'delivery_status_' + index">
                                    Postal Code: {{ recipient.postal_code }}
                                </Label>
                            </div>
                            <ToggleInput :id="'delivery_status_' + index" v-model="recipient.delivery_status"
                                label="Delivered" />
                        </div>
                        <div v-if="form.errors[`recipients.${index}.delivery_status`]"
                            class="text-red-500 text-sm mt-1">
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
