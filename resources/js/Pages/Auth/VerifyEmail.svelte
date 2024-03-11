<script>
    // Layout importieren
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    // UI-Komponenten importieren
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    // Funktionen für Netzwerk-Requests importieren
    import { useForm, inertia } from "@inertiajs/svelte";

    export let status;

    // Formular initialisieren
    let form = useForm({});

    // Funktion, die aufgerufen wird, wenn das Formular abgeschickt wird
    function submit() {
        // Netzwerk-Request an den Server senden
        $form.post(route("verification.send"));
    }

    const verificationLinkSent = status === "verification-link-sent";
</script>

<svelte:head>
    <title>E-Mail-Bestätigung</title>
</svelte:head>

<GuestLayout>
    <div class="mb-4 text-sm text-gray-600">
        Danke für die Anmeldung! Bevor Sie beginnen, können Sie Ihre
        E-Mail-Adresse überprüfen, indem Sie auf den Link klicken, den wir Ihnen
        gerade per E-Mail gesendet haben. Wenn Sie die E-Mail nicht erhalten
        haben, senden wir Ihnen gerne eine weitere.
    </div>

    {#if verificationLinkSent}
        <div class="mb-4 font-medium text-sm text-green-600">
            Ein neuer Bestätigungslink wurde an die E-Mail-Adresse gesendet, die
            Sie während der Registrierung angegeben haben.
        </div>
    {/if}

    <form on:submit|preventDefault={submit}>
        <div class="mt-4 flex items-center justify-between">
            <PrimaryButton
                className={$form.processing ? "opacity-25" : ""}
                disabled={$form.processing}
            >
                E-Mail-Bestätigungslink erneut senden
            </PrimaryButton>

            <!-- svelte-ignore missing-declaration -->
            <button
                use:inertia={{ href: route("logout"), method: "post" }}
                type="button"
            >
                Abmelden
            </button>
        </div>
    </form>
</GuestLayout>
