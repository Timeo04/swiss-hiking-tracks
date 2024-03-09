<script>
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    import InputError from "@/Components/InputError.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { inertia, useForm } from "@inertiajs/svelte";
    import { FloatingLabelInput } from "flowbite-svelte";

    let form = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    });

    function submit() {
        $form.post(route("register"), {
            onFinish: () => $form.reset("password", "password_confirmation"),
        });
    }
</script>

<svelte:head>
    <title>Registrieren</title>
</svelte:head>

<GuestLayout>
    <form on:submit|preventDefault={submit}>
        <div>
            <FloatingLabelInput
                style="outlined"
                id="name"
                name="name"
                type="text"
                required
                bind:value={$form.name}
                autofocus
                autocomplete="name"
            >
                Name
            </FloatingLabelInput>
            {#if $form.errors.name}
                <InputError className="mt-2" message={$form.errors.name} />
            {/if}
        </div>

        <div class="mt-4">
            <FloatingLabelInput
                style="outlined"
                id="email"
                name="email"
                type="email"
                required
                bind:value={$form.email}
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
                name="password"
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
                name="password_confirmation"
                type="password"
                required
                bind:value={$form.password_confirmation}
                autocomplete="new-password"
            >
                Passwort bestätigen
            </FloatingLabelInput>
            {#if $form.errors.password_confirmation}
                <InputError className="mt-2" message={$form.errors.password_confirmation} />
            {/if}
        </div>

        <div class="flex items-center justify-end mt-4">
            <!-- svelte-ignore missing-declaration -->
            <a
                use:inertia
                href={route("login")}
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
                Bereits registriert?
            </a>

            <PrimaryButton
                className="ms-4 {$form.processing ? 'opacity-25' : ''}"
                disabled={$form.processing}
            >
                Registrieren
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
