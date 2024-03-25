<script>
    import Modal from "@/Components/Modal.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    import { router } from "@inertiajs/svelte";
    import QRCode from "qrcode";

    export let open = false;
    export let track;
    let qrCodeCanvas;

    $: if (track.share_url != null && qrCodeCanvas != null) {
        let qrCode = new QRCode.toCanvas(
            qrCodeCanvas,
            route("tracks.show", track.share_url),
        );
    }

    // Modal schliessen
    function closeModal() {
        open = false;
    }
</script>

<Modal bind:open on:close={closeModal}>
    <div class="p-6">
        {#if track.share_url != null}
            <p>Diese Route wurde geteilt:</p>
            <!-- svelte-ignore missing-declaration -->
            <p>{route("tracks.show", track.share_url)}</p>
            <p>
                <em>Geben Sie diesen Link weiter, um diesen Track zu teilen.</em
                >
            </p>
            <canvas bind:this={qrCodeCanvas}></canvas>
            <div class="mt-6 flex justify-end">
                <!-- Close Modal -->
                <SecondaryButton on:click={closeModal}
                    >Abbrechen</SecondaryButton
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
                    Teilen beenden
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
