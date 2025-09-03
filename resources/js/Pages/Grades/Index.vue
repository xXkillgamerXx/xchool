<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Asignar Calificaciones
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Profesor: {{ teacher.display_name || teacher.name }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">
                            Calificaciones Recientes
                        </div>
                        <div class="text-2xl font-bold text-indigo-600">
                            {{ recentGrades.length }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center"
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
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Cursos que Enseño
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ myCourses.length }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center"
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
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Grados que Enseño
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ myGrades.length }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center"
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
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Calificaciones
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ totalGrades }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Asignar Calificaciones -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center"
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
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">
                                Asignar Calificaciones
                            </h3>
                            <p class="text-sm text-gray-600">
                                Crear una nueva tarea o examen y calificar
                                estudiantes
                            </p>
                        </div>
                        <div class="ml-4">
                            <Link
                                :href="route('grades.create')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Asignar Calificaciones
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Ver Estadísticas -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center"
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
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">
                                Estadísticas de Calificaciones
                            </h3>
                            <p class="text-sm text-gray-600">
                                Ver reportes y estadísticas por curso
                            </p>
                        </div>
                        <div class="ml-4">
                            <Link
                                :href="route('grades.statistics')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                Ver Estadísticas
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calificaciones Recientes -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Calificaciones Recientes
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Últimas calificaciones asignadas
                    </p>
                </div>
                <div class="p-6">
                    <div
                        v-if="recentGrades.length === 0"
                        class="text-center text-gray-500 py-8"
                    >
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            No hay calificaciones
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Asigna tu primera calificación para comenzar.
                        </p>
                        <div class="mt-6">
                            <Link
                                :href="route('grades.create')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <svg
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                    />
                                </svg>
                                Asignar Primera Calificación
                            </Link>
                        </div>
                    </div>

                    <div v-else class="space-y-4">
                        <div
                            v-for="grade in recentGrades"
                            :key="grade.id"
                            class="p-4 border rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <span
                                                :class="[
                                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                    getTypeColor(grade.type),
                                                ]"
                                            >
                                                {{ getTypeName(grade.type) }}
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3
                                                class="text-lg font-medium text-gray-900 truncate"
                                            >
                                                {{ grade.assignment_name }}
                                            </h3>
                                            <div
                                                class="mt-1 flex items-center space-x-4 text-sm text-gray-500"
                                            >
                                                <span>{{
                                                    grade.course.name
                                                }}</span>
                                                <span>•</span>
                                                <span>{{
                                                    grade.student.name
                                                }}</span>
                                                <span>•</span>
                                                <span>{{
                                                    formatDate(grade.grade_date)
                                                }}</span>
                                            </div>
                                            <div
                                                v-if="grade.comments"
                                                class="mt-2 text-sm text-gray-600"
                                            >
                                                {{ grade.comments }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500">
                                            Calificación
                                        </div>
                                        <div
                                            class="text-lg font-semibold text-gray-900"
                                        >
                                            {{ grade.score }} /
                                            {{ grade.max_score }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{
                                                getPercentage(
                                                    grade.score,
                                                    grade.max_score
                                                )
                                            }}%
                                        </div>
                                    </div>

                                    <div class="flex space-x-2">
                                        <Link
                                            :href="
                                                route(
                                                    'grades.student',
                                                    grade.student.id
                                                )
                                            "
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <svg
                                                class="w-4 h-4 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                />
                                            </svg>
                                            Ver Estudiante
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

// Props
const props = defineProps({
    recentGrades: Array,
    myCourses: Array,
    myGrades: Array,
    teacher: Object,
});

// Computed
const totalGrades = computed(() => {
    return props.recentGrades.length;
});

// Methods
const getTypeName = (type) => {
    const types = {
        exam: "Examen",
        homework: "Tarea",
        project: "Proyecto",
        quiz: "Quiz",
        participation: "Participación",
        other: "Otro",
    };
    return types[type] || type;
};

const getTypeColor = (type) => {
    const colors = {
        exam: "bg-red-100 text-red-800",
        homework: "bg-blue-100 text-blue-800",
        project: "bg-green-100 text-green-800",
        quiz: "bg-yellow-100 text-yellow-800",
        participation: "bg-purple-100 text-purple-800",
        other: "bg-gray-100 text-gray-800",
    };
    return colors[type] || "bg-gray-100 text-gray-800";
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("es-ES", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const getPercentage = (score, maxScore) => {
    if (maxScore > 0) {
        return Math.round((score / maxScore) * 100);
    }
    return 0;
};
</script>
