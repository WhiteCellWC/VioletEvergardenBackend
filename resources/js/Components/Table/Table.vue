<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown/Dropdown.vue'
import { ref, watch } from 'vue';

//Props
const props = defineProps({
    resource: {
        type: Object,
        required: true,
        default: () => ({
            data: [],
            links: {},
            meta: {},
        }),
    },
    columns: {
        type: Array,
        required: true,
        default: () => []
    },
    windowSize: {
        type: Number,
        default: 2
    }
});

//Ref
const page = usePage()
const limit = ref(Number(page.props.ziggy.query.pag_per_page) || 10)
const orderBy = ref(page.props.ziggy.query?.order_by);
const orderType = ref(page.props.ziggy.query?.order_type);

const limitOptions = [
    {
        label: 10,
        key: 10
    },
    {
        label: 20,
        key: 20
    },
    {
        label: 30,
        key: 30
    },
    {
        label: 40,
        key: 40
    },
    {
        label: 50,
        key: 50
    }
];

//Functions
const sort = (field, sort = null) => {
    if (sort) sort();

    // Determine new order type
    const cycle = { asc: 'desc', desc: null, null: 'asc' };
    orderType.value = field === orderBy.value ? cycle[orderType.value] : 'asc';
    orderBy.value = orderType.value ? field : null;

    // Prepare query
    const query = { ...page.props.ziggy.query };
    if (orderType.value) {
        query.order_by = orderBy.value;
        query.order_type = orderType.value;
    } else {
        delete query.order_by;
        delete query.order_type;
    }

    // Remove existing query from URL
    const url = page.url.split('?')[0];
    router.get(url, query, {
        replace: true,
        preserveScroll: true,
        preserveState: true,
    });
};

const getValue = (row, field) => {
    if (typeof field === 'function') {
        return field(row)
    }
    if (typeof field === 'string') {
        return field.split('.').reduce((acc, key) => acc?.[key], row)
    }
    return ''
}

const windowedLinks = (meta, windowSize) => {
    if (!meta?.links) return []

    const total = meta.last_page
    const current = meta.current_page
    const links = meta.links

    const result = []

    links.forEach(link => {
        if (
            link.label.includes('Previous') ||
            link.label.includes('Next') ||
            parseInt(link.label) === 1 ||
            parseInt(link.label) === total ||
            (parseInt(link.label) >= current - windowSize &&
                parseInt(link.label) <= current + windowSize)
        ) {
            result.push(link)
        } else if (result[result.length - 1]?.label !== '...') {
            result.push({ label: '...', url: null })
        }
    })

    return result
}

//Watch
watch(limit, (newLimit) => {
    const query = { ...page.props.ziggy.query, pag_per_page: newLimit };
    router.get(page.url, query, {
        replace: true,
        preserveScroll: true,
        preserveState: true
    })
})
</script>

<template>
    <div class="flex mb-4">
        <div class="flex items-center gap-2">
            <label>Show:</label>
            <Dropdown v-model="limit" v-model:modelLabel="limit" :options="limitOptions" :valueKey="'key'"
                :labelKey="'label'" />
        </div>
    </div>

    <div class="overflow-auto rounded-lg border border-gray-300 dark:border-gray-700">
        <table class="w-full">
            <!-- Head -->
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-700 text-start">Actions</th>
                    <th class="px-4 py-2 border-b border-gray-300 dark:border-gray-700 text-start">No</th>
                    <th v-for="(col, i) in columns" :key="i"
                        class="px-4 py-2 border-b border-gray-300 dark:border-gray-700 text-start">
                        <div class="flex items-center gap-2" :class="col.sortable ? 'cursor-pointer' : ''"
                            @click="col.sortable && sort(col.field, col?.sort)">
                            <div class="whitespace-nowrap">
                                {{ col.label }}
                            </div>
                            <div v-if="col.sortable">
                                <FontAwesomeIcon v-if="orderBy === col.field && orderType === 'asc'"
                                    :icon="['fas', 'sort-up']" class="text-[var(--gray-50)]" />
                                <FontAwesomeIcon v-else-if="orderBy === col.field && orderType === 'desc'"
                                    :icon="['fas', 'sort-down']" class="text-[var(--gray-50)]" />
                                <FontAwesomeIcon v-else :icon="['fas', 'sort']" class="text-[var(--gray-50)]" />
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>

            <!-- Body -->
            <tbody>
                <tr v-for="(row, i) in resource.data" :key="row.id"
                    class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-700">
                        <slot name="actions" :row="row" />
                    </td>

                    <td class="px-4 py-2 border-b border-gray-300 dark:border-gray-700">
                        {{ (resource.meta?.from ?? 0) + i }}
                    </td>

                    <td v-for="(col, j) in columns" :key="`col-${i}-${j}`"
                        class="px-4 py-2 border-b border-gray-300 dark:border-gray-700">
                        {{ getValue(row, col.field) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div v-if="resource.meta?.last_page > 1" class="flex justify-end mt-4 space-x-2">
        <template v-for="(link, i) in windowedLinks(resource.meta, windowSize)" :key="i">
            <span v-if="!link.url" class="px-3 py-1" v-html="link.label" />
            <Link v-else :href="link.url" v-html="link.label" class="px-3 py-1 border rounded" :class="{
                'bg-[var(--primary-50)] text-black font-bold': link.active,
                'hover:bg-gray-200 dark:hover:bg-gray-700': !link.active
            }" />
        </template>
    </div>
</template>
