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
    shipments: {
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
        label: 'User Email',
        field: row => row?.user?.email,
        sortable: true
    },
    {
        label: 'Total Recipients',
        field: row => row.recipients?.length
    },
    {
        label: 'Letter Delivered',
        field: row => {
            let deliveredCount = 0;
            row?.recipients?.forEach(recipient => {
                console.log(recipient);
                if (recipient?.letter_deliveries?.[0]?.delivery_status == 'delivered') {
                    deliveredCount++;
                }
            })
            return deliveredCount;
        }
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
        label: 'Shipment',
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
                    <h2 class="text-xl font-bold">Shipments</h2>
                    <Link :href="'#'"
                        class="bg-[var(--primary-50)] text-black hover:bg-[var(--primary-100)] px-2 py-1 rounded font-bold">
                    <FontAwesomeIcon :icon="['fas', 'plus']" class="text-black me-1" />
                    <span>Create Shipment</span>
                    </Link>
                </div>
            </div>

            <div class="p-4">
                <Table :resource="props.shipments" :columns="columns" :windowSize="1">
                    <template #actions="{ row }">
                        <Link :href="'#'" method="delete" class="">
                        <FontAwesomeIcon :icon="['fas', 'eye']" class="text-green-500 me-2" />
                        </Link>
                        <Link :href="route('shipments.edit', { shipment: row.id })">
                        <FontAwesomeIcon :icon="['fas', 'pen-to-square']" class="text-blue-500" />
                        </Link>
                    </template>
                </Table>
            </div>
            <Modal />
        </template>
    </AdminLayout>
</template>
