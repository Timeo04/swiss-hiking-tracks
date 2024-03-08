<script>
    import InputError from "@/Components/InputError.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import { useForm } from "@inertiajs/svelte";
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
        $form.put(route("password.update"), {
            preserveScroll: true,
            onSuccess: () => $form.reset(),
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
        <h2 class="text-lg font-medium text-gray-900">Update Password</h2>

        <p class="mt-1 text-sm text-gray-600">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form on:submit|preventDefault={updatePassword} class="mt-6 space-y-6">
        <div>
            <InputLabel forValue="current_password" value="Current Password" />

            <TextInput
                id="current_password"
                bind:this={currentPasswordInput}
                bind:value={$form.current_password}
                type="password"
                className="mt-1 block w-full"
                autocomplete="current-password"
            />

            {#if $form.errors.current_password}
                <InputError
                    message={$form.errors.current_password}
                    className="mt-2"
                />
            {/if}
        </div>

        <div>
            <InputLabel forValue="password" value="New Password" />

            <TextInput
                id="password"
                bind:this={passwordInput}
                bind:value={$form.password}
                type="password"
                className="mt-1 block w-full"
                autocomplete="new-password"
            />

            {#if $form.errors.password}
                <InputError message={$form.errors.password} className="mt-2" />
            {/if}
        </div>

        <div>
            <InputLabel
                forValue="password_confirmation"
                value="Confirm Password"
            />

            <TextInput
                id="password_confirmation"
                bind:value={$form.password_confirmation}
                type="password"
                className="mt-1 block w-full"
                autocomplete="new-password"
            />

            {#if $form.errors.password_confirmation}
                <InputError
                    message={$form.errors.password_confirmation}
                    className="mt-2"
                />
            {/if}
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton disabled={$form.processing}>Save</PrimaryButton>

            {#if $form.recentlySuccessful}
                <p
                    transition:fade={{ easing: sineInOut }}
                    class="text-sm text-gray-600"
                >
                    Saved.
                </p>
            {/if}
        </div>
    </form>
</section>
