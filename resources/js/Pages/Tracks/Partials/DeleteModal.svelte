<script>
    // Modal Component importieren
    import Modal from "@/Components/Modal.svelte";
    // UI-Komponenten importieren
    import DangerButton from "@/Components/DangerButton.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    // Funktion für Netzwerk-Requests importieren
    import { router } from "@inertiajs/svelte";

    // Variablen deklarieren
    export let open = false;
    export let track;

    // Modal schliessen
    function closeModal() {
        open = false;
    }
</script>

<!-- Delete-Modal -->
<Modal bind:open on:close={closeModal}>
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
            <SecondaryButton on:click={closeModal}>Abbrechen</SecondaryButton>

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
