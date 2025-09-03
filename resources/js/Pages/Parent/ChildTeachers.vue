<template>
    <ParentLayout :children="children" current-tab="teachers">
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
                                profesores.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de profesores -->
            <div v-else class="space-y-6">
                <!-- Header -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Profesores - {{ selectedChild.name }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        {{
                            selectedChild.grade
                                ? selectedChild.grade.full_name
                                : "Sin grado asignado"
                        }}
                    </p>
                </div>

                <!-- Mensaje si no hay profesores -->
                <div
                    v-if="Object.keys(teachers).length === 0"
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
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                        />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        No hay profesores asignados
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        El grado de tu hijo aún no tiene profesores asignados.
                    </p>
                </div>

                <!-- Lista de profesores -->
                <div
                    v-else
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <div
                        v-for="(teacherData, teacherId) in teachers"
                        :key="teacherId"
                        class="bg-white shadow rounded-lg overflow-hidden"
                    >
                        <div class="p-6">
                            <!-- Avatar del profesor -->
                            <div class="flex items-center space-x-4 mb-4">
                                <div
                                    class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center"
                                >
                                    <span
                                        class="text-green-600 font-medium text-lg"
                                    >
                                        {{
                                            getInitials(
                                                teacherData.teacher.name
                                            )
                                        }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        {{ teacherData.teacher.name }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ teacherData.teacher.email }}
                                    </p>
                                </div>
                            </div>

                            <!-- Cursos que imparte -->
                            <div class="mb-4">
                                <h4
                                    class="text-sm font-medium text-gray-700 mb-2"
                                >
                                    Cursos que imparte:
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        v-for="course in teacherData.courses"
                                        :key="course.id"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800"
                                    >
                                        {{ course.name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="text-sm text-gray-600">
                                <p>
                                    <span class="font-medium"
                                        >Total de clases:</span
                                    >
                                    {{ teacherData.total_classes }}
                                </p>
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
    teachers: Object,
    grade: Object,
    child: Object,
    children: Array,
    user: Object,
    message: String,
});

const getInitials = (name) => {
    if (!name) return "P";
    const names = name.split(" ");
    if (names.length >= 2) {
        return (names[0][0] + names[1][0]).toUpperCase();
    }
    return names[0][0].toUpperCase();
};
</script>
