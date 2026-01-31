<nav class="bg-[#1E88E5] border-b border-gray-200 sticky top-0 z-20">
    <div class="px-6 py-3 flex items-center justify-between">
        <div class="flex items-center">
            <button class="text-white focus:outline-none mr-4" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>

            <div class="flex items-center">
                @if(file_exists(public_path('images/logo.png')))
                    {{-- <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-8"> --}}
                    <h1 class="text-white font-semibold ml-2 text-xl">Risk Disaster Management</h1>
                @else
                    <div class="logo-placeholder">{{ substr(config('app.name', 'L'), 0, 1) }}</div>
                    <span class="ml-2 text-xl font-semibold text-white">{{ config('app.name', 'Laravel') }}</span>
                @endif
            </div>
        </div>

        <div class="flex items-center">
            <div class="relative mr-4 hidden md:block">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400"></i>
                </div>
                <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="ml-4 relative">
                <button class="p-2 text-white hover:text-blue-700 focus:outline-none">
                    <i class="fa-solid fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-4 h-4 text-xs flex items-center justify-center">3</span>
                </button>
            </div>

            <div class="ml-4 relative">
                <button class="flex items-center focus:outline-none">
                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">U</div>
                       <p class="text-sm font-medium text-white">
                            {{-- {{ auth()->user()->first_name }} {{ auth()->user()->last_name }} --}}
                        </p>
                </button>
            </div>
        </div>
    </div>
</nav>
