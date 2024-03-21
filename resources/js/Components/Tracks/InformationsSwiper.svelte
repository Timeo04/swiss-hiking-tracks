<script>
    import Swiper from "@/Components/Swiper.svelte";
    import Map from "./Map.svelte";

    export let track;
    let swiper = null;
</script>

<Swiper bind:this={swiper}>
    <swiper-slide class="h-[500px] relative rounded-2xl overflow-clip">
        <Map {track} />
    </swiper-slide>
    <swiper-slide class="h-[500px] bg-gray-300 relative rounded-2xl overflow-clip">
        <h3 class="text-2xl">Wetter</h3>
    </swiper-slide>
    <swiper-slide class="h-[500px] bg-gray-300 relative rounded-2xl overflow-clip">
        <h3 class="text-2xl">Sicherheit und Notfallnummern</h3>
    </swiper-slide>
</Swiper>
