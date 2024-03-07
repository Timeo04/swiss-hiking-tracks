<script setup>
    // import { computed } from 'vue';
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import { useForm, Link } from "@inertiajs/svelte";
    import { inertia } from "@inertiajs/svelte";

    export let status;

    let form = useForm({});

    function submit() {
        $form.post(route("verification.send"));
    }

    const verificationLinkSent = status === "verification-link-sent";
</script>

<svelte:head>
    <title>E-Mail-Bestätigung</title>
</svelte:head>

<GuestLayout>
    <div class="mb-4 text-sm text-gray-600">
        Danke für die Anmeldung! Bevor Sie beginnen, können Sie Ihre
        E-Mail-Adresse überprüfen, indem Sie auf den Link klicken, den wir Ihnen
        gerade per E-Mail gesendet haben. Wenn Sie die E-Mail nicht erhalten
        haben, senden wir Ihnen gerne eine weitere.
    </div>

    <div
        class="mb-4 font-medium text-sm text-green-600"
        v-if="verificationLinkSent"
    >
        Ein neuer Bestätigungslink wurde an die E-Mail-Adresse gesendet, die Sie
        während der Registrierung angegeben haben.
    </div>

    <form on:submit|preventDefault={submit}>
        <div class="mt-4 flex items-center justify-between">
            <PrimaryButton
                className={$form.processing ? "opacity-25" : ""}
                disabled={$form.processing}
            >
                E-Mail-Bestätigungslink erneut senden
            </PrimaryButton>

            <button use:inertia={{ href: route("logout"), method: "post" }} type="button">
                Abmelden
            </button>
            <!-- <Link
                href={route("logout")}
                method="post"
                as="button"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                >Log Out</Link
            > -->
        </div>
    </form>
</GuestLayout>
