<script>
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import { router } from "@inertiajs/svelte";
    import { Button } from "flowbite-svelte";
    export let auth;
    export let tracks;
    console.log(tracks);
</script>

<svelte:head>
    <title>Routen</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <!-- <h1 class="text-2xl font-semibold">Routen</h1> -->
    <!-- <Button color="primary">Route erstellen</Button> -->
    <h1 class="py-10 text-2xl font-semibold text-center">Wanderungen</h1>
    {#if tracks == null || tracks.length === 0}
        <p>Keine Routen vorhanden</p>
    {:else}
        <div class="flex flex-col gap-2 p-2">
            {#each tracks as track}
                <div
                    class="flex items-center justify-between p-4 bg-white rounded-lg shadow-md"
                >
                    <div class="flex items-center">
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold">{track.title}</h2>
                            <p class="text-gray-500">
                                {track.starting_location != null
                                    ? track.starting_location + " - "
                                    : ""}{track.destination_location != null
                                    ? track.destination_location
                                    : ""}
                            </p>
                        </div>
                    </div>
                    <button
                        on:click={() =>
                            router.visit(route("tracks.show", { track }))}
                        class="px-4 py-2 text-white bg-primary-600 rounded-lg"
                    >
                        Öffnen
                    </button>
                </div>
            {/each}
        </div>
    {/if}
    <button
        on:click={() => router.visit(route("tracks.create"))}
        class="group fixed bottom-20 right-4 p-0 w-12 h-12 border-primary-600 border-[3px] rounded-full hover:bg-primary-700 active:shadow-lg mouse shadow transition ease-in duration-200 focus:outline-none"
    >
        <svg
            viewBox="0 0 20 20"
            enable-background="new 0 0 20 20"
            class="w-6 h-6 inline-block"
        >
            <path
                class="group-hover:fill-white fill-primary-600 transition ease-in duration-200"
                d="M16,10c0,0.553-0.048,1-0.601,1H11v4.399C11,15.951,10.553,16,10,16c-0.553,0-1-0.049-1-0.601V11H4.601
                                    C4.049,11,4,10.553,4,10c0-0.553,0.049-1,0.601-1H9V4.601C9,4.048,9.447,4,10,4c0.553,0,1,0.048,1,0.601V9h4.399
                                    C15.952,9,16,9.447,16,10z"
            />
        </svg>
    </button>
</AuthenticatedLayout>
