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
    } from "flowbite-svelte-icons";
    // Funktion für Netzwerk-Requests importieren
    import { router } from "@inertiajs/svelte";
    // Funktion zum Berechnen der Länge importieren
    import {
        getLength,
        calculateHeightPoints,
        calculateAscent,
        calculateDescent,
        calculateHikingTime,
    } from "@/utils/geojson/linestring";
    import { Chart, Line } from "svelte-chartjs";
    import {
        Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        LineElement,
        LinearScale,
        PointElement,
        CategoryScale,
        Filler,
    } from "chart.js";
    import colors from "tailwindcss/colors";

    ChartJS.register(
        Title,
        Tooltip,
        Legend,
        Filler,
        LineElement,
        LinearScale,
        PointElement,
        CategoryScale,
    );

    export let track;
    export let auth;

    let confirmTrackDeletionModal = false;

    let distance = getLength(track.geojson);
    let heightPoints = calculateHeightPoints(track.geojson);
    let ascent = calculateAscent(track.geojson);
    let descent = calculateDescent(track.geojson);
    let hikingTime = calculateHikingTime(track.geojson, 4.2);

    // Modal schliessen
    function closeModal() {
        confirmTrackDeletionModal = false;
    }

    ChartJS.defaults.color = colors.gray[500];
    ChartJS.defaults.font.size = 12;
    ChartJS.defaults.font.family =
        'Kanit, ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';
    // ChartJS.defaults.font.weight = "lighter";

    console.log(colors.primary);
    let distanceData = {
        datasets: [
            {
                label: "Höhe über Meer",
                data: heightPoints.map((row) => ({
                    y: row.height,
                    x: row.distance / 1000,
                })),
                fill: "start",

                backgroundColor: "#EF562F",
                borderColor: "#EF562F",
            },
        ],
    };
    let maxHeight = Math.max(...heightPoints.map((row) => row.height));
    let minHeight = Math.min(...heightPoints.map((row) => row.height));
    const distanceOptions = {
        plugins: {
            legend: {
                display: false,
            },
            tooltip: {
                enabled: false,
            },
            decimation: {
                enabled: true,
            },
        },
        scales: {
            x: {
                title: {
                    display: false, // set to true to show the title
                    text: "Distanz (km)",
                    font: {
                        weight: "bold",
                        size: 14,
                    },
                },
                type: "linear",
                ticks: {
                    callback: (value) => `${Math.round(value * 10) / 10} km`,
                },
                max: heightPoints[heightPoints.length - 1].distance / 1000,
                min: 0,
                beginAtZero: true,
            },
            y: {
                title: {
                    display: true,
                    text: "Höhe (m ü. M.)",
                    font: {
                        weight: "bold",
                        size: 14,
                    },
                },
                type: "linear",
                suggestedMax: Math.max(
                    minHeight + 150,
                    maxHeight + (maxHeight - minHeight) * 0.15,
                ),
            },
        },
        aspectRatio: 2.5,
        maintainAspectRation: false,
        responsive: true,
        datasets: {
            line: {
                pointRadius: 0, // disable for all `'line'` datasets
            },
        },
    };
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

        <div class="w-full max-h-[350px] flex justify-center items-center">
            <Line data={distanceData} options={distanceOptions} />
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
