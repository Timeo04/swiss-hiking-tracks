<script>
    import { inertia } from "@inertiajs/svelte";

    export let track;
</script>

<div class="flex flex-col gap-0">
    <h3 class="font-bold text-lg">{track.title}</h3>
    <p>{track.starting_location != null ? track.starting_location : ""}</p>
    <!-- svelte-ignore missing-declaration -->
    <a
        href={route("tracks.show", track.id)}
        use:inertia
        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 justify-center"
        >öffnen</a
    >
</div>
