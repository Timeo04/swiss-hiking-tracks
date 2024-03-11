<script>
    // Layout importieren
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    // UI-Komponenten importieren
    import InputError from "@/Components/InputError.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { FloatingLabelInput } from "flowbite-svelte";
    // Funktion für Formular-Netzwerk-Requests importieren
    import { useForm } from "@inertiajs/svelte";
    
    export let email;
    export let token;

    // Formular initialisieren
    let form = useForm({
        token: token,
        email: email,
        password: "",
        password_confirmation: "",
    });

    // Funktion, die aufgerufen wird, wenn das Formular abgeschickt wird
    function submit() {
        // Netzwerk-Request an den Server senden
        $form.post(route("password.store"), {
            // Passwort-Felder nach dem Abschicken leeren
            onFinish: () => $form.reset("password", "password_confirmation"),
        });
    }
</script>

<svelte:head>
    <title>Passwort zurücksetzen</title>
</svelte:head>

<GuestLayout>
    <form on:submit|preventDefault={submit}>
        <div>
            <FloatingLabelInput
                style="outlined"
                id="email"
                name="email"
                type="email"
                required
                bind:value={$form.email}
                autofocus
                autocomplete="username"
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
                type="password"
                required
                bind:value={$form.password}
                autocomplete="new-password"
            >
                Passwort
            </FloatingLabelInput>
            {#if $form.errors.password}
                <InputError className="mt-2" message={$form.errors.password} />
            {/if}
        </div>

        <div class="mt-4">
            <FloatingLabelInput
                style="outlined"
                id="password_confirmation"
                type="password"
                required
                bind:value={$form.password_confirmation}
                autocomplete="new-password"
            >
                Passwort bestätigen
            </FloatingLabelInput>
            {#if $form.errors.password_confirmation}
                <InputError
                    className="mt-2"
                    message={$form.errors.password_confirmation}
                />
            {/if}
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton
                className={$form.processing ? "opacity-25" : ""}
                disabled={$form.processing}
            >
                Passwort zurücksetzen
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
