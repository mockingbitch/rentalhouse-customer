import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist';
import { usePage } from '@inertiajs/vue3';
import '../css/app.css';
import { InertiaProgress } from '@inertiajs/progress';

createInertiaApp({
    resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mixin({
                methods: {
                    lang: function () {
                        return usePage().props.language.original;
                    }
                },
            })
            .mount(el)
    },
});
InertiaProgress.init();
