<script>
    // Inertia für Links importieren
    import { inertia } from "@inertiajs/svelte";

    export let href;
    export let as = "button";
    export let method = "get";
</script>

<button
    use:inertia={{ href: href, method: method }}
    type={as}
    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
>
    <slot />
</button>
