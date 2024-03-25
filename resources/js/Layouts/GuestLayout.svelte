<script>
    // Inertia für Links importieren
    import { inertia } from "@inertiajs/svelte";
    // Logo importieren
    import ApplicationLogo from "@/Components/ApplicationLogo.svelte";

    // import BackgroundImage from "@/assets/img/swisshikingtracks_bg.jpg";
</script>

<div
    class="bg-image min-h-screen flex flex-col sm:justify-center items-center pt-20 bg-gray-100 px-6"
>
    <div>
        <!-- Logo with Link -->
        <a href="/" use:inertia>
            <ApplicationLogo className="w-20 h-20 fill-current text-gray-500" />
        </a>
    </div>

    <div
        class="w-full max-w-md mt-8 sm:mt-20 p-6 pt-4 lg:p-10 lg:pt-8 bg-white/75 shadow-md overflow-hidden rounded-2xl"
    >
        <slot />
    </div>
</div>

<style>
    .bg-image {
        background-image: url(@/assets/img/swisshikingtracks_bg.jpg);
        background-size: cover;
        background-position: center;
    }

    @media (min-width: 640px) {
        .bg-image {
            background-image: url(@/assets/img/swisshikingtracks_bg_lg.jpg);
        }
    }
</style>
