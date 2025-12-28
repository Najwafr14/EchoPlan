<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Echo Plan') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 flex items-center justify-center p-6">
    <div class="w-full max-w-6xl">

        <!-- Main Content -->
        <main class="text-center">

            <!-- Brand -->
            <div class="mb-8">
                <h1 class="text-6xl font-bold text-blue-600 mb-3">Echo Plan</h1>
                <p class="text-xl text-gray-600">
                    Web-Based Concert Event Management System
                </p>
            </div>

            <!-- Description -->
            <p class="max-w-3xl mx-auto text-gray-600 mb-12 leading-relaxed">
                Echo Plan is a web-based information system designed to support concert event organizers
                in planning, monitoring, and evaluating the progress of each division.
                This system centralizes tasks, schedules, and documents to improve coordination,
                transparency, and timely execution across the entire event team.
            </p>

            <!-- Feature Cards -->
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-12">

                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border-2 border-blue-100 hover:border-blue-300 transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l7 7v9a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Centralized Task Management</h3>
                    <p class="text-gray-600">
                        Track tasks, deadlines, and responsibilities across all divisions
                        in one integrated system.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg border-2 border-blue-100 hover:border-blue-300 transition-all">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Progress Monitoring</h3>
                    <p class="text-gray-600">
                        Provide clear visibility of project progress to ensure
                        all activities are completed on time.
                    </p>
                </div>

            </div>

            <!-- CTA -->
            <a href="{{ route('login') }}"
                class="inline-block px-8 py-4 bg-blue-600 text-white text-lg font-semibold rounded-lg
                       hover:bg-blue-700 transform hover:scale-105 transition-all shadow-lg">
                Start Managing Your Event
            </a>

            <!-- Footer -->
            <p class="text-gray-500 text-sm mt-12">
                &copy; 2024 Echo Plan — Concert Event Organizer Management System
            </p>

        </main>
    </div>
</body>

</html>