<script>
    // Komponenten importieren
    import Swiper from "@/Components/Swiper.svelte";
    import Map from "./Map.svelte";
    import WeatherAddInfo from "./WeatherAddInfo.svelte";
    // UI-Komponente importieren
    import { Card } from "flowbite-svelte";

    export let track;
    let swiper = null;
</script>

<Swiper bind:this={swiper}>
    <swiper-slide class="h-[500px] relative rounded-2xl overflow-clip">
        <!-- Karte -->
        <Map tracks={[track]} />
    </swiper-slide>
    <swiper-slide
        class="h-[500px] bg-gray-300 relative rounded-2xl overflow-clip"
    >
        <!-- Wetter -->
        <WeatherAddInfo track={track}/>
    </swiper-slide>
    <swiper-slide
        class="h-[500px] bg-gray-300 relative rounded-2xl overflow-clip flex justify-center items-center"
    >
        <!-- Notfallinformationen -->
        <Card
            class="flex flex-col gap-2 justify-center items-center w-10/12 w-max-[400px] border-red-500 bg-gray-200 p-8 text-center"
        >
            <strong class="text-black text-xl">Geben Sie Acht!</strong>
            <p>
                Hier finden Sie einige Sicherheitshinweise und Notfallnummern.
            </p>
            <a target="_blank" href="https://www.sac-cas.ch/de/ausbildung-und-sicherheit/sicher-unterwegs/" class="inline-flex items-center text-primary-600 underline hover:text-primary-500">Schweizer Alpen-Club (SAC)</a>
            <a target="_blank" href="https://www.schweizer-wanderwege.ch/de/wandern/unterwegs/sicherheit-und-notfall" class="inline-flex items-center text-primary-600 underline hover:text-primary-500">Schweizer-Wanderwege</a>
            <div>
                <p>Polizei: 117</p>
                <p>Feuerwehr 118</p>
                <p>Ambulanz: 144</p>
                <p>Rega: 1414</p>
            </div>
        </Card>
    </swiper-slide>
</Swiper>
