<script>
    // Inertia für Links importieren
    import { inertia } from "@inertiajs/svelte";
    
    export let href;
    export let active = false;

    const classes = active
        ? "inline-flex items-center px-1 pt-1 border-b-2 border-primary-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-primary-700 transition duration-150 ease-in-out"
        : "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out";
</script>

<a {href} class={classes} use:inertia>
    <slot />
</a>