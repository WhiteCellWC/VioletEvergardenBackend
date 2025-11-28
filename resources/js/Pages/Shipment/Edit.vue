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
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="bg-violet-200 border border-gray-200 rounded-lg shadow-sm p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Shipment Components</h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="flex flex-col items-center gap-3">
                                <span class="text-sm font-semibold text-gray-700">{{
                                    props.shipment.data.paper_type?.name }}</span>
                                <img class="h-48 w-48 object-cover rounded-md bg-gray-100 mx-auto"
                                    :src="props.shipment.data.paper_type?.images?.[0]?.image_path || '/placeholder.png'"
                                    alt="Paper Type" />
                            </div>

                            <div class="flex flex-col items-center gap-3">
                                <span class="text-sm font-semibold text-gray-700">{{
                                    props.shipment.data.envelope_type?.name }}</span>
                                <img class="h-48 w-48 object-cover rounded-md bg-gray-100 mx-auto"
                                    :src="props.shipment.data.envelope_type?.images?.[0]?.image_path || '/placeholder.png'"
                                    alt="Envelope Type" />
                            </div>

                            <div class="flex flex-col items-center gap-3">
                                <span class="text-sm font-semibold text-gray-700">{{
                                    props.shipment.data.wax_seal_type?.name }}</span>
                                <img class="h-48 w-48 object-cover rounded-md bg-gray-100 mx-auto"
                                    :src="props.shipment.data.wax_seal_type?.images?.[0]?.image_path || '/placeholder.png'"
                                    alt="Wax Seal Type" />
                            </div>

                            <div class="flex flex-col items-center gap-3">
                                <span class="text-sm font-semibold text-gray-700">{{
                                    props.shipment.data.fragrance_type?.name }}</span>
                                <img class="h-48 w-48 object-cover rounded-md bg-gray-100 mx-auto"
                                    :src="props.shipment.data.fragrance_type?.images?.[0]?.image_path || '/placeholder.png'"
                                    alt="Fragrance Type" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-white mb-4">Recipients</h3>
                        <div class="space-y-4">
                            <div v-for="(recipient, index) in form.recipients" :key="recipient.recipient_id"
                                class="p-6 border border-gray-200 rounded-lg shadow-sm bg-violet-200 flex items-center justify-between">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="text-sm"><span class="font-semibold text-gray-700">Name:</span> <span
                                            class="text-gray-600">{{ recipient.recipient_name }}</span></div>
                                    <div class="text-sm"><span class="font-semibold text-gray-700">Email:</span> <span
                                            class="text-gray-600">{{ recipient.recipient_email }}</span></div>
                                    <div class="text-sm col-span-2"><span class="font-semibold text-gray-700">Address
                                            Line 1:</span> <span class="text-gray-600">{{ recipient.recipient_address1
                                            }}</span></div>
                                    <div v-if="recipient.recipient_address2" class="text-sm col-span-2"><span
                                            class="font-semibold text-gray-700">Address Line 2:</span> <span
                                            class="text-gray-600">{{ recipient.recipient_address2 }}</span></div>
                                    <div class="text-sm"><span class="font-semibold text-gray-700">Country:</span> <span
                                            class="text-gray-600">{{ recipient.country }}</span></div>
                                    <div class="text-sm"><span class="font-semibold text-gray-700">State:</span> <span
                                            class="text-gray-600">{{ recipient.state }}</span></div>
                                    <div class="text-sm"><span class="font-semibold text-gray-700">Postal Code:</span>
                                        <span class="text-gray-600">{{ recipient.postal_code }}</span>
                                    </div>
                                </div>
                                <div>
                                    <ToggleInput class="text-black" :id="'delivery_status_' + index" v-model="recipient.delivery_status"
                                        label="Delivered" />
                                    <div v-if="form.errors[`recipients.${index}.delivery_status`]"
                                        class="text-red-500 text-sm mt-1">
                                        {{ form.errors[`recipients.${index}.delivery_status`] }}
                                    </div>
                                </div>
                            </div>
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
