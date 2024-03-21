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

    export let images = [];
    export let track;
    let image_count = images.length;
    let swiper = null;

    $: if (image_count != images.length) {
        image_count = images.length;
        if (swiper != null) {
            console.log("call update");
            swiper.update();
        }
    }

    let imageFiles = null;
    let imageForm;
    async function submitImage() {
        if (imageFiles == null) {
            return;
        }
        console.log(imageFiles[0]);
        router.post(
            route("tracks.storeImage", track),
            {
                image: imageFiles[0],
            },
            {
                forceFormData: true,
                preserveScroll: true,
            },
        );
    }

    async function deleteImage(imageId) {
        router.delete(route("tracks.destroyImage", [track, imageId]), {
            preserveScroll: true,
        });
    }

    async function setHome(imageId) {
        let newOrder = images.map((img) => img.id);
        newOrder = newOrder.filter((img) => img != imageId);
        newOrder.unshift(imageId);
        router.post(
            route("tracks.updateImageOrder", track),
            {
                order: newOrder,
            },
            {
                preserveScroll: true,
            },
        );
    }
</script>

<Swiper bind:this={swiper}>
    {#each images as image, i (image.id)}
        <swiper-slide class="h-[500px] relative">
            <!-- <div class="h-[500px] rounded-xl"> -->
            <img src={image.url} alt="Bild" class="w-full rounded-2xl" />
            <button
                on:click={setHome(image.id)}
                disabled={i == 0}
                class="absolute top-4 left-4 text-gray-700"
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
            <!-- </div> -->
        </swiper-slide>
    {/each}
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
</Swiper>
