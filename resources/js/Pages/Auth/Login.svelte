<script>
    import Checkbox from "@/Components/Checkbox.svelte";
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    import InputError from "@/Components/InputError.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import { inertia, useForm } from "@inertiajs/svelte";

    export let status;
    export let canResetPassword;

    let form = useForm({
        email: "",
        password: "",
        remember: false,
    });

    function submit() {
        $form.post(route("login"), {
            onFinish: () => $form.reset("password"),
        });
    }
</script>

<svelte:head>
    <title>Log in</title>
</svelte:head>

<GuestLayout>
    {#if status}
        <div class="mb-4 font-medium text-sm text-green-600">
            {status}
        </div>
    {/if}

    <form on:submit|preventDefault={submit}>
        <div>
            <InputLabel forValue="email" value="Email" />

            <TextInput
                bind:value={$form.email}
                id="email"
                type="email"
                className="mt-1 block w-full"
                required={true}
                autofocus={true}
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
                required={true}
                autocomplete="current-password"
                bind:value={$form.password}
            />

            {#if $form.errors.password}
                <InputError className="mt-2" message={$form.errors.password} />
            {/if}
        </div>

        <div class="block mt-4">
            <label for="remember" class="flex items-center">
                <Checkbox id="remember" name="remember" bind:checked={$form.remember} />
                <span class="ms-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex flex-row items-center justify-end mt-4">
            {#if canResetPassword}
                <a
                    use:inertia
                    href={route("password.request")}
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >
                    Forgot your password?
                </a>
            {/if}

            <PrimaryButton
                className="ms-4 {$form.processing ? 'opacity-25' : ''}"
                disabled={$form.processing}
            >
                Log in
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
