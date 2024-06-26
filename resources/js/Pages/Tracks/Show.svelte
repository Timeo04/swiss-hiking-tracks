<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import ShareLayout from "@/Layouts/ShareLayout.svelte";
    // UI-Komponenten importieren
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { FloatingLabelInput, Badge } from "flowbite-svelte";
    // Transitions importieren
    import { sineInOut } from "svelte/easing";
    import { fade } from "svelte/transition";
    // Icons importieren
    import {
        AngleUpOutline,
        ArrowDownOutline,
        ArrowLeftOutline,
        ArrowUpOutline,
    } from "flowbite-svelte-icons";
    // Funktionen für Netzwerk-Requests importieren
    import { router, useForm } from "@inertiajs/svelte";
    // Funktion zum Berechnen der Länge importieren
    import {
        calculateLength,
        calculateAscent,
        calculateDescent,
        calculateHikingTime,
    } from "@/utils/geojson/linestring";
    // Komponenten importieren
    import ElevationChart from "@/Components/Tracks/ElevationChart.svelte";
    import ImageSwiper from "@/Components/Tracks/ImageSwiper.svelte";
    import InformationsSwiper from "@/Components/Tracks/InformationsSwiper.svelte";
    import ShareModal from "@/Pages/Tracks/Partials/ShareModal.svelte";
    import Map from "@/Components/Tracks/Map.svelte";
    import DeleteModal from "./Partials/DeleteModal.svelte";

    // Übergabewerte initialisieren
    export let track;
    export let auth = null;
    export let images;
    export let shared = false;

    // Formular für Tags initialisieren
    let form = useForm({
        name: "",
    });

    // Layout bestimmen (geteilt oder eingeloggt)
    let layout = shared ? ShareLayout : AuthenticatedLayout;

    // Variablen für Modals
    let confirmTrackDeletionModal = false;
    let shareModal = false;

    // Länge, Aufstieg, Abstieg und Wanderzeit berechnen
    let distance = calculateLength(track.geojson);
    let ascent = calculateAscent(track.geojson);
    let descent = calculateDescent(track.geojson);
    let hikingTime = calculateHikingTime(
        track.geojson,
        auth != null && auth.user != null ? auth.user.hiking_speed || 4.2 : 4.2, // Wandergeschwindigkeit des Benutzers oder 4.2 km/h
    );
</script>

<svelte:head>
    <title>{track.title}</title>
</svelte:head>

