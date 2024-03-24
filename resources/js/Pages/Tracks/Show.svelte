<script>
    // Layout importieren
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    // UI-Komponenten importieren
    import Modal from "@/Components/Modal.svelte";
    import DangerButton from "@/Components/DangerButton.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    // Icon importieren
    import { ArrowLeftOutline } from "flowbite-svelte-icons";
    // Funktion für Netzwerk-Requests importieren
    import { router } from "@inertiajs/svelte";
    import { FloatingLabelInput } from "flowbite-svelte";
    import { useForm, inertia } from "@inertiajs/svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { sineInOut } from "svelte/easing";
    import { fade } from "svelte/transition";
    
    export let track;
    export let auth;
    // Formular initialisieren
    let form = useForm({
        kategorie: track.kategorie,
    });

    let confirmTrackDeletionModal = false;

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
        class="rounded-full w-12 h-12 fixed top-4 left-4 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-white shadow-md hover:shadow-lg transition ease-in duration-200 focus:outline-none"
        on:click={() => router.visit(route("tracks.index"))}
    >
        <ArrowLeftOutline size="xl" />
    </button>
    <h1 class="text-2xl text-center py-10 font-semibold">{track.title}</h1>

    <div class="flex flex-col gap-2 justify-start items-center">
        <button
            type="button"
            class="w-1/2 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
            on:click={() => (confirmTrackDeletionModal = true)}
        >
            Löschen
        </button>

        <button
            type="button"
            class="w-1/2 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
            on:click={router.visit(route("tracks.edit", { track }))}
        >
            Bearbeiten
        </button>

        <div>
            <FloatingLabelInput
                style="outlined"
                id="kategorie"
                type="text"
                required
                bind:value={$form.kategorie}
                autofocus
                autocomplete="Kategorie"
            >
                Kategorie
            </FloatingLabelInput>
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton disabled={$form.processing}>Speichern</PrimaryButton>

            {#if $form.recentlySuccessful}
                <p
                    transition:fade={{ easing: sineInOut }}
                    class="text-sm text-gray-600"
                >
                    Gespeichert.
                </p>
            {/if}
        </div>
    </div>

    <!-- Delete-Modal -->
    <Modal bind:open={confirmTrackDeletionModal} on:close={closeModal}>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Möchten Sie die Route "{track.title}" wirklich löschen?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Wird die Route gelöscht, werden auch alle dazugehörigen Daten dauerhaft entfernt.
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
