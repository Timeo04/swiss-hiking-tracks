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
    <swiper-slide class="h-[500px] relative rounded-2xl overflow-clip">
        <h4>Wetter</h4>
    </swiper-slide>
</Swiper>
