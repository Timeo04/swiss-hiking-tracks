<script>
    import InputError from "@/Components/InputError.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { useForm, inertia } from "@inertiajs/svelte";
    import { FloatingLabelInput } from "flowbite-svelte";
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
        <h2 class="text-lg font-medium text-gray-900">Profilinformationen</h2>

        <p class="mt-1 text-sm text-gray-600">
            Aktualisieren Sie die Profilinformationen und die E-Mail-Adresse.
        </p>
    </header>

    <!-- svelte-ignore missing-declaration -->
    <form
        on:submit|preventDefault={$form.patch(route("profile.update"), {
            preserveScroll: true,
        })}
        class="mt-6 space-y-6"
    >
        <div>
            <FloatingLabelInput
                style="outlined"
                id="name"
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

        <div>
            <FloatingLabelInput
                style="outlined"
                id="email"
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

        {#if mustVerifyEmail && user.email_verified_at === null}
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    Ihre E-Mail-Adresse wurde noch nicht bestätigt.
                    <!-- svelte-ignore a11y-missing-attribute -->
                    <a
                        use:inertia={{
                            href: route("verification.send"),
                            method: "post",
                            as: "button",
                        }}
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                    >
                        Hier klicken, um die Bestätigungsmail erneut zu senden.
                    </a>
                </p>

                {#if status === "verification-link-sent"}
                    <div class="mt-2 font-medium text-sm text-green-600">
                        Ein neuer Bestätigungslink wurde an Ihre E-Mail-Adresse
                        gesendet.
                    </div>
                {/if}
            </div>
        {/if}

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
