<script setup>
// Layout
import AdminLayout from '@/Layouts/AdminLayout.vue';
// Components
import TextInput from '@/Components/Input/TextInput.vue';
import TextAreaInput from '@/Components/Input/TextAreaInput.vue';
import NumberInput from '@/Components/Input/NumberInput.vue';
import ToggleInput from '@/Components/Input/ToggleInput.vue';
import ImageMultipleInput from '@/Components/Input/ImageMultipleInput.vue';
import Button from '@/Components/Button/Button.vue';
import Dropdown from '@/Components/Dropdown/Dropdown.vue';
import Label from '@/Components/Label/Label.vue';
//Libs
import { route } from 'ziggy-js';
import { useForm } from '@inertiajs/vue3';

// Props
const props = defineProps({
    'waxSealType': {
        type: Object,
        required: true,
        default: () => ({})
    }
});

// Ref
const form = useForm({
    _method: 'PUT',
    name: props.waxSealType.data.name,
    user_id: props.waxSealType.data.user_id,
    price: props.waxSealType.data.price,
    discount: props.waxSealType.data.discount,
    images: [],
    delete_images: [],
    is_premium: props.waxSealType.data.is_premium,
    status: props.waxSealType.data.status
})

// Handlers
const submit = () => {
    form.post(route('wax-seal-types.update', { wax_seal_type: props.waxSealType.data.id }))
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
                    <h2 class="text-xl font-bold">Wax Seal Type</h2>
                </div>
            </div>

            <form @submit.prevent="submit" class="p-8 flex flex-col w-1/2 gap-6">
                <div class="flex flex-col gap-2">
                    <Label for="name" mandatory>Wax Seal Name</Label>
                    <TextInput id="name" placeholder="Input Name" v-model="form.name" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="user_id">Seal Owner</Label>
                    <Dropdown v-model="form.user_id" :fetchRoute="route('api.v1.users.index')" :valueKey="'id'"
                        :labelKey="'email'" placeholder="Select User Email" :allowSearch="true" :allowClear="true" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="price" mandatory>Price</Label>
                    <NumberInput id="price" placeholder="Input Price" v-model="form.price" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="discount">Discount</Label>
                    <NumberInput id="discount" placeholder="Input Discount" v-model="form.discount" :min="1"
                        :max="100" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label mandatory>Image</Label>
                    <ImageMultipleInput v-model="form.images" :uploadedImages="waxSealType.data.images"
                        v-model:deleteImages="form.delete_images" />
                </div>

                <ToggleInput v-model="form.is_premium" label="Is Premium" />

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
