<script>
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    import InputError from "@/Components/InputError.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import { useForm } from "@inertiajs/svelte";

    export let email;
    export let token;

    // const props = defineProps({
    //     email: {
    //         type: String,
    //         required: true,
    //     },
    //     token: {
    //         type: String,
    //         required: true,
    //     },
    // });

    let form = useForm({
        token: token,
        email: email,
        password: "",
        password_confirmation: "",
    });

    function submit() {
        $form.post(route("password.store"), {
            onFinish: () => $form.reset("password", "password_confirmation"),
        });
    }
</script>

<svelte:head>
    <title>Reset Password</title>
</svelte:head>

<GuestLayout>
    <form on:submit|preventDefault={submit}>
        <div>
            <InputLabel forValue="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                className="mt-1 block w-full"
                bind:value={$form.email}
                required
                autofocus
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
                bind:value={$form.password_confirmation}
                required
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
            <PrimaryButton
                className={$form.processing ? "opacity-25" : ""}
                disabled={$form.processing}
            >
                Reset Password
            </PrimaryButton>
        </div>
    </form>
</GuestLayout>
