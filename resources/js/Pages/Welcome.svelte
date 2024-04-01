<script>
    // Layout importieren
    import GuestLayout from "@/Layouts/GuestLayout.svelte";
    // UI-Komponenten importieren
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    // Funktionen für Netzwerk-Requests importieren
    import { inertia, router } from "@inertiajs/svelte";

    const route = window.route;
    // Übergabewerte initialisieren
    export let canLogin;
    export let canRegister;
    export let auth;
    export let errors;
    console.error(errors);
    export let laravelVersion;
    export let phpVersion;
</script>

<svelte:head>
    <title>Willkommen</title>
</svelte:head>

<!-- Links oben rechts anzeigen -->
<div class="fixed top-0 right-0 p-6 text-end">
    {#if auth.user != null}
        <!-- Falls der User eingeloggt ist -->
        <a
            use:inertia
            href={route("dashboard")}
            class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
            >Dashboard</a
        >
    {:else}
        <!-- Falls der User noch nicht eingeloggt ist -->
        {#if canLogin}
            <a
                use:inertia
                href={route("login")}
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Anmelden</a
            >
        {/if}
        {#if canRegister}
            <a
                use:inertia
                href={route("register")}
                class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Registrieren</a
            >
        {/if}
    {/if}
</div>

<GuestLayout>
    <div class="max-w-7xl mx-auto">
        <h2
            class="text-xl font-semibold text-gray-900 dark:text-white text-center"
        >
            SwissHikingTracks
        </h2>

        <div>
            <p
                class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400"
            >
                Willkommen bei SwissHikingTracks, der Plattform für Wanderungen
                in der Schweiz.
            </p>
        </div>

        <!-- Buttons mit Aktionen anzeigen -->
        {#if auth.user != null}
            <PrimaryButton
                className="mt-4 justify-center w-full"
                on:click={() => router.visit(route("dashboard"))}
            >
                Zum Dashboard
            </PrimaryButton>
        {:else}
            <PrimaryButton
                className="mt-4 justify-center w-full"
                on:click={() => router.visit(route("register"))}
            >
                Registrieren
            </PrimaryButton>
            <a
                href={route("login")}
                class="mt-4 block
                text-center text-sm text-gray-600 dark:text-gray-400
                hover:text-gray-900 dark:hover:text-white
                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                use:inertia>stattdessen anmelden</a
            >
        {/if}

        <div
            class="flex justify-center mt-10 px-6 sm:items-center sm:justify-between gap-4"
        >
            <div
                class="w-full text-center text-sm text-gray-500 dark:text-gray-400"
            >
                Laravel v{laravelVersion} (PHP v{phpVersion}) <!-- Laravel- und PHP-Version anzeigen -->
            </div>
        </div>
    </div>
</GuestLayout>
