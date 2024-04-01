<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    // Funktionen für Netzwerk-Requests importieren
    import { router, inertia } from "@inertiajs/svelte";
    import { Badge } from "flowbite-svelte";

    export let auth;
    export let tracks;

    let allTags = [];
    tracks.forEach((track) => {
        allTags.push(...track.tags);
    });
    let allTagsUnique = allTags.filter(function (tag, index) {
        return allTags.findIndex((v) => v.id == tag.id) == index;
    });
</script>

<svelte:head>
    <title>Routen</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <h1 class="py-10 text-2xl font-semibold text-center">Routen</h1>
    {#each allTagsUnique as tag}
        <Badge>{tag.name}</Badge>
    {/each}
    {#if tracks == null || tracks.length === 0}
        <p class="text-center">Keine Routen vorhanden</p>
    {:else}
        <div class="flex flex-col gap-2 p-2">
            <!-- Show all Tracks -->
            {#each tracks as track}
                <div
                    class="flex items-center justify-between p-4 gap-2 bg-white rounded-lg shadow-md"
                >
                    <div class="flex items-center">
                        <div class="ml-4">
                            <h2 class="text-xl font-semibold">{track.title}</h2>
                            <!-- Show Start- and Destination-Location if defined -->
                            <p class="text-gray-500">
                                {track.starting_location != null
                                    ? track.starting_location + " - "
                                    : ""}{track.destination_location != null
                                    ? track.destination_location
                                    : ""}
                            </p>
                            <div>
                                {#each track.tags as tag}
                                    <Badge>{tag.name}</Badge>
                                {/each}
                            </div>
                        </div>
                    </div>
                    <!-- svelte-ignore missing-declaration -->
                    <button
                        use:inertia={{ href: route("tracks.show", { track }) }}
                        class="px-4 py-2 text-white bg-primary-600 rounded-lg"
                    >
                        Öffnen
                    </button>
                </div>
            {/each}
        </div>
    {/if}
    <!-- Show Button to create new Track -->
    <!-- svelte-ignore missing-declaration -->
    <button
        on:click={() => router.visit(route("tracks.create"))}
        class="group fixed bottom-20 right-4 p-0 w-12 h-12 border-primary-600 border-[3px] rounded-full bg-white hover:bg-primary-700 active:shadow-lg mouse shadow transition ease-in duration-200 focus:outline-none"
    >
        <!-- Plus Icon -->
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
