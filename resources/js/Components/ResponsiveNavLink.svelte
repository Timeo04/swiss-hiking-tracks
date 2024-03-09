<script>
    import { inertia } from "@inertiajs/svelte";
    export let href;
    export let active = false;
    export let method = "get";
    export let as = "a";

    const classes = active
        ? "block w-full ps-3 pe-4 py-2 border-l-4 border-primary-400 text-start text-base font-medium text-primary-700 bg-primary-50 focus:outline-none focus:text-primary-800 focus:bg-primary-100 focus:border-primary-700 transition duration-150 ease-in-out"
        : "block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out";
</script>

<!-- svelte-ignore a11y-missing-attribute -->
<a class={classes} use:inertia={{ href: href, method: method }} {as}>
    <slot />
</a>
