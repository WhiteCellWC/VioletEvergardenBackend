import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faSun, faMoon, faPlus, faMagnifyingGlass, faPenToSquare, faTrash, faSortUp, faSortDown, faSort, faEye } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
library.add(faSun, faMoon, faPlus, faMagnifyingGlass, faPenToSquare, faTrash, faSortUp, faSortDown, faSort, faEye);
createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('FontAwesomeIcon', FontAwesomeIcon)
            .mount(el);
    },
});
