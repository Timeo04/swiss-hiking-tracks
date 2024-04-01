<script>
    // Modal Component importieren
    import Modal from "@/Components/Modal.svelte";
    // UI-Komponenten importieren
    import PrimaryButton from "@/Components/PrimaryButton.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    // Funktion für Netzwerk-Requests importieren
    import { router } from "@inertiajs/svelte";
    // Icon importieren
    import { ShareNodesOutline } from "flowbite-svelte-icons";
    // QR-Code-Generator importieren
    import QRCode from "qrcode";

    // Variablen deklarieren
    export let open = false;
    export let track;

    // QR-Code-Canvas initialisieren
    let qrCodeCanvas;

    // QR-Code generieren falls share_url vorhanden und qrCodeCanvas initialisiert ist
    $: if (track.share_url != null && qrCodeCanvas != null) {
        // QR-Code generieren und auf Canvas zeichnen
        let qrCode = new QRCode.toCanvas(
            qrCodeCanvas,
            route("tracks.showShare", track.share_url),
        );
    }

    // Modal schliessen
    function closeModal() {
        open = false;
    }

    // Teilen-Funktion für den Share-Button
    function share() {
        // Wenn der Browser die Share-API unterstützt
        if (navigator.share) {
            // Nativen Teilen-Dialog öffnen
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
        <!-- Falls der Track geteilt wurde -->
        {#if track.share_url != null}
            <h2 class="font-bold text-xl text-center">
                Die Route "{track.title}" teilen
            </h2>
            <!-- Canvas für QR-Code -->
            <canvas class="mx-auto" bind:this={qrCodeCanvas}></canvas>
            <!-- Link anzeigen -->
            <!-- svelte-ignore missing-declaration -->
            <p class="text-primary-600 text-center block underline">
                {route("tracks.showShare", track.share_url)}
            </p>
            {#if navigator.share}
                <div class="mt-4 w-full flex justify-center items-center">
                    <!-- Share-Button -->
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

                <!-- Delete Track Share -->
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
            <!-- Falls der Track noch nicht geteilt wurde -->
        {:else}
            <h2 class="text-lg font-medium text-gray-900">
                Möchten Sie die Route "{track.title}" teilen?
            </h2>

            <div class="mt-6 flex justify-end">
                <!-- Close Modal -->
                <SecondaryButton on:click={closeModal}
                    >Abbrechen</SecondaryButton
                >

                <!-- Track teilen -->
                <!-- svelte-ignore missing-declaration -->
                <PrimaryButton
                    className="ms-3"
                    on:click={router.post(
                        // Netzwerk-Request an den Server
                        route("tracks.share", { track }),
                        {},
                        {
                            preserveScroll: true, // Scroll-Position beibehalten
                        },
                    )}
                >
                    Route teilen
                </PrimaryButton>
            </div>
        {/if}
    </div>
</Modal>
