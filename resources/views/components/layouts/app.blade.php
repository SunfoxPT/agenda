<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">
    <div id="progress" class="fixed inset-0 z-50 bg-base-300/60 flex items-center justify-center hidden">
        <span class="loading loading-spinner w-10 text-primary"></span>
    </div>
    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <x-app-brand class="px-5 pt-4" />
            {{-- SIDEBAR ACTIONS --}}
            
            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if($user = auth()->user())
                    <x-menu-separator />

                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">

                        <x-slot:actions>
                            <x-theme-toggle darkTheme="aqua" lightTheme="retro" />
                            <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate link="/logout" />
                        </x-slot:actions>
                    </x-list-item>

                    <x-menu-separator />
                @endif

                <x-menu-sub title="Team" icon="o-user-group">
                    <x-menu-item title="Staffs" icon="o-user" link="{{ route('admin.staffs') }}" />
                    <x-menu-item title="Clients" icon="o-user-group" link="{{ route('admin.clients') }}" />
                </x-menu-sub>

                <x-menu-sub title="Resources" icon="o-sparkles">
                    <x-menu-item title="Services" icon="o-sparkles" link="{{ route('admin.services') }}" />
                    <x-menu-item title="Spaces" icon="o-home" link="{{ route('admin.spaces') }}" />
                </x-menu-sub>

                <x-menu-sub title="Calendar" icon="o-calendar-days">
                    <x-menu-item title="Appointments" icon="o-calendar" link="{{ route('admin.appointments') }}" />
                    <x-menu-item title="Business Hours" icon="o-clock" link="{{ route('admin.business-hours') }}" />
                </x-menu-sub>

            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />  
    <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />
    @livewireScripts
</body>
</html>
