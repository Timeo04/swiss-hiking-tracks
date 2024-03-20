<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    // UI-Komponenten importieren
    import Modal from "@/Components/Modal.svelte";
    import DangerButton from "@/Components/DangerButton.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    import { Carousel } from "flowbite-svelte";
    // Icon importieren
    import {
        ArrowDownOutline,
        ArrowLeftOutline,
        ArrowUpOutline,
        PlusSolid,
    } from "flowbite-svelte-icons";
    // Funktion für Netzwerk-Requests importieren
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

    // Import Swiper Svelte components
    import { register } from "swiper/element/bundle";

    register();

    export let track;
    export let auth;
    export let images;
    export let main_image;

    let confirmTrackDeletionModal = false;

    let distance = calculateLength(track.geojson);
    let ascent = calculateAscent(track.geojson);
    let descent = calculateDescent(track.geojson);
    let hikingTime = calculateHikingTime(track.geojson, 4.2);

    // Modal schliessen
    function closeModal() {
        confirmTrackDeletionModal = false;
    }

    let imageFiles = null;
    let imageForm;
    async function submitImage() {
        if (imageFiles == null) {
            return;
        }
        console.log(imageFiles[0]);
        router.post(
            route("tracks.storeImage", track),
            {
                image: imageFiles[0],
            },
            {
                forceFormData: true,
                preserveScroll: true,
            },
        );
    }
</script>

<svelte:head>
    <title>{track.title}</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <div class="fixed top-0 left-0 w-full bg-white">
        <h1 class="text-2xl text-center py-10 font-semibold">{track.title}</h1>
    </div>
    <!-- Go back to Index.svelte-Page -->
    <!-- svelte-ignore missing-declaration -->
    <button
        class="fixed rounded-full w-12 h-12 top-4 left-4 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-white shadow-md hover:shadow-lg transition ease-in duration-200 focus:outline-none"
        on:click={() => router.visit(route("tracks.index"))}
    >
        <ArrowLeftOutline size="xl" />
    </button>

    <!-- spacer -->
    <div class="h-96 w-full bg-white"></div>
    <div class="h-80 w-full bg-white"></div>

    <div class="w-full h-fit flex flex-col justify-center items-center gap-6">
        <!-- Track info, StartLocation, EndLocation, Length, estimatedDuration, HeightDifference -->
        <div class="w-full h-20 grid grid-cols-3">
            <!-- <div class="w-full h-20 flex flex-col justify-around "> -->
            <!-- <div class="flex justify-around "> -->
            <p class="text-gray-500 m-auto">
                {track.starting_location != null ? track.starting_location : ""}
            </p>
            <div class="m-auto">
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

        <div class="flex flex-col gap-2 justify-start items-center">
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

        <div class="w-full">
            <ElevationChart {track} />
        </div>

        <!-- AddInfo Map, Safety, Weather -->
        <!-- <Carousel {route to components} let:Indicators>
            <Indicators />
        </Carousel> -->

        <Map {track} />

        <!-- AddInfo Images, Comments, AddCommentOrImage -->
        <!-- <Carousel {route to components} let:Indicators>
            <Indicators />
        </Carousel> -->

        <!-- Platzhalter -->
        <swiper-container
            class="mySwiper"
            space-between="10"
            pagination="true"
            observer="true"
        >
            {#each images as image}
                <swiper-slide class="h-[500px]">
                    <!-- <div class="h-[500px] rounded-xl"> -->
                    <img src={image} alt="Bild" class="w-full rounded-xl" />
                    <!-- </div> -->
                </swiper-slide>
            {/each}
            <swiper-slide>
                <div
                    class="flex justify-stretch items-stretch w-full rounded-xl bg-gray-200 h-[500px]"
                >
                    <button
                        on:click={() =>
                            imageForm.querySelector("input").click()}
                        class="p-20 w-full h-full flex-col gap-2 flex justify-center items-center"
                    >
                        <PlusSolid size="xl" />
                        <p>Bild hinzufügen</p>
                    </button>
                </div>

                <form
                    on:submit|preventDefault={submitImage}
                    bind:this={imageForm}
                    class="hidden"
                >
                    <input
                        on:input={function () {
                            // console.log(this.files[0]);
                            imageFiles = this.files;
                            submitImage();
                        }}
                        bind:files={imageFiles}
                        type="file"
                        accept="image/*"
                    />
                </form>
            </swiper-slide>
        </swiper-container>

        <!-- Tags -->
        <!-- Display Tags -->
        <!-- AddTags -->

        <!-- ShareButton -->

        <!-- DeleteButton -->
        <button
            type="button"
            class=" w-10/12 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
            on:click={() => (confirmTrackDeletionModal = true)}
        >
            Löschen
        </button>

        <!-- Platzhalter -->
        <div class="w-full h-48"></div>
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

<style>
    swiper-container {
        width: 100%;
        height: 100%;
    }

    swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
