<script>
    import DangerButton from "@/Components/DangerButton.svelte";
    import InputError from "@/Components/InputError.svelte";
    import Modal from "@/Components/Modal.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    import { useForm } from "@inertiajs/svelte";
    import { FloatingLabelInput } from "flowbite-svelte";

    export let className = "";

    let confirmUserDeletionModal = false;
    let passwordInput = null;

    let form = useForm({
        password: "",
    });

    function deleteUser() {
        // DELETE-Request an die Route "profile.destroy" senden
        $form.delete(route("profile.destroy"), {
            preserveScroll: true,
            onSuccess: () => closeModal(), // Bei Erfolg Modal schließen
            onError: () => passwordInput.input.focus(), // Bei Fehler Passwort-Feld fokussieren
            onFinish: () => $form.reset(),
        });
    }

    function closeModal() {
        confirmUserDeletionModal = false;

        $form.reset();
    }
</script>

<section class="space-y-6 {className}">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Account löschen</h2>

        <p class="mt-1 text-sm text-gray-600">
            Wenn Sie Ihr Konto löschen, werden alle Ressourcen und Daten
            dauerhaft gelöscht. Bevor Sie Ihr Konto löschen, laden Sie bitte
            alle Daten oder Informationen herunter, die Sie behalten möchten.
        </p>
    </header>

    <!-- Button, um Modal zu öffnen -->
    <DangerButton on:click={() => (confirmUserDeletionModal = true)}
        >Account löschen</DangerButton
    >

    <!-- Modal, um User-Account zu löschen -->
    <Modal bind:open={confirmUserDeletionModal} on:close={closeModal}>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Sind Sie sicher, dass Sie Ihr Konto löschen möchten?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Wenn Ihr Konto gelöscht wird, werden alle Ressourcen und Daten
                dauerhaft gelöscht. Bitte geben Sie Ihr Passwort ein, um zu
                bestätigen, dass Sie Ihr Konto dauerhaft löschen möchten.
            </p>

            <div class="mt-6">
                <FloatingLabelInput
                    style="outlined"
                    id="password"
                    type="password"
                    required
                    bind:value={$form.password}
                    bind:this={passwordInput}
                    autocomplete="current-password"
                    autofocus
                >
                    Passwort
                </FloatingLabelInput>

                {#if $form.errors.password}
                    <InputError
                        message={$form.errors.password}
                        className="mt-2"
                    />
                {/if}
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton on:click={closeModal}
                    >Abbrechen</SecondaryButton
                >

                <!-- Bei Klick Lösch-Funktion aufrufen -->
                <DangerButton
                    className="ms-3 {$form.processing ? 'opacity-25' : ''}"
                    disabled={$form.processing}
                    on:click={deleteUser}
                >
                    Account löschen
                </DangerButton>
            </div>
        </div>
    </Modal>
</section>
