<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Table from '@/Components/Table/Table.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue/Breadcrumb.vue';
import Modal from '@/Components/Modal/Modal.vue';
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js';
import { ref } from 'vue';

//Props
const props = defineProps({
    letterTypes: {
        type: Object,
        required: false,
        default: () => ({
            data: [],
            links: {},
            meta: {},
        })
    }
});

//Ref
const columns = ref([
    {
        label: 'Name',
        field: 'name',
        sortable: true
    },
    {
        label: 'Letter Template Count',
        field: (row) => row.letter_templates?.length,
        sortable: true,
        sort: () => {  }
    },
    {
        label: 'Status',
        field: 'status',
        sortable: true
    },
    {
        label: 'Created At',
        field: 'created_at',
        sortable: true
    }
]);
const breadcrumbs = ref([
    {
        label: 'Dashboard',
        url: route('dashboard')
    },
    {
        label: 'State',
        active: true
    }
]);
</script>

<template>
    <AdminLayout>
        <template #breadcrumb>
            <Breadcrumb :items="breadcrumbs" />
        </template>
        <template #content>
            <div class="px-4 py-2 bg-gray-600">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Letter Types</h2>
                    <Link :href="route('letter-types.create')"
                        class="bg-[var(--primary-50)] text-black hover:bg-[var(--primary-100)] px-2 py-1 rounded font-bold">
                    <FontAwesomeIcon :icon="['fas', 'plus']" class="text-black me-1" />
                    <span>Create Letter Type</span>
                    </Link>
                </div>
            </div>

            <div class="p-4">
                <Table :resource="props.letterTypes" :columns="columns" :windowSize="1">
                    <template #actions="{ row }">
                        <Link :href="route('letter-types.edit', row.id)">
                        <FontAwesomeIcon :icon="['fas', 'pen-to-square']" class="text-blue-500 me-2" />
                        </Link>
                        <Link :href="route('letter-types.destroy', row.id)" method="delete" class="">
                        <FontAwesomeIcon :icon="['fas', 'trash']" class="text-red-500" />
                        </Link>
                    </template>
                </Table>
            </div>
            <Modal />
        </template>
    </AdminLayout>
</template>
