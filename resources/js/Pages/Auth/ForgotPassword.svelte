<script>
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    import InputError from "@/Components/InputError.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { useForm } from "@inertiajs/svelte";
    import { FloatingLabelInput } from "flowbite-svelte";

    export let status;

    let form = useForm({
        email: "",
    });

    function submit() {
        $form.post(route("password.email"));
    }
</script>

<svelte:head>
    <title>Passwort vergessen</title>
</svelte:head>

<GuestLayout>
    <div class="mb-4 text-sm text-gray-600">
        Passwort vergessen? Kein Problem. Lassen Sie uns einfach Ihre
        E-Mail-Adresse wissen und wir senden Ihnen einen Link zum Zurücksetzen
        des Passworts, mit dem Sie ein neues wählen können.
    </div>

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
                autofocus
                autocomplete="username"
            >
                E-Mail
            </FloatingLabelInput>
            {#if $form.errors.email}
                <InputError className="mt-2" message={$form.errors.email} />
            {/if}
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton
                className={$form.processing ? "opacity-25" : ""}
                disabled={$form.processing}
            >
                Passwort Reset-Link zusenden
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
