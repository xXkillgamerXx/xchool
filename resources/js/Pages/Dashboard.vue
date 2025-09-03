<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

// Props
const props = defineProps({
    user: Object,
    stats: Object,
    recent_activities: Array,
});
</script>

<template>
    <AdminLayout :user="user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    ¡Bienvenido, {{ user.display_name }}!
                </h1>
                <p class="mt-2 text-gray-600">
                    Panel de administración de Xchool
                </p>
            </div>

            <!-- Estadísticas -->
            <div
                v-if="stats"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
            >
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-600 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Usuarios
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.total_usuarios }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-green-600 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Estudiantes
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.total_estudiantes }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-purple-600 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Profesores
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.total_profesores }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-yellow-600 rounded-md flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Padres
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ stats.total_padres }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div v-if="recent_activities" class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Actividad Reciente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div
                            v-for="activity in recent_activities"
                            :key="activity.id"
                            class="flex items-start space-x-3"
                        >
                            <div class="flex-shrink-0">
                                <div
                                    class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-4 h-4 text-indigo-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    {{ activity.description }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ activity.time }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
