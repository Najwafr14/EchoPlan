<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
    </div>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-32"></div>
                <div class="px-6 pb-6">
                    <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:-mt-12">
                        <div class="w-24 h-24 sm:w-48 sm:h-48 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center text-3xl sm:text-4xl font-bold text-blue-600">
                            {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-6 text-center sm:text-left">
                            <div class="flex items-center justify-center sm:justify-start gap-3">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                                    {{ auth()->user()->full_name }}
                                </h1>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ auth()->user()->status === 'active' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst(auth()->user()->status) }}
                                </span>
                            </div>
                            <p class="text-gray-600 mt-1">{{ auth()->user()->email }}</p>
                            <span class="inline-block mt-2 px-3 py-1 text-sm font-medium rounded-full 
                                {{ auth()->user()->role === 'Admin' ? 'bg-red-100 text-red-800' : 
                                (auth()->user()->role === 'Owner' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b">
                    Personal Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Fullname</label>
                        <p class="text-gray-900 font-medium">{{ auth()->user()->full_name }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Username</label>
                        <p class="text-gray-900 font-medium">{{ auth()->user()->username }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900 font-medium">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="text-gray-900 font-medium">{{ auth()->user()->phone_number ?? '-' }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Domicile</label>
                        <p class="text-gray-900 font-medium">{{ auth()->user()->domicile ?? '-' }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Born Date</label>
                        <p class="text-gray-900 font-medium">
                            {{ auth()->user()->born_date 
                                ? \Carbon\Carbon::parse(auth()->user()->born_date)->translatedFormat('d F Y') 
                                : '-' 
                            }}
                        </p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Gender</label>
                        <p class="text-gray-900 font-medium">{{ ucfirst(auth()->user()->gender ?? '-') }}</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-500">Role</label>
                        <p class="text-gray-900 font-medium">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('profile.edit') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition duration-200 text-center">
                    Edit Profile
                </a>
                <div class="flex-1">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<style>
@media (max-width: 640px) {
    .bg-gradient-to-r {
        height: 80px;
    }
}
</style>