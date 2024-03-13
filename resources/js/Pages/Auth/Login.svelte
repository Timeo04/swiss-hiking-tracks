<script>
    // Layout importieren
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    // UI-Komponenten importieren
    import Checkbox from "@/Components/Checkbox.svelte";
    import InputError from "@/Components/InputError.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { FloatingLabelInput } from "flowbite-svelte";
    // Funktion für Formular-Netzwerk-Requests importieren
    import { inertia, useForm } from "@inertiajs/svelte";

    export let status;
    export let canResetPassword;

    // Formular initialisieren
    let form = useForm({
        email: "",
        password: "",
        remember: false,
    });

    // Funktion, die aufgerufen wird, wenn das Formular abgeschickt wird
    function submit() {
        // Netzwerk-Request an den Server senden
        $form.post(route("login"), {
            // Passwort-Feld nach dem Abschicken leeren
            onFinish: () => $form.reset("password"),
        });
    }
</script>

<svelte:head>
    <title>Anmelden</title>
</svelte:head>

<GuestLayout>
    <!-- Status-Meldung anzeigen, falls vorhanden -->
    {#if status}
        <div class="mb-4 font-medium text-sm text-green-600">
            {status}
        </div>
    {/if}

    <form on:submit|preventDefault={submit}>
        <div>
            <FloatingLabelInput
                style="outlined"
                id="email"
                name="email"
                type="email"
                required
                bind:value={$form.email}
                autocomplete="username"
                autofocus={true}
            >
                E-Mail
            </FloatingLabelInput>
            {#if $form.errors.email}
                <InputError className="mt-2" message={$form.errors.email} />
            {/if}
        </div>

        <div class="mt-4">
            <FloatingLabelInput
                style="outlined"
                id="password"
                name="password"
                type="password"
                required
                bind:value={$form.password}
                autocomplete="current-password"
            >
                Passwort
            </FloatingLabelInput>
            {#if $form.errors.password}
                <InputError className="mt-2" message={$form.errors.password} />
            {/if}
        </div>

        <div class="block mt-4">
            <label for="remember" class="flex items-center">
                <Checkbox
                    id="remember"
                    name="remember"
                    bind:checked={$form.remember}
                />
                <span class="ms-2 text-sm text-gray-600"
                    >angemeldet bleiben</span
                >
            </label>
        </div>

        <div class="flex flex-row items-center justify-end mt-4">
            {#if canResetPassword}
                <!-- svelte-ignore missing-declaration -->
                <a
                    use:inertia
                    href={route("password.request")}
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    Passwort vergessen?
                </a>
            {/if}

            <PrimaryButton
                className="ms-4 {$form.processing ? 'opacity-25' : ''}"
                disabled={$form.processing}
            >
                anmelden
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
