<x-app-layout>
    <div class="header">
        <a id="sidebarToggle" class="sidebar-toggle">
            ☰
        </a>
        <p>Dashboard - {{ auth()->user()->role }}</p>
        <b>{{ auth()->user()->full_name }}</b>
    </div>
</x-app-layout>
