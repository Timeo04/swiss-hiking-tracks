<script>
    import InputError from "@/Components/InputError.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { useForm } from "@inertiajs/svelte";
    import { FloatingLabelInput } from "flowbite-svelte";
    import { sineInOut } from "svelte/easing";
    import { fade } from "svelte/transition";

    export let className = "";

    let passwordInput = null;
    let currentPasswordInput = null;

    let form = useForm({
        current_password: "",
        password: "",
        password_confirmation: "",
    });

    function updatePassword() {
        // PUT-Request an die Route "password.update" senden
        $form.put(route("password.update"), {
            preserveScroll: true,
            onSuccess: () => $form.reset(), // Bei Erfolg Formular zurücksetzen
            onError: () => {
                if ($form.errors.password) {
                    $form.reset("password", "password_confirmation");
                    passwordInput.input.focus();
                }
                if ($form.errors.current_password) {
                    $form.reset("current_password");
                    currentPasswordInput.input.focus();
                }
            },
        });
    }
</script>

<section class={className}>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Passwort ändern</h2>

        <p class="mt-1 text-sm text-gray-600">
            Stellen Sie sicher, dass Ihr Konto ein langes, zufälliges Passwort
            verwendet, um sicher zu bleiben.
        </p>
    </header>

    <!-- Um Passwort zu ändern altes Passwort und neues Passwort abfragen -->
    <form on:submit|preventDefault={updatePassword} class="mt-6 space-y-6">
        <div>
            <FloatingLabelInput
                style="outlined"
                id="current_password"
                type="password"
                required
                bind:value={$form.current_password}
                bind:this={currentPasswordInput}
                autocomplete="current-password"
            >
                Aktuelles Passwort
            </FloatingLabelInput>

            {#if $form.errors.current_password}
                <InputError
                    message={$form.errors.current_password}
                    className="mt-2"
                />
            {/if}
        </div>

        <div>
            <FloatingLabelInput
                style="outlined"
                id="password"
                type="password"
                required
                bind:this={passwordInput}
                bind:value={$form.password}
                autocomplete="new-password"
            >
                Neues Passwort
            </FloatingLabelInput>

            {#if $form.errors.password}
                <InputError message={$form.errors.password} className="mt-2" />
            {/if}
        </div>

        <div>
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
                    message={$form.errors.password_confirmation}
                    className="mt-2"
                />
            {/if}
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
    </form>
</section>
