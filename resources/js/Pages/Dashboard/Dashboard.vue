<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import Breadcrumb from "@/Components/Breadcrumb.vue/Breadcrumb.vue";
import ChartCard from '@/Components/ChartCard.vue';
import { getChartTheme } from '@/utils/chartConfig.js';
import { computed, ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const props = defineProps({
    statistics: Object,
    chartData: Object,
    recentActivity: Object,
});

const breadcrumb = {
    title: 'Dashboard',
    items: [
        {
            name: 'Home',
            url: '/dashboard',
            is_active: false,
        },
        {
            name: 'Dashboard',
            url: '/dashboard',
            is_active: true,
        },
    ],
};

const isDark = ref(false);

const statusColors = {
    pending: 'text-amber-500',
    shipped: 'text-blue-500',
    delivered: 'text-green-500',
};

const getShipmentStatusClass = (status) => {
    const key = typeof status === 'string' ? status.toLowerCase() : null;
    return statusColors[key] || 'text-gray-500';
};

onMounted(() => {
    isDark.value = document.documentElement.classList.contains('dark');

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                isDark.value = document.documentElement.classList.contains('dark');
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true
    });
});

const lettersChartOptions = computed(() => {
    const lettersPerDay = Array.isArray(props.chartData?.letters_per_day) ? props.chartData.letters_per_day : [];
    const theme = getChartTheme(isDark.value);
    return {
        ...theme,
        chart: {
            ...theme.chart,
            type: 'area',
            height: 350,
            zoom: {
                enabled: false,
            },
        },
        xaxis: {
            ...theme.xaxis,
            categories: lettersPerDay.map(item =>
                new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
            ),
        },
        yaxis: {
            ...theme.yaxis,
            title: {
                text: 'Number of Letters',
                style: {
                    ...theme.yaxis.title.style
                }
            },
            labels: {
                 ...theme.yaxis.labels,
                formatter: (value) => parseInt(value),
            }
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth',
            width: 2,
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
                stops: [0, 90, 100]
            }
        },
        tooltip: {
            ...theme.tooltip,
            x: {
                format: 'dd MMM'
            },
            y: {
                formatter: function (val) {
                    return val + " letters"
                }
            }
        }
    };
});

const lettersChartSeries = computed(() => {
    const lettersPerDay = Array.isArray(props.chartData?.letters_per_day) ? props.chartData.letters_per_day : [];
    return [{
        name: 'Letters Created',
        data: lettersPerDay.map(item => item.count)
    }];
});

const deliveryChartOptions = computed(() => {
    const { pending = 0, shipped = 0, delivered = 0 } = props.chartData?.delivery_status_breakdown || {};
    const theme = getChartTheme(isDark.value);
    return {
        ...theme,
        chart: {
            ...theme.chart,
            type: 'donut',
            height: 350
        },
        labels: ['Pending', 'Shipped', 'Delivered'],
        colors: ['#f59e0b', '#3b82f6', '#10b981'],
        legend: {
            ...theme.legend,
            position: 'bottom'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                const series = opts.w.globals.series;
                const idx = opts.seriesIndex;
                const total = series.reduce((a, b) => a + b, 0);
                const count = series[idx];
                const percentage = total > 0 ? ((count / total) * 100).toFixed(1) : 0;
                return `${count} (${percentage}%)`;
            }
        },
        tooltip: {
            ...theme.tooltip,
            y: {
                formatter: function (val) {
                    return val + " deliveries"
                }
            }
        },
        responsive: theme.responsive,
    };
});

const deliveryChartSeries = computed(() => {
    const { pending = 0, shipped = 0, delivered = 0 } = props.chartData?.delivery_status_breakdown || {};
    return [pending, shipped, delivered];
});


</script>

