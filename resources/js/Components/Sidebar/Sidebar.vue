<script setup>
import { onMounted, ref } from 'vue'
import logo from '@/assets/logo/violet-evergarden-admin-dashboard.png'
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const openedDropdown = ref(null)
const locationDropdown = ref('location');
const deliveryDropdown = ref('delivery');
const letterComponentDropdown = ref('letter_component');
const letterDropdown = ref('letter');

const handleDropdownClick = (dropdown) => {
    if (dropdown == openedDropdown.value) {
        openedDropdown.value = null;
    } else {
        openedDropdown.value = dropdown;
    }
}

onMounted(() => {
    if (route().current('states.*') || route().current('countries.*')) {
        openedDropdown.value = locationDropdown.value;
    }
    if (route().current('fragrance-types.*') || route().current('envelope-types.*') || route().current('paper-types.*') || route().current('wax-seal-types.*')) {
        openedDropdown.value = letterComponentDropdown.value;
    }
    if (route().current('letter-types.*') || route().current('letter-templates.*')) {
        openedDropdown.value = letterDropdown.value;
    }
    if (route().current('delivery-options.*')) {
        openedDropdown.value = deliveryDropdown.value;
    }
})
</script>

<template>
    <div
        class="col-span-1 bg-white dark:bg-[var(--dark-bg-100)] max-xl:hidden rounded-2xl shadow-sm p-4 max-h-[93svh] sticky top-6 overflow-y-auto">
        <!-- Logo -->
        <div class="flex items-center gap-4">
            <img :src="logo" alt="Logo" class="rounded-full w-16" />
            <div class="flex flex-col items-center font-bold text-[var(--primary-50)]">
                <span>Violet</span>
                <span>Evergarden</span>
            </div>
        </div>

        <!-- Nav -->
        <nav class="space-y-2 mt-4">
            <Link :href="route('dashboard')" class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('dashboard') }">
            Dashboard</Link>
            <a href="#" class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Users</a>
            <a href="#" class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Letters</a>
            <a href="#" class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">Packages</a>

            <!-- Letter Dropdown -->
            <div>
                <button @click="handleDropdownClick(letterDropdown)"
                    class="w-full flex items-center justify-between p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span>Letter</span>
                    <svg :class="{ 'rotate-90': openedDropdown == letterDropdown }" class="w-4 h-4 transition-transform"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div v-show="openedDropdown == letterDropdown" class="ml-4 mt-2 space-y-1 transition-all duration-200">
                    <Link :href="route('letter-types.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('letter-types.*') }">
                    Letter Type
                    </Link>
                    <Link :href="route('letter-templates.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('letter-templates.*') }">
                    Letter Templates
                    </Link>
                </div>
            </div>

            <!-- Letter Component Dropdown -->
            <div>
                <button @click="handleDropdownClick(letterComponentDropdown)"
                    class="w-full flex items-center justify-between p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span>Letter Component</span>
                    <svg :class="{ 'rotate-90': openedDropdown == letterComponentDropdown }"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div v-show="openedDropdown == letterComponentDropdown"
                    class="ml-4 mt-2 space-y-1 transition-all duration-200">
                    <Link :href="route('fragrance-types.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('fragrance-types.*') }">
                    Fragrance Type
                    </Link>
                    <Link :href="route('envelope-types.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('envelope-types.*') }">
                    Envelope Type
                    </Link>
                    <Link :href="route('paper-types.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('paper-types.*') }">
                    Paper Type
                    </Link>
                    <Link :href="route('wax-seal-types.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('wax-seal-types.*') }">
                    Wax Seal Type
                    </Link>
                </div>
            </div>

            <!-- Location Dropdown -->
            <div>
                <button @click="handleDropdownClick(locationDropdown)"
                    class="w-full flex items-center justify-between p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span>Location</span>
                    <svg :class="{ 'rotate-90': openedDropdown == locationDropdown }"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div v-show="openedDropdown == locationDropdown"
                    class="ml-4 mt-2 space-y-1 transition-all duration-200">
                    <Link :href="route('countries.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('countries.*') }">
                    Country
                    </Link>
                    <Link :href="route('states.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('states.*') }">State
                    </Link>
                </div>
            </div>

            <!-- Delivery Dropdown -->
            <div>
                <button @click="handleDropdownClick(deliveryDropdown)"
                    class="w-full flex items-center justify-between p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                    <span>Delivery</span>
                    <svg :class="{ 'rotate-90': openedDropdown == deliveryDropdown }"
                        class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div v-show="openedDropdown == deliveryDropdown"
                    class="ml-4 mt-2 space-y-1 transition-all duration-200">
                    <Link :href="route('delivery-options.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('delivery-options.*') }">
                    Delivery Option
                    </Link>
                    <Link :href="route('delivery-tiers.index')"
                        class="block p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-200 dark:bg-gray-600 font-semibold': route().current('delivery-tiers.*') }">
                    Delivery Tier
                    </Link>
                </div>
            </div>
        </nav>
    </div>
</template>