<!-- Layout abhängig von eingeloggt oder geteilt -->
<svelte:component this={layout} {auth} className="px-0">
    {#if !shared}
        <!-- Go back to Index.svelte-Page -->
        <!-- svelte-ignore missing-declaration -->
        <button
            class="z-30 fixed rounded-full w-12 h-12 top-4 left-4 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-white shadow-md hover:shadow-lg transition ease-in duration-200 focus:outline-none"
            on:click={() => router.visit(route("tracks.index"))}
        >
            <ArrowLeftOutline size="xl" />
        </button>
    {/if}
    <!-- spacer -->
    <div class="h-[calc(100vh-225px)] w-full"></div>

    <!-- Background -->
    {#if images.length > 0}
        <!-- Erstes Bild falls Bilder vorhanden -->
        <img
            class="fixed top-0 left-0 z-0 object-cover w-screen h-screen"
            src={images[0].url}
            alt={track.title}
        />
    {:else}
        <!-- Nicht interaktives Luftbild falls keine Bilder vorhanden -->
        <div class="fixed top-0 left-0 z-0 object-cover w-screen h-screen">
            <Map
                tracks={[track]}
                imageryBaseMap={true}
                interactiveMap={false}
            />
        </div>
    {/if}

    <!-- Title -->
    <div class="fixed z-10 top-0 left-0 w-full bg-none">
        <h1 class="text-2xl text-center py-16 font-semibold text-white">
            {track.title}
        </h1>
    </div>

    <div
        class="p-2 pt-4 bg-white rounded-t-[20px] w-full h-fit
        flex flex-col justify-center items-stretch gap-6 z-20"
    >
        <div>
            <div class="flex items-center justify-center mb-2">
                <AngleUpOutline size="xl" class="text-primary-700" />
            </div>
            <!-- Track info, StartLocation, EndLocation, Length, estimatedDuration, HeightDifference -->
            <div class="w-full h-20 grid grid-cols-3">
                <!-- Startort -->
                <p class="text-gray-500 m-auto">
                    {track.starting_location != null
                        ? track.starting_location
                        : ""}
                </p>
                <!-- Arrow left to right -->
                <div class="m-auto">
                    {#if track.starting_location != null || track.destination_location != null}
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
                    {/if}
                </div>
                <!-- Zielort -->
                <p class="text-gray-500 m-auto">
                    {track.destination_location != null
                        ? track.destination_location
                        : ""}
                </p>
                <!-- Distanz -->
                <p class="text-gray-500 m-auto">
                    {Math.round(distance / 10) / 100} km
                </p>
                <!-- Wanderzeit -->
                <p class="m-auto text-gray-500">
                    {#if hikingTime < 60}
                        {Math.round(hikingTime)} min
                    {:else}
                        {Math.floor(hikingTime / 60)} h {Math.round(
                            hikingTime % 60,
                        )} min
                    {/if}
                </p>
                <!-- Höhendifferenzen -->
                <p class="m-auto text-gray-500">
                    <span>
                        <ArrowUpOutline
                            size="sm"
                            class="inline-block"
                        />{Math.round(ascent)} m
                    </span><span>/</span><span>
                        <ArrowDownOutline
                            size="sm"
                            class="inline-block"
                        />{Math.round(descent)} m
                    </span>
                </p>
            </div>
        </div>

        <div class="w-full">
            <!-- Höhendiagramm -->
            <ElevationChart {track} />
        </div>

        <!-- Zusatzinformationen-Komponente (Karte, Wetter, Sicherheit) -->
        <InformationsSwiper {track} {shared} />

        <!-- Bilder-Komponente -->
        <ImageSwiper {images} {track} {shared} />

        <!-- Tags -->
        <div
            class="flex flex-row items-center justify-start self-stretch gap-2"
        >
            {#each track.tags as tag}
                <!-- svelte-ignore missing-declaration -->
                <Badge
                    dismissable={!shared}
                    on:close={() =>
                        router.post(
                            route("tracks.untag", { track }),
                            {
                                id: tag.id,
                            },
                            {
                                preserveScroll: true,
                            },
                        )}>{tag.name}</Badge
                >
            {/each}
        </div>

        {#if !shared}
            <!-- AddTags -->
            <!-- svelte-ignore missing-declaration -->
            <form
                on:submit|preventDefault={$form.post(
                    route("tracks.tag", { track }),
                    {
                        preserveScroll: true,
                        onSuccess: () => {
                            $form.reset();
                        },
                    },
                )}
            >
                <div class="flex flex-row gap-2 w-full pb-2 justify-stretch items-stretch">
                    <FloatingLabelInput
                        style="outlined"
                        id="kategorie"
                        type="text"
                        size="sm"
                        required
                        bind:value={$form.name}
                    >
                        Kategorie hinzufügen
                    </FloatingLabelInput>
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton disabled={$form.processing}
                        >Speichern</PrimaryButton
                    >
                    {#if $form.recentlySuccessful}
                        <p
                            transition:fade={{ easing: sineInOut }}
                            class="text-sm text-gray-600"
                        >
                            Gespeichert.
                        </p>
                    {/if}
                </div>
            </form>

            <div
                class="flex flex-col gap-2 justify-start items-stretch md:items-center"
            >
                <!-- Delete Button -->
                <button
                    type="button"
                    class="w-full bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                    on:click={() => (confirmTrackDeletionModal = true)}
                >
                    Löschen
                </button>

                <!-- Edit Button -->
                <!-- svelte-ignore missing-declaration -->
                <button
                    type="button"
                    class="w-full bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                    on:click={router.visit(route("tracks.edit", { track }))}
                >
                    Bearbeiten
                </button>

                <!-- Share Button -->
                <button
                    type="button"
                    class="w-full bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                    on:click={() => (shareModal = true)}
                >
                    Teilen
                </button>

                <!-- Open in Swisstopo Button -->
                <!-- svelte-ignore missing-declaration -->
                <button
                    type="button"
                    class="w-full bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                    on:click={() =>
                        (window.location.href = route("tracks.swisstopo", {
                            track,
                        }))}
                >
                    In SwissTopo öffnen
                </button>
            </div>
        {/if}

        <!-- Platzhalter -->
        <div class="w-full h-12"></div>
    </div>

    <!-- Delete-Modal -->
    <DeleteModal {track} bind:open={confirmTrackDeletionModal} />

    <!-- Share-Modal -->
    <ShareModal {track} bind:open={shareModal} />
</svelte:component>
