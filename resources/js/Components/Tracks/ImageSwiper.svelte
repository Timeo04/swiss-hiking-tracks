<script>
    import Swiper from "@/Components/Swiper.svelte";
    // Funktion für Netzwerk-Requests importieren
    import { router } from "@inertiajs/svelte";
    // Icon importieren
    import {
        HomeOutline,
        HomeSolid,
        PlusSolid,
        TrashBinOutline,
    } from "flowbite-svelte-icons";

    // Variablen deklarieren
    export let images = [];
    export let track;
    export let shared = false;
    // Anzahl der Bilder
    let image_count = images.length;
    // Variable für Swiper-Instanz initialisieren
    let swiper = null;

    // Wenn sich die Anzahl der Bilder ändert, Swiper aktualisieren
    $: if (image_count != images.length) {
        image_count = images.length;
        if (swiper != null) {
            swiper.update();
        }
    }

    let imageFiles = null;
    let imageForm;
    // Neues Bild hochladen
    async function submitImage() {
        if (imageFiles == null) {
            return;
        }
        // Per POST-Request Bild hochladen
        router.post(
            route("tracks.storeImage", track),
            {
                image: imageFiles[0],
            },
            {
                forceFormData: true, // FormData-Objekt verwenden
                preserveScroll: true, // Scroll-Position beibehalten
            },
        );
    }

    // Bild löschen
    async function deleteImage(imageId) {
        router.delete(route("tracks.destroyImage", [track, imageId]), {
            preserveScroll: true,
        });
    }

    // Bild als Titelbild setzen
    async function setHome(imageId) {
        // Neue Reihenfolge der Bilder erstellen
        let newOrder = images.map((img) => img.id);
        newOrder = newOrder.filter((img) => img != imageId);
        newOrder.unshift(imageId);
        // Reihenfolge per POST-Request aktualisieren
        router.post(
            route("tracks.updateImageOrder", track),
            {
                order: newOrder,
            },
            {
                preserveScroll: true, // Scroll-Position beibehalten
            },
        );
    }
</script>

<!-- Swiper für Bilder -->
<Swiper bind:this={swiper}>
    <!-- Für jedes Bild ein Slide erstellen -->
    {#each images as image, i (image.id)}
        <swiper-slide class="h-[500px] relative">
            <img src={image.url} alt="Bild" class="w-full rounded-2xl" />
            {#if !shared}
                <!-- Buttons für Titelbild und Löschen -->
                <button
                    on:click={setHome(image.id)}
                    disabled={i == 0}
                    class="absolute top-4 left-4 text-white mix-blend-difference grayscale"
                >
                    {#if i == 0}
                        <HomeSolid />
                    {:else}
                        <HomeOutline />
                    {/if}
                </button>
                <button
                    on:click={deleteImage(image.id)}
                    class="absolute top-4 right-4"
                >
                    <TrashBinOutline />
                </button>
            {/if}
        </swiper-slide>
    {/each}
    {#if !shared}
        <!-- Slide für neues Bild -->
        <swiper-slide>
            <div
                class="flex justify-stretch items-stretch w-full rounded-2xl bg-gray-300 h-[500px]"
            >
                <button
                    on:click={() => imageForm.querySelector("input").click()}
                    class="p-20 w-full h-full flex-col gap-2 flex justify-center items-center"
                >
                    <PlusSolid size="xl" />
                    <p>Bild hinzufügen</p>
                </button>
            </div>

            <form
                on:submit|preventDefault={submitImage}
                bind:this={imageForm}
                class="hidden"
            >
                <input
                    on:input={function () {
                        // console.log(this.files[0]);
                        imageFiles = this.files;
                        submitImage();
                    }}
                    bind:files={imageFiles}
                    type="file"
                    accept="image/*"
                />
            </form>
        </swiper-slide>
    {/if}
</Swiper>
