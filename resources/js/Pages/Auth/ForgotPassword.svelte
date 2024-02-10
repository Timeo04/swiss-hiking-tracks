<script>
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    import InputError from "@/Components/InputError.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import { useForm } from "@inertiajs/svelte";

    export let status;

    let form = useForm({
        email: "",
    });

    function submit() {
        $form.post(route("password.email"));
    }
</script>

<svelte:head>
    <title>Forgot Password</title>
</svelte:head>

<GuestLayout>
    <div class="mb-4 text-sm text-gray-600">
        Forgot your password? No problem. Just let us know your email address
        and we will email you a password reset link that will allow you to
        choose a new one.
    </div>

    {#if status}
        <div class="mb-4 font-medium text-sm text-green-600">
            {status}
        </div>
    {/if}

    <form on:submit|preventDefault={submit}>
        <div>
            <InputLabel forValue="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                className="mt-1 block w-full"
                bind:value={$form.email}
                required
                autofocus={true}
                autocomplete="username"
            />

            {#if $form.errors.email}
                <InputError className="mt-2" message={$form.errors.email} />
            {/if}
        </div>

        <div class="flex items-center justify-end mt-4">
            <PrimaryButton
                className={$form.processing ? "opacity-25" : ""}
                disabled={$form.processing}
            >
                Email Password Reset Link
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
