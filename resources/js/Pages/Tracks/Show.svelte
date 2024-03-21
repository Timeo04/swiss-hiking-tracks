<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    // UI-Komponenten importieren
    import Modal from "@/Components/Modal.svelte";
    import DangerButton from "@/Components/DangerButton.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    // Icon importieren
    import {
        AngleUpOutline,
        ArrowDownOutline,
        ArrowLeftOutline,
        ArrowUpOutline,
        PlusSolid,
    } from "flowbite-svelte-icons";
    import { router } from "@inertiajs/svelte";
    // Funktion zum Berechnen der Länge importieren
    import {
        calculateLength,
        calculateAscent,
        calculateDescent,
        calculateHikingTime,
    } from "@/utils/geojson/linestring";
    import ElevationChart from "@/Components/Tracks/ElevationChart.svelte";
    import Map from "@/Components/Tracks/Map.svelte";
    import ImageSwiper from "@/Components/Tracks/ImageSwiper.svelte";
    import InformationsSwiper from "@/Components/Tracks/InformationsSwiper.svelte";

    export let track;
    export let auth;
    export let images;

    let confirmTrackDeletionModal = false;

    let distance = calculateLength(track.geojson);
    let ascent = calculateAscent(track.geojson);
    let descent = calculateDescent(track.geojson);
    let hikingTime = calculateHikingTime(track.geojson, 4.2);

    // Modal schliessen
    function closeModal() {
        confirmTrackDeletionModal = false;
    }
</script>

<svelte:head>
    <title>{track.title}</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <!-- Go back to Index.svelte-Page -->
    <!-- svelte-ignore missing-declaration -->
    <button
        class="z-30 fixed rounded-full w-12 h-12 top-4 left-4 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-white shadow-md hover:shadow-lg transition ease-in duration-200 focus:outline-none"
        on:click={() => router.visit(route("tracks.index"))}
    >
        <ArrowLeftOutline size="xl" />
    </button>

    <!-- spacer -->
    <div class="h-[calc(100vh-225px)] w-full"></div>

    <!-- Background -->
    {#if images.length > 0}
        <img
            class="fixed top-0 left-0 z-0 object-cover w-screen h-screen"
            src={images[0].url}
            alt={track.title}
        />
    {/if}

    <!-- Title -->
    <div class="fixed z-10 top-0 left-0 w-full bg-none">
        <h1 class="text-2xl text-center py-16 font-semibold">{track.title}</h1>
    </div>

    <div
        class="p-2 pt-4 bg-white rounded-t-[20px] w-full h-fit
        flex flex-col justify-center items-stretch gap-6 z-20
        snap-start"
    >
        <div>
            <div class="flex items-center justify-center mb-2">
                <AngleUpOutline size="xl" class="text-primary-700" />
            </div>
            <!-- Track info, StartLocation, EndLocation, Length, estimatedDuration, HeightDifference -->
            <div class="w-full h-20 grid grid-cols-3">
                <!-- <div class="w-full h-20 flex flex-col justify-around "> -->
                <!-- <div class="flex justify-around "> -->
                <p class="text-gray-500 m-auto">
                    {track.starting_location != null
                        ? track.starting_location
                        : ""}
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
                <p class="text-gray-500 m-auto">
                    {Math.round(distance / 10) / 100} km
                </p>
                <p class="m-auto text-gray-500">
                    {#if hikingTime < 60}
                        {Math.round(hikingTime)} min
                    {:else}
                        {Math.floor(hikingTime / 60)} h {Math.round(
                            hikingTime % 60,
                        )} min
                    {/if}
                </p>
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
            <ElevationChart {track} />
        </div>

        <!-- AddInfo Map, Safety, Weather -->

        <!-- <Map {track} /> -->
        <InformationsSwiper {track} />

        <!-- AddInfo Images, Comments, AddCommentOrImage -->
        <!-- <Carousel {route to components} let:Indicators>
            <Indicators />
        </Carousel> -->

        <!-- Platzhalter -->

        <ImageSwiper {images} {track} />

        <!-- Tags -->
        <!-- Display Tags -->
        <!-- AddTags -->

        <!-- ShareButton -->

        <!-- DeleteButton -->

        <div
            class="flex flex-col gap-2 justify-start items-stretch md:items-center"
        >
            <button
                type="button"
                class="w-full bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                on:click={() => (confirmTrackDeletionModal = true)}
            >
                Löschen
            </button>

            <!-- svelte-ignore missing-declaration -->
            <button
                type="button"
                class="w-full bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                on:click={router.visit(route("tracks.edit", { track }))}
            >
                Bearbeiten
            </button>
        </div>

        <!-- Platzhalter -->
        <div class="w-full h-12"></div>
    </div>
    <!-- Delete-Modal -->
    <Modal bind:open={confirmTrackDeletionModal} on:close={closeModal}>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Möchten Sie die Route "{track.title}" wirklich löschen?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Wird die Route gelöscht, werden auch alle dazugehörigen Daten
                dauerhaft entfernt.
            </p>

            <div class="mt-6 flex justify-end">
                <!-- Close Modal -->
                <SecondaryButton on:click={closeModal}
                    >Abbrechen</SecondaryButton
                >

                <!-- Delete Track -->
                <!-- svelte-ignore missing-declaration -->
                <DangerButton
                    className="ms-3"
                    on:click={router.delete(route("tracks.destroy", { track }))}
                >
                    Route löschen
                </DangerButton>
            </div>
        </div>
    </Modal>
</AuthenticatedLayout>
