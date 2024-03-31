<script>
    import { inertia } from "@inertiajs/svelte";
    import { ArrowDownOutline, ArrowUpOutline } from "flowbite-svelte-icons";

    export let track;
</script>

<div class="min-w-[55vw] sm:min-w-80 flex flex-col gap-1">
    {#if track.image_url != null}
        <img
            src={track.image_url}
            alt={track.title}
            class="w-full h-40 object-cover rounded-lg"
        />
    {/if}
    <h3 class="font-bold text-lg text-center">{track.title}</h3>
    <!-- <p>{track.starting_location != null ? track.starting_location : ""}</p> -->
    <div class="mb-1 w-full grid grid-cols-3">
        <!-- <div class="w-full h-20 flex flex-col justify-around "> -->
        <!-- <div class="flex justify-around "> -->
        <p class="text-gray-500 m-auto">
            {track.starting_location != null ? track.starting_location : ""}
        </p>
        <div class="m-auto">
            <svg
                width="24"
                height="24"
                xmlns="http://www.w3.org/2000/svg"
                fill-rule="evenodd"
                clip-rule="evenodd"
                ><path
                    d="M21.883 12l-7.527 6.235.644.765 9-7.521-9-7.479-.645.764 7.529 6.236h-21.884v1h21.883z"
                /></svg
            >
            <!-- Arrow left to right -->
        </div>
        <p class="text-gray-500 m-auto">
            {track.destination_location != null
                ? track.destination_location
                : ""}
        </p>
        <!-- <p class="text-gray-500 m-auto">
            {Math.round(distance / 10) / 100} km
        </p>
        <p class="m-auto text-gray-500">
            {#if hikingTime < 60}
                {Math.round(hikingTime)} min
            {:else}
                {Math.floor(hikingTime / 60)} h {Math.round(hikingTime % 60)} min
            {/if}
        </p>
        <p class="m-auto text-gray-500">
            <span>
                <ArrowUpOutline size="sm" class="inline-block" />{Math.round(
                    ascent,
                )} m
            </span><span>/</span><span>
                <ArrowDownOutline size="sm" class="inline-block" />{Math.round(
                    descent,
                )} m
            </span>
        </p> -->
    </div>
    <!-- svelte-ignore missing-declaration -->
    <a
        href={route("tracks.show", track.id)}
        use:inertia
        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 justify-center"
        >öffnen</a
    >
</div>
