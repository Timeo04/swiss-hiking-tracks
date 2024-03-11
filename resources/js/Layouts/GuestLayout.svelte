<script>
    // Inertia für Links importieren
    import { inertia } from "@inertiajs/svelte";
    // Logo importieren
    import ApplicationLogo from "@/Components/ApplicationLogo.svelte";
</script>

<div
    class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
>
    <div>
        <!-- Logo with Link -->
        <a href="/" use:inertia>
            <ApplicationLogo className="w-20 h-20 fill-current text-gray-500" />
        </a>
    </div>

    <div
        class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
    >
        <slot />
    </div>
</div>
