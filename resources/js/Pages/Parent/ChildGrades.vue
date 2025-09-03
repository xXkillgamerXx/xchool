<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Calificaciones de Mi Hijo
                    </h1>
                    <p class="mt-2 text-gray-600">
                        {{
                            child
                                ? `Calificaciones de ${child.name}`
                                : "Calificaciones del estudiante"
                        }}
                        {{ grade ? ` - Grado: ${grade.name}` : "" }}
                    </p>
                </div>

                <!-- Mensaje si no hay hijo asignado -->
                <div
                    v-if="!child"
                    class="bg-yellow-50 border border-yellow-200 rounded-lg p-6"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg
                                class="h-5 w-5 text-yellow-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Hijo no asignado
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>{{ message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Promedio general -->
                <div
                    v-if="child && grades && grades.overall_average"
                    class="bg-white shadow rounded-lg p-6 mb-6"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">
                                Promedio General
                            </h2>
                            <p class="text-sm text-gray-600">
                                Promedio de todas las materias
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-indigo-600">
                                {{ grades.overall_average }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ getGradeLevel(grades.overall_average) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de calificaciones por curso -->
                <div v-if="child && grades && grades.courses" class="space-y-6">
                    <div
                        v-for="course in grades.courses"
                        :key="course.course"
                        class="bg-white shadow rounded-lg overflow-hidden"
                    >
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        {{ course.course }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Profesor: {{ course.teacher }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div
                                        class="text-2xl font-bold text-indigo-600"
                                    >
                                        {{ course.average }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ getGradeLevel(course.average) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-4">
                                Calificaciones Detalladas
                            </h4>
                            <div class="space-y-3">
                                <div
                                    v-for="(grade, index) in course.grades"
                                    :key="index"
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                >
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ grade.assignment }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ formatDate(grade.date) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                            :class="getGradeColor(grade.grade)"
                                        >
                                            {{ grade.grade }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ getGradeLevel(grade.grade) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje si no hay calificaciones -->
                <div
                    v-else-if="child"
                    class="bg-white shadow rounded-lg p-6 text-center"
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
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                        />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        No hay calificaciones registradas
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Aún no se han registrado calificaciones para tu hijo.
                    </p>
                </div>

                <!-- Leyenda de calificaciones -->
                <div
                    v-if="child && grades && grades.courses"
                    class="bg-white shadow rounded-lg p-6 mt-6"
                >
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Escala de Calificaciones
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="flex items-center">
                            <div
                                class="w-4 h-4 bg-green-500 rounded-full mr-2"
                            ></div>
                            <span class="text-sm text-gray-600"
                                >90-100: Excelente</span
                            >
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-4 h-4 bg-blue-500 rounded-full mr-2"
                            ></div>
                            <span class="text-sm text-gray-600"
                                >80-89: Bueno</span
                            >
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-4 h-4 bg-yellow-500 rounded-full mr-2"
                            ></div>
                            <span class="text-sm text-gray-600"
                                >70-79: Regular</span
                            >
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-4 h-4 bg-red-500 rounded-full mr-2"
                            ></div>
                            <span class="text-sm text-gray-600"
                                >0-69: Necesita mejorar</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    grades: Object,
    grade: Object,
    child: Object,
    user: Object,
    message: String,
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("es-ES", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const getGradeLevel = (grade) => {
    if (grade >= 90) return "Excelente";
    if (grade >= 80) return "Bueno";
    if (grade >= 70) return "Regular";
    return "Necesita mejorar";
};

const getGradeColor = (grade) => {
    if (grade >= 90) return "bg-green-100 text-green-800";
    if (grade >= 80) return "bg-blue-100 text-blue-800";
    if (grade >= 70) return "bg-yellow-100 text-yellow-800";
    return "bg-red-100 text-red-800";
};
</script>
