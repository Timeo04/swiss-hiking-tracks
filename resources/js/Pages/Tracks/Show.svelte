<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    // UI-Komponenten importieren
    import Modal from "@/Components/Modal.svelte";
    import DangerButton from "@/Components/DangerButton.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    import { Carousel } from "flowbite-svelte";
    // Icon importieren
    import { ArrowLeftOutline } from "flowbite-svelte-icons";
    // Funktion für Netzwerk-Requests importieren
    import { router } from "@inertiajs/svelte";
    // Funktion zum Berechnen der Länge importieren
    import { getLength } from "@/utils/geojson/linestring";

    export let track;
    export let auth;

    let confirmTrackDeletionModal = false;

    let distance = getLength(track.geojson);

    // Modal schliessen
    function closeModal() {
        confirmTrackDeletionModal = false;
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
            <p class="m-auto text-red-400">
                <!-- {track.estimated_duration != null
                        ? track.estimated_duration + " h"
                        : "? h"} -->
                3h
            </p>
            <p class="m-auto text-red-400">
                <!-- {track.height_difference != null
                        ? track.height_difference + " m"
                        : "? / ? m"} -->
                100 m / 120 m
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

        <!-- Marschzeittabelle -->
        <div class="w-full h-36">
            <img
                src="https://4.bp.blogspot.com/-wBe3cKJqqQU/WzvIQ2RZTUI/AAAAAAAACi8/ToNL9jjid_AUhjbzGh8Aya1oX3w0eNuZQCLcBGAs/s1600/Marschtabelle_E51.jpg"
                alt="Marschzeittabelle"
                class="w-full h-full"
            />
        </div>

        <!-- AddInfo Map, Safety, Weather -->
        <!-- <Carousel {route to components} let:Indicators>
            <Indicators />
        </Carousel> -->

        <!-- Platzhalter -->
        <div class="w-full h-fit">
            <img
                src="https://kstatic.googleusercontent.com/files/9b837ed639746f02c66e5f00bec26588fc91fc4689292b791a80971eac477e72f8f0b00fdb8dec996f5e6fc0cb777946d3e4c7ee4dd9238d50e2bf2922f4a808"
                alt="Marschzeittabelle"
                class="w-full h-full"
            />
        </div>

        <!-- AddInfo Images, Comments, AddCommentOrImage -->
        <!-- <Carousel {route to components} let:Indicators>
            <Indicators />
        </Carousel> -->

        <!-- Platzhalter -->
        <div class="w-full h-fit">
            <img
                src="https://4kwallpapers.com/images/wallpapers/mountain-peak-alps-1242x2208-11501.jpg"
                alt="Marschzeittabelle"
                class="w-full h-full"
            />
        </div>

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
