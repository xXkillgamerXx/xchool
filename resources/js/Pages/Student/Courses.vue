<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Mis Cursos</h1>
                    <p class="mt-2 text-gray-600">
                        {{
                            grade
                                ? `Grado: ${grade.name}`
                                : "No tienes un grado asignado"
                        }}
                    </p>
                </div>

                <!-- Mensaje si no hay grado asignado -->
                <div
                    v-if="!grade"
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
                                Grado no asignado
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>{{ message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div
                    v-if="grade"
                    class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6"
                >
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-indigo-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Total de Cursos
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ Object.keys(courses).length }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-green-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Horas Totales
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ totalHours }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-blue-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Profesores
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ uniqueTeachers }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de cursos -->
                <div v-if="grade" class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">
                            Detalle de Cursos
                        </h2>
                    </div>

                    <div
                        v-if="Object.keys(courses).length === 0"
                        class="p-6 text-center text-gray-500"
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
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            No hay cursos asignados
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Tu grado aún no tiene cursos configurados.
                        </p>
                    </div>

                    <div v-else class="divide-y divide-gray-200">
                        <div
                            v-for="(courseData, courseId) in courses"
                            :key="courseId"
                            class="p-6"
                        >
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        {{ courseData.course.name }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{
                                            courseData.course.description ||
                                            "Sin descripción"
                                        }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ courseData.total_hours }} horas
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        por semana
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <span
                                        class="text-sm font-medium text-gray-700"
                                        >Profesor:</span
                                    >
                                    <span class="text-sm text-gray-900">{{
                                        courseData.teacher.name
                                    }}</span>
                                </div>

                                <div class="mt-3">
                                    <h4
                                        class="text-sm font-medium text-gray-700 mb-2"
                                    >
                                        Horarios:
                                    </h4>
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2"
                                    >
                                        <div
                                            v-for="schedule in courseData.schedules"
                                            :key="schedule.id"
                                            class="bg-white rounded border p-2 text-sm"
                                        >
                                            <div
                                                class="font-medium text-gray-900"
                                            >
                                                {{ getDayName(schedule.day) }}
                                            </div>
                                            <div class="text-gray-600">
                                                {{
                                                    formatTime(
                                                        schedule.start_time
                                                    )
                                                }}
                                                -
                                                {{
                                                    formatTime(
                                                        schedule.end_time
                                                    )
                                                }}
                                            </div>
                                        </div>
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
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    courses: Object,
    grade: Object,
    user: Object,
    message: String,
});

const totalHours = computed(() => {
    return Object.values(props.courses).reduce((total, courseData) => {
        return total + courseData.total_hours;
    }, 0);
});

const uniqueTeachers = computed(() => {
    const teachers = new Set();
    Object.values(props.courses).forEach((courseData) => {
        teachers.add(courseData.teacher.id);
    });
    return teachers.size;
});

const getDayName = (day) => {
    const dayMap = {
        monday: "Lunes",
        tuesday: "Martes",
        wednesday: "Miércoles",
        thursday: "Jueves",
        friday: "Viernes",
    };
    return dayMap[day] || day;
};

const formatTime = (time) => {
    return new Date(`2000-01-01T${time}`).toLocaleTimeString("es-ES", {
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>
