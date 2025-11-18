<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Table from '../../Components/Table/Table.vue';
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js';

//Props
const props = defineProps({
    waxSealTypes: {
        type: Object,
        required: false,
        default: () => ({
            data: [],
            links: {},
            meta: {},
        })
    }
})

//Ref
const columns = [
    {
        label: 'Name',
        field: 'name',
        sortable: true
    },
    {
        label: 'Is Custom',
        field: 'is_custom',
        sortable: true
    },
    {
        label: 'Price',
        field: 'price',
        sortable: true
    },
    {
        label: 'Premium',
        field: 'is_premium',
        sortable: true
    },
    {
        label: 'Discount',
        field: 'discount',
        sortable: true
    },
    {
        label: 'Status',
        field: 'status',
        sortable: true
    },
    {
        label: 'Created At',
        field: 'created_at',
    },
]

</script>

<template>
    <AdminLayout>
        <template #breadcrumb>
            <div>
                Dashboard > <span class="text-[var(--primary-50)]">Wax Seal Type</span>
            </div>
        </template>
        <template #content>
            <div class="px-4 py-2 bg-gray-600">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Wax Seal Types</h2>
                    <Link :href="route('wax-seal-types.create')"
                        class="bg-[var(--primary-50)] text-black hover:bg-[var(--primary-100)] px-2 py-1 rounded font-bold">
                    <FontAwesomeIcon :icon="['fas', 'plus']" class="text-black me-1" />
                    <span>Create Wax Seal Type</span>
                    </Link>
                </div>
            </div>

            <div class="p-4">
                <Table :resource="props.waxSealTypes" :columns="columns" :windowSize="1">
                    <template #actions="{ row }">
                        <Link :href="route('wax-seal-types.edit', row.id)">
                        <FontAwesomeIcon :icon="['fas', 'pen-to-square']" class="text-blue-500 me-2" />
                        </Link>
                        <Link :href="route('wax-seal-types.destroy', row.id)" method="delete" class="">
                        <FontAwesomeIcon :icon="['fas', 'trash']" class="text-red-500" />
                        </Link>
                    </template>
                </Table>
            </div>
        </template>
    </AdminLayout>
</template>
