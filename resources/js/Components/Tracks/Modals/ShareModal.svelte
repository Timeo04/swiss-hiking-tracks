<script>
    import Modal from "@/Components/Modal.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    import { router } from "@inertiajs/svelte";
    import { ShareNodesOutline } from "flowbite-svelte-icons";
    import QRCode from "qrcode";

    export let open = false;
    export let track;
    let qrCodeCanvas;

    $: if (track.share_url != null && qrCodeCanvas != null) {
        let qrCode = new QRCode.toCanvas(
            qrCodeCanvas,
            route("tracks.showShare", track.share_url),
        );
    }

    // Modal schliessen
    function closeModal() {
        open = false;
    }

    function share() {
        if (navigator.share) {
            navigator.share({
                title: track.title,
                text: "Schau dir diese Route an!",
                url: route("tracks.showShare", track.share_url),
            });
        }
    }
</script>

<Modal bind:open on:close={closeModal}>
    <div class="p-6">
        {#if track.share_url != null}
            <h2 class="font-bold text-xl text-center">
                Die Route "{track.title}" teilen
            </h2>
            <canvas class="mx-auto" bind:this={qrCodeCanvas}></canvas>
            <!-- svelte-ignore missing-declaration -->
            <a
                class="text-primary-600 text-center block underline"
                href={route("tracks.showShare", track.share_url)}
                >{route("tracks.showShare", track.share_url)}</a
            >
            <!-- <p>
                <em>Geben Sie diesen Link weiter, um diesen Track zu teilen.</em
                >
            </p> -->
            {#if navigator.share}
                <div class="mt-4 w-full flex justify-center items-center">
                    <SecondaryButton on:click={share}>
                        <ShareNodesOutline tabindex="-1" />
                    </SecondaryButton>
                </div>
            {/if}
            <div class="mt-6 flex justify-end">
                <!-- Close Modal -->
                <SecondaryButton on:click={closeModal}
                    >Schliessen</SecondaryButton
                >

                <!-- Delete Track -->
                <!-- svelte-ignore missing-declaration -->
                <PrimaryButton
                    className="ms-3"
                    on:click={router.delete(
                        route("tracks.unshare", { track }),
                        {
                            preserveScroll: true,
                        },
                    )}
                >
                    Freigabe widerrufen
                </PrimaryButton>
            </div>
        {:else}
            <h2 class="text-lg font-medium text-gray-900">
                Möchten Sie die Route "{track.title}" teilen?
            </h2>

            <!-- <p class="mt-1 text-sm text-gray-600">
                Wird die Route gelöscht, werden auch alle dazugehörigen Daten
                dauerhaft entfernt.
            </p> -->

            <div class="mt-6 flex justify-end">
                <!-- Close Modal -->
                <SecondaryButton on:click={closeModal}
                    >Abbrechen</SecondaryButton
                >

                <!-- Delete Track -->
                <!-- svelte-ignore missing-declaration -->
                <PrimaryButton
                    className="ms-3"
                    on:click={router.post(
                        route("tracks.share", { track }),
                        {},
                        {
                            preserveScroll: true,
                        },
                    )}
                >
                    Route teilen
                </PrimaryButton>
            </div>
        {/if}
    </div>
</Modal>
