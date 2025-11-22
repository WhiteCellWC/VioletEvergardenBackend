<script setup>
// Layout
import AdminLayout from '@/Layouts/AdminLayout.vue';
// Components
import NumberInput from '@/Components/Input/NumberInput.vue';
import Label from '@/Components/Label/Label.vue';
import ToggleInput from '@/Components/Input/ToggleInput.vue';
import Dropdown from '@/Components/Dropdown/Dropdown.vue';
import Button from '@/Components/Button/Button.vue';
//Libs
import { route } from 'ziggy-js';
import { useForm } from '@inertiajs/vue3';

// Ref
const form = useForm({
    delivery_option_id: '',
    max_weight: '',
    price: '',
    status: true
})

// Handlers
const submit = () => {
    form.post(route('delivery-tiers.store'))
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
                    <h2 class="text-xl font-bold">Delivery Tiers</h2>
                </div>
            </div>

            <form @submit.prevent="submit" class="p-8 flex flex-col w-1/2 gap-6">
                <div class="flex flex-col gap-2">
                    <Label for="paper_type_id" mandatory>Delivery Option</Label>
                    <Dropdown v-model="form.delivery_option_id" :fetchRoute="route('api.v1.delivery-options.index')"
                        :valueKey="'id'" :labelKey="'name'" placeholder="Select Delivery Option" :allowSearch="true" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="max_weight" mandatory>Max Weight</Label>
                    <NumberInput id="max_weight" placeholder="Input Weight" v-model="form.max_weight" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="price" mandatory>Price</Label>
                    <NumberInput id="price" placeholder="Input Price" v-model="form.price" />
                </div>

                <ToggleInput v-model="form.status" label="Status" />

                <div class="flex justify-end gap-4">
                    <Button type="button" background="bg-gray-500 text-white"
                        hover="hover:bg-gray-600 hover:outline-none hover:ring hover:ring-gray-500" @click="cancel">
                        Cancel
                    </Button>
                    <Button :disabled="form.processing">
                        Save
                    </Button>
                </div>
            </form>
        </template>
    </AdminLayout>
</template>
