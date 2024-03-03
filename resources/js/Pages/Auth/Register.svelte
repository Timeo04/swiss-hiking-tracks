<script>
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    import InputError from "@/Components/InputError.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import { inertia, useForm } from "@inertiajs/svelte";

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
    <title>Register</title>
</svelte:head>

<GuestLayout>
    <form on:submit|preventDefault={submit}>
        <div>
            <InputLabel forValue="name" value="Name" />

            <TextInput
                id="name"
                type="text"
                className="mt-1 block w-full"
                bind:value={$form.name}
                required
                autofocus
                autocomplete="name"
            />

            {#if $form.errors.name}
                <InputError className="mt-2" message={$form.errors.name} />
            {/if}
        </div>

        <div class="mt-4">
            <InputLabel forValue="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                className="mt-1 block w-full"
                bind:value={$form.email}
                required
                autocomplete="username"
            />

            {#if $form.errors.email}
                <InputError className="mt-2" message={$form.errors.email} />
            {/if}
        </div>

        <div class="mt-4">
            <InputLabel forValue="password" value="Password" />

            <TextInput
                id="password"
                type="password"
                className="mt-1 block w-full"
                bind:value={$form.password}
                required
                autocomplete="new-password"
            />

            {#if $form.errors.password}
                <InputError className="mt-2" message={$form.errors.password} />
            {/if}
        </div>

        <div class="mt-4">
            <InputLabel
                forValue="password_confirmation"
                value="Confirm Password"
            />

            <TextInput
                id="password_confirmation"
                type="password"
                className="mt-1 block w-full"
                required
                bind:value={$form.password_confirmation}
                autocomplete="new-password"
            />

            {#if $form.errors.password_confirmation}
                <InputError
                    className="mt-2"
                    message={$form.errors.password_confirmation}
                />
            {/if}
        </div>

        <div class="flex items-center justify-end mt-4">
            <a
                use:inertia
                href={route("login")}
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
                Already registered?
            </a>

            <PrimaryButton
                className="ms-4 {$form.processing ? 'opacity-25' : ''}"
                disabled={$form.processing}
            >
                Register
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
