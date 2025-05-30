<div>
    <div class="flex items-center justify-center min-h-screen">
        <x-card title="Register here to continue !" class="md:w-96 mx-auto" novalidate>
            <x-form wire:submit.prevent="register" no-separator>
                <div class="row mb-2">
                    <div class="col-span-12">
                        <x-input label="Name" icon="o-user" placeholder="Enter space name" wire:model="name" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-span-12">
                        <x-input label="Email" icon="o-envelope" placeholder="Enter space email" wire:model="email" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-span-12">
                        <x-password label="Password" placeholder="Enter space password" right wire:model="password" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-span-12">
                        <x-password label="Confirm Password" placeholder="Confirm space password" only-password wire:model="confirm_password" />
                    </div>
                </div>
                <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />
                <x-slot:actions>
                    <x-button label="Cancel" wire:click="cancelar" />
                    <x-button label="Confirm" type="submit" spinner="register" icon="o-paper-airplane" class="btn-primary" />
                </x-slot:actions>
            </x-form>
        </x-card>
    </div>
</div>