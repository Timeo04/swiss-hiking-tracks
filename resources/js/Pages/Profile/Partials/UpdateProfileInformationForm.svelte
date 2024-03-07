<script>
    import InputError from "@/Components/InputError.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import { useForm, inertia } from "@inertiajs/svelte";
    import { sineInOut } from "svelte/easing";
    import { fade } from "svelte/transition";

    export let mustVerifyEmail;
    export let status;
    export let auth;
    export let className = "";

    let user = auth.user;
    $: user = auth.user;

    let form = useForm({
        name: user.name,
        email: user.email,
    });
</script>

<section class={className}>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

        <p class="mt-1 text-sm text-gray-600">
            Update your account's profile information and email address.
        </p>
    </header>

    <form
        on:submit|preventDefault={$form.patch(route("profile.update"), {
            preserveScroll: true,
        })}
        class="mt-6 space-y-6"
    >
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

        <div>
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

        {#if mustVerifyEmail && user.email_verified_at === null}
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <!-- svelte-ignore a11y-missing-attribute -->
                    <a
                        use:inertia={{
                            href: route("verification.send"),
                            method: "post",
                            as: "button",
                        }}
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        Click here to re-send the verification email.
                    </a>
                </p>

                {#if status === "verification-link-sent"}
                    <div class="mt-2 font-medium text-sm text-green-600">
                        A new verification link has been sent to your email
                        address.
                    </div>
                {/if}
            </div>
        {/if}

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
