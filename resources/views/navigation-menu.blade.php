<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('crm.dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link href="{{ route('crm.dashboard') }}" :active="request()->routeIs('crm.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- CRM Links -->
                    <div class="hidden sm:flex sm:items-center" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = ! open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            <span>CRM</span>
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" class="absolute z-50 mt-32 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <x-dropdown-link href="{{ route('customers.index') }}">
                                    {{ __('Customers') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('products.index') }}">
                                    {{ __('Products') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('orders.index') }}">
                                    {{ __('Orders') }}
                                </x-dropdown-link>
                                <x-dropdown-link href="{{ route('segmentation.index') }}">
                                    {{ __('Segments') }}
                                </x-dropdown-link>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics Link -->
                    <x-nav-link href="{{ route('analytics.dashboard') }}" :active="request()->routeIs('analytics.*')">
                        {{ __('Analytics') }}
                    </x-nav-link>

                    <!-- Interactions Link -->
                    <x-nav-link href="{{ route('interactions.dashboard') }}" :active="request()->routeIs('interactions.dashboard')">
                        {{ __('Interactions') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('campaigns.index') }}" :active="request()->routeIs('campaigns.*')">
                        {{ __('Campaigns') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('loyalty.dashboard') }}" :active="request()->routeIs('loyalty.*')">
                        {{ __('Loyalty Program') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Manage Account') }}
                        </div>

                        <x-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}"
                                    @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
