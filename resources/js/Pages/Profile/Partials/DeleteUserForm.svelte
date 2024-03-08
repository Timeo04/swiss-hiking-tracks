<script>
    import DangerButton from "@/Components/DangerButton.svelte";
    import InputError from "@/Components/InputError.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import Modal from "@/Components/Modal.svelte";
    import SecondaryButton from "@/Components/SecondaryButton.svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import { useForm } from "@inertiajs/svelte";

    export let className = "";

    let confirmUserDeletionModal = false;
    let passwordInput = null;

    let form = useForm({
        password: "",
    });

    function deleteUser() {
        $form.delete(route("profile.destroy"), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.input.focus(),
            onFinish: () => $form.reset(),
        });
    }

    function closeModal() {
        confirmUserDeletionModal = false;

        $form.reset();
    }
</script>

<section class="space-y-6 {className}">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>

        <p class="mt-1 text-sm text-gray-600">
            Once your account is deleted, all of its resources and data will be
            permanently deleted. Before deleting your account, please download
            any data or information that you wish to retain.
        </p>
    </header>

    <DangerButton on:click={() => (confirmUserDeletionModal = true)}
        >Delete Account</DangerButton
    >

    <Modal bind:open={confirmUserDeletionModal} on:close={closeModal}>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete your account?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Please enter your password to confirm
                you would like to permanently delete your account.
            </p>

            <div class="mt-6">
                <InputLabel
                    forValue="password"
                    value="Password"
                    className="sr-only"
                />

                <TextInput
                    id="password"
                    bind:value={$form.password}
                    bind:this={passwordInput}
                    type="password"
                    className="mt-1 block w-3/4"
                    placeholder="Password"
                    required
                    autofocus={true}
                />

                {#if $form.errors.password}
                    <InputError message={$form.errors.password} className="mt-2" />
                {/if}
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton on:click={closeModal}>Cancel</SecondaryButton>

                <DangerButton
                    className="ms-3 {$form.processing ? 'opacity-25' : ''}"
                    disabled={$form.processing}
                    on:click={deleteUser}
                >
                    Delete Account
                </DangerButton>
            </div>
        </div>
    </Modal>
</section>
