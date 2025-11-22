<script setup>
// Layout
import AdminLayout from '@/Layouts/AdminLayout.vue';
// Components
import TextInput from '@/Components/Input/TextInput.vue';
import TextAreaInput from '@/Components/Input/TextAreaInput.vue';
import Label from '@/Components/Label/Label.vue';
import Dropdown from '@/Components/Dropdown/Dropdown.vue';
import Button from '@/Components/Button/Button.vue';
//Libs
import { route } from 'ziggy-js';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    'letterTemplate': {
        type: Object,
        required: true,
        default: () => ({})
    }
})
// Ref
const form = useForm({
    _method: 'PUT',
    name: props.letterTemplate.data.name,
    description: props.letterTemplate.data.description,
    send_type: props.letterTemplate.data.send_type,
    paper_type_id: props.letterTemplate.data.paper_type.id,
    fragrance_type_id: props.letterTemplate.data.fragrance_type.id,
    envelope_type_id: props.letterTemplate.data.envelope_type.id,
    wax_seal_type_id: props.letterTemplate.data.wax_seal_type.id,
    letter_type_ids: props.letterTemplate.data.letter_types?.[0]?.id ?? ''
})
const sendTypeLabel = ref(props.letterTemplate.data.send_type);
const paperLabel = ref(props.letterTemplate.data.paper_type.name);
const fragranceLabel = ref(props.letterTemplate.data?.fragrance_type?.name);
const envelopeLabel = ref(props.letterTemplate.data.envelope_type.name);
const waxSealLabel = ref(props.letterTemplate.data.wax_seal_type.name);
const letterTypeLabel = ref(props.letterTemplate.data.letter_types?.[0]?.name);

const sendTypes = [
    {
        id: 'physical',
        name: 'Physical'
    },
    {
        id: 'digital',
        name: 'Digital'
    }
];

// Handlers
const submit = () => {
    const formData = {
        ...form.data(),
        letter_type_ids: form.letter_type_ids ? [form.letter_type_ids] : []
    };

    form.transform(() => formData).post(route('letter-templates.update', { letter_template: props.letterTemplate.data.id }));
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
                    <h2 class="text-xl font-bold">Letter Templates</h2>
                </div>
            </div>

            <form @submit.prevent="submit" class="p-8 flex flex-col w-1/2 gap-6">
                <div class="flex flex-col gap-2">
                    <Label for="name" mandatory>Letter Template</Label>
                    <TextInput id="name" placeholder="Input Name" v-model="form.name" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="description" mandatory>Description</Label>
                    <TextAreaInput id="description" placeholder="Input Description" v-model="form.description" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="send_type" mandatory>Send Type</Label>
                    <Dropdown v-model="form.send_type" :options="sendTypes" :valueKey="'id'" :labelKey="'name'"
                        v-model:modelLabel="sendTypeLabel" placeholder="Select Send Type" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="paper_type_id" mandatory>Paper Type</Label>
                    <Dropdown v-model="form.paper_type_id" :fetchRoute="route('api.v1.paper-types.index')"
                        v-model:modelLabel="paperLabel" :valueKey="'id'" :labelKey="'name'"
                        placeholder="Select Paper Type" :allowSearch="true" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="fragrance_type_id">Fragrance Type</Label>
                    <Dropdown v-model="form.fragrance_type_id" :fetchRoute="route('api.v1.fragrance-types.index')"
                        v-model:modelLabel="fragranceLabel" :valueKey="'id'" :labelKey="'name'"
                        placeholder="Select Fragrance Type" :allowSearch="true" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="envelope_type_id" mandatory>Envelope Type</Label>
                    <Dropdown v-model="form.envelope_type_id" :fetchRoute="route('api.v1.envelope-types.index')"
                        v-model:modelLabel="envelopeLabel" :valueKey="'id'" :labelKey="'name'"
                        placeholder="Select Envelope Type" :allowSearch="true" :openUpwards="true" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="wax_seal_type_id" mandatory>Wax Seal Type</Label>
                    <Dropdown v-model="form.wax_seal_type_id" :fetchRoute="route('api.v1.wax-seal-types.index')"
                        v-model:modelLabel="waxSealLabel" :valueKey="'id'" :labelKey="'name'"
                        placeholder="Select Wax Seal Type" :allowSearch="true" :openUpwards="true" />
                </div>

                <div class="flex flex-col gap-2">
                    <Label for="letter_type_ids" mandatory>Letter Type</Label>
                    <Dropdown v-model="form.letter_type_ids" :fetchRoute="route('api.v1.letter-types.index')"
                        v-model:modelLabel="letterTypeLabel" :valueKey="'id'" :labelKey="'name'"
                        placeholder="Select Letter Type" :allowSearch="true" :openUpwards="true" />
                </div>

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
