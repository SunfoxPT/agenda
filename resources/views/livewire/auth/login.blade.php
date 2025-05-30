<div>
    <div class="flex items-center justify-center min-h-screen">
        <x-card title="Sign in"  class="md:w-96 mx-auto" novalidate>
            <span class="text-sm text-gray-500 mb-4">
                Don't have an account?
            </span>
            <a class="link link-primary" href="{{ route('register') }}">Sign up</a>
            <x-form wire:submit.prevent="authenticate" no-separator>
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
                        <a class="link link-primary" href="#">
                            Forgot password?
                        </a>
                    </div>
                </div>
                <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />
                <x-slot:actions>
                    <x-button label="Cancel" wire:click="cancelar" />
                    <x-button label="Confirm" type="submit" spinner="authenticate" icon="o-paper-airplane" class="btn-primary" />
                </x-slot:actions>

            </x-form>
        </x-card>
    </div>
</div>