<template>
    <AdminLayout>
        <template #breadcrumb>
            <Breadcrumb :data="breadcrumb" />
        </template>
        <template #content>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
                <StatCard
                    title="Total Users"
                    :value="statistics.total_users"
                    :icon="['fas', 'users']"
                    icon-bg-color="bg-blue-100 dark:bg-blue-900"
                    icon-color="text-blue-600 dark:text-blue-400"
                />

                <StatCard
                    title="Letters This Month"
                    :value="statistics.letters_this_month"
                    :icon="['fas', 'envelope']"
                    icon-bg-color="bg-green-100 dark:bg-green-900"
                    icon-color="text-green-600 dark:text-green-400"
                />

                <StatCard
                    title="Deliveries This Week"
                    :value="statistics.deliveries_this_week"
                    :icon="['fas', 'truck']"
                    icon-bg-color="bg-purple-100 dark:bg-purple-900"
                    icon-color="text-purple-600 dark:text-purple-400"
                />

                <StatCard
                    title="New Users Today"
                    :value="statistics.new_users_today"
                    :icon="['fas', 'user-plus']"
                    icon-bg-color="bg-orange-100 dark:bg-orange-900"
                    icon-color="text-orange-600 dark:text-orange-400"
                />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 px-6">
                 <ChartCard title="Letters Per Day" subtitle="Last 7 days">
                    <apexchart
                        type="area"
                        height="350"
                        :options="lettersChartOptions"
                        :series="lettersChartSeries"
                    ></apexchart>
                </ChartCard>

                <ChartCard title="Delivery Status Breakdown" subtitle="Current status distribution">
                    <apexchart
                        type="donut"
                        height="350"
                        :options="deliveryChartOptions"
                        :series="deliveryChartSeries"
                    ></apexchart>
                </ChartCard>
            </div>

            <!-- Recent Activity Section -->
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Recent Activity</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Recent Letters -->
                    <div class="bg-white dark:bg-[var(--dark-bg-100)] rounded-xl shadow-sm">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Letters</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Latest 5 letters created</p>
                        </div>
                        <div class="p-4 space-y-4">
                            <div v-if="!(recentActivity.letters || []).length" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                No recent letters.
                            </div>
                             <ul v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li v-for="letter in (recentActivity.letters || [])" :key="letter.id" class="py-3 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 px-2 rounded">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ letter.title }}</p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">by {{ letter.user.name }}</p>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 text-right">{{ new Date(letter.created_at).toLocaleDateString() }}</div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Recent Shipments -->
                    <div class="bg-white dark:bg-[var(--dark-bg-100)] rounded-xl shadow-sm flex flex-col">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Shipments</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Latest 5 shipments</p>
                        </div>
                        <div class="p-4 space-y-4 flex-1">
                             <div v-if="!(recentActivity.shipments || []).length" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                No recent shipments.
                            </div>
                            <ul v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li v-for="shipment in (recentActivity.shipments || [])" :key="shipment.id" class="py-3 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 px-2 rounded">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ shipment.recipient.name }}</p>
                                        <p class="text-sm capitalize" :class="getShipmentStatusClass(shipment.status)">{{ shipment.status || 'Unknown' }}</p>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ new Date(shipment.created_at).toLocaleDateString() }}</div>
                                </li>
                            </ul>
                        </div>
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                            <Link :href="route('shipments.index')" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">View All</Link>
                        </div>
                    </div>

                    <!-- Recent Users -->
                    <div class="bg-white dark:bg-[var(--dark-bg-100)] rounded-xl shadow-sm flex flex-col">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Users</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Latest 5 registered users</p>
                        </div>
                        <div class="p-4 space-y-4 flex-1">
                             <div v-if="!(recentActivity.users || []).length" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                No recent users.
                            </div>
                            <ul v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li v-for="user in (recentActivity.users || [])" :key="user.id" class="py-3 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 px-2 rounded">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ new Date(user.created_at).toLocaleDateString() }}</div>
                                </li>
                            </ul>
                        </div>
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                            <Link :href="route('users.index')" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">View All</Link>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </AdminLayout>
</template>
