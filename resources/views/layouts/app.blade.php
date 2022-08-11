
        <div class="min-h-screen bg-gray-100">


         {{--    <div id="app">
                <example-component/>
            </div> --}}



            <!-- Page Heading -->
    

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
