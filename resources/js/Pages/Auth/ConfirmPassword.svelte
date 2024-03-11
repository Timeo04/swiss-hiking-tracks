<script>
    // Gast-Layout importieren
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    // UI-Komponenten importieren
    import InputError from "@/Components/InputError.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { FloatingLabelInput } from "flowbite-svelte";
    // Funktion für Formular-Netzwerk-Requests importieren
    import { useForm } from "@inertiajs/svelte";

    // Formular initialisieren
    let form = useForm({
        password: "",
    });

    // Funktion, die aufgerufen wird, wenn das Formular abgeschickt wird
    function submit() {
        // Netzwerk-Request an den Server senden
        $form.post(route("password.confirm"), {
            onFinish: () => $form.reset(),
        });
    }
</script>

<svelte:head>
    <title>Passwort bestätigen</title>
</svelte:head>

<GuestLayout>
    <div class="mb-4 text-sm text-gray-600">
        Dies ist ein sicherer Bereich der Anwendung. Bitte bestätigen Sie Ihr
        Passwort, bevor Sie fortfahren.
    </div>

    <form on:submit|preventDefault={submit}>
        <div>
            <FloatingLabelInput
                style="outlined"
                id="password"
                name="password"
                type="password"
                required
                bind:value={$form.password}
                autofocus
                autocomplete="current-password"
            >
                Passwort
            </FloatingLabelInput>
            {#if $form.errors.password}
                <InputError className="mt-2" message={$form.errors.password} />
            {/if}
        </div>

        <div class="flex justify-end mt-4">
            <PrimaryButton
                className="ms-4 {$form.processing ? 'opacity-25' : ''}"
                disabled={$form.processing}
            >
                Bestätigen
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
