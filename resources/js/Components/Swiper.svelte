<svelte:options accessors={true} />

<script>
    // Import Swiper Svelte components
    import { register } from "swiper/element/bundle";

    register();

    // swiper parameters
    const swiperParams = {
        slidesPerView: 1,
        observer: true,
        observeSlideChildren: true,
        pagination: {
            // el: ".swiper-pagination",
            clickable: true,
        },
        spaceBetween: 10,
    };

    let swiper = null;

    export function update() {
        if (swiper != null) {
            console.log("updating...");
            swiper.swiper.updateSlides();
        }
    }

    function init() {
        // now we need to assign all parameters to Swiper element
        Object.assign(swiper, swiperParams);

        // and now initialize it
        swiper.initialize();
    }

    $: if (swiper != null) init();
</script>

<swiper-container class="mySwiper z-auto" init="false" bind:this={swiper}>
    <slot />
</swiper-container>

<style>
    swiper-container {
        width: 100%;
        height: 100%;
    }

    swiper-container :global(swiper-slide) {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    swiper-container :global(swiper-slide) :global(img) {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    :global(:root) {
        --swiper-theme-color: #ef562f;
        --swiper-pagination-color: var(--swiper-theme-color);
        --swiper-pagination-bullet-size: 6px;
        --swiper-pagination-bullet-width: 6px;
        --swiper-pagination-bullet-height: 6px;
        --swiper-pagination-bullet-inactive-color: #fff;
        --swiper-pagination-bullet-inactive-opacity: 1;
        --swiper-pagination-bullet-opacity: 1;
        --swiper-pagination-bullet-horizontal-gap: 2px;
        --swiper-pagination-bullet-vertical-gap: 4px;
        --swiper-pagination-bottom: 6px;
    }
</style>
