<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    user: Object,
    stats: Object,
    recent_activities: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard -
                {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Enlace a gestión de usuarios para colegios -->
                <div
                    v-if="user.role === 'colegio'"
                    class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-blue-900">
                                Gestión de Usuarios
                            </h3>
                            <p class="text-blue-700">
                                Invita profesores y padres a la plataforma
                            </p>
                        </div>
                        <Link
                            :href="route('user-management.index')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Gestionar Usuarios
                        </Link>
                    </div>
                </div>

                <!-- Enlace a gestión de estudiantes para colegios -->
                <div
                    v-if="user.role === 'colegio'"
                    class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-purple-900">
                                Gestión de Estudiantes
                            </h3>
                            <p class="text-purple-700">
                                Crea estudiantes y asígnalos a padres
                            </p>
                        </div>
                        <Link
                            :href="route('students.index')"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Gestionar Estudiantes
                        </Link>
                    </div>
                </div>

                <!-- Enlace al perfil para todos los usuarios -->
                <div
                    class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-green-900">
                                Tu Perfil
                            </h3>
                            <p class="text-green-700">
                                Completa tu información personal
                            </p>
                        </div>
                        <Link
                            :href="route('profile.edit')"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Editar Perfil
                        </Link>
                    </div>
                </div>

                <!-- Información del usuario -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6"
                >
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">
                            Bienvenido, {{ user.name }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Email:</p>
                                <p class="font-medium">{{ user.email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Rol:</p>
                                <p class="font-medium capitalize">
                                    {{ user.role }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
