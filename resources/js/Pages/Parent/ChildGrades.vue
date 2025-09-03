<template>
    <ParentLayout :children="children" current-tab="grades">
        <template #default="{ selectedChild }">
            <!-- Mensaje si no hay hijo seleccionado -->
            <div
                v-if="!selectedChild"
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
                            Selecciona un estudiante
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>
                                Por favor selecciona un estudiante para ver sus
                                calificaciones.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de calificaciones -->
            <div v-else class="space-y-6">
                <!-- Header -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Calificaciones - {{ selectedChild.name }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        {{
                            selectedChild.grade
                                ? selectedChild.grade.full_name
                                : "Sin grado asignado"
                        }}
                    </p>
                </div>

                <!-- Promedio general -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Promedio General
                            </h3>
                            <p class="text-sm text-gray-600">
                                Promedio de todas las materias
                            </p>
                        </div>
                        <div class="text-right">
                            <div
                                :class="[
                                    getGradeColor(grades.overall_average),
                                    'text-3xl font-bold',
                                ]"
                            >
                                {{ grades.overall_average }}
                            </div>
                            <p class="text-sm text-gray-600">sobre 100</p>
                        </div>
                    </div>
                </div>

                <!-- Calificaciones por curso -->
                <div class="space-y-6">
                    <div
                        v-for="courseData in grades.courses"
                        :key="courseData.course"
                        class="bg-white shadow rounded-lg overflow-hidden"
                    >
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        {{ courseData.course }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        Profesor: {{ courseData.teacher }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div
                                        :class="[
                                            getGradeColor(courseData.average),
                                            'text-2xl font-bold',
                                        ]"
                                    >
                                        {{ courseData.average }}
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Promedio
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="divide-y divide-gray-200">
                            <div
                                v-for="grade in courseData.grades"
                                :key="grade.assignment"
                                class="px-6 py-4 flex items-center justify-between"
                            >
                                <div>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ grade.assignment }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ formatDate(grade.date) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div
                                        :class="[
                                            getGradeColor(grade.grade),
                                            'text-lg font-medium',
                                        ]"
                                    >
                                        {{ grade.grade }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </ParentLayout>
</template>

<script setup>
import ParentLayout from "@/Layouts/ParentLayout.vue";

const props = defineProps({
    grades: Object,
    grade: Object,
    child: Object,
    children: Array,
    user: Object,
    message: String,
});

const getGradeColor = (grade) => {
    if (grade >= 90) return "text-green-600";
    if (grade >= 80) return "text-blue-600";
    if (grade >= 70) return "text-yellow-600";
    return "text-red-600";
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("es-ES", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};
</script>
