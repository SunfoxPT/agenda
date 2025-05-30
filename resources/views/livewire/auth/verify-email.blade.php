<div>
    <div class="flex items-center justify-center min-h-screen">
        <x-card title="Verify your email" class="md:w-96 mx-auto" novalidate>
            <p class="mb-4 text-center">
                We have sent a verification link to your email. Please check your inbox or spam folder.
            </p>

            <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />

            <x-slot:actions>
                <x-button wire:click="resend" icon="o-envelope" spinner="resend" label="Resend verification email" class="btn-primary w-full" />
            </x-slot:actions>
        </x-card>
    </div>
</div>
