<script>
    // Layout importieren
    import ApplicationLogo from "@/Components/ApplicationLogo.svelte";
import { inertia, router } from "@inertiajs/svelte";
    import { Button } from "flowbite-svelte";

    const route = window.route;
    export let canLogin;
    export let canRegister;
    export let auth;
    export let errors;
    console.log(errors);
    export let laravelVersion;
    export let phpVersion;
</script>

<svelte:head>
    <title>Willkommen</title>
</svelte:head>

<div
    class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
>
    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-end">
        {#if auth.user != null}
            <a
                use:inertia
                href={route("dashboard")}
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</a
            >
        {:else}
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

    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-center">
            <ApplicationLogo />
        </div>

        <h2
            class="mt-6 text-xl font-semibold text-gray-900 dark:text-white text-center"
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

        {#if auth.user != null}
            <Button
                class="mt-6 w-full"
                color="primary"
                size="lg"
                on:click={() => router.visit(route("dashboard"))}
            >
                Zum Dashboard
            </Button>
        {:else}
            <Button
                class="mt-6 w-full"
                color="primary"
                size="lg"
                on:click={() => router.visit(route("register"))}
            >
                Registrieren
            </Button>
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
            class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between gap-4"
        >
            <div
                class="w-full ms-4 text-center text-sm text-gray-500 dark:text-gray-400"
            >
                Laravel v{laravelVersion} (PHP v{phpVersion})
            </div>
        </div>
    </div>
</div>

<style>
    .bg-dots-darker {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
    }
    @media (prefers-color-scheme: dark) {
        .dark\:bg-dots-lighter {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
        }
    }
</style>
