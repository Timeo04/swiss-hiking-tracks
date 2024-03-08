<script>
    import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.svelte";
    import { router } from "@inertiajs/svelte";
    import { ArrowLeftOutline } from "flowbite-svelte-icons";
    import Modal from "@/Components/Modal.svelte";
    import DangerButton from "@/Components/DangerButton.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";

    export let track;
    export let auth;

    let confirmTrackDeletionModal = false;

    function closeModal() {
        confirmTrackDeletionModal = false;
    }
</script>

<svelte:head>
    <title>{track.title}</title>
</svelte:head>

<AuthenticatedLayout {auth}>
    <button
        class="rounded-full w-12 h-12 fixed top-4 left-4 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-white shadow-md hover:shadow-lg transition ease-in duration-200 focus:outline-none"
        on:click={() => router.visit(route("tracks.index"))}
    >
        <ArrowLeftOutline size="xl" />
    </button>
    <h1 class="text-2xl text-center py-10 font-semibold">{track.title}</h1>

    <button
        type="button"
        class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-1/2 bg-primary-700 hover:bg-primary-500 flex justify-center items-center text-black border border-red-500 bg-transparent shadow-md font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
        on:click={() => (confirmTrackDeletionModal = true)}
    >
        Löschen
    </button>

    <Modal bind:open={confirmTrackDeletionModal} on:close={closeModal}>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Möchten Sie diese Route wirklich löschen?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Sobald die Route gelöscht ist, werden alle zugehörigen Daten
                dauerhaft gelöscht.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton on:click={closeModal}
                    >Abbrechen</SecondaryButton
                >

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
