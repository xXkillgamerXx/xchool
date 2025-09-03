<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Registro de Asistencia
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Profesor: {{ teacher.display_name || teacher.name }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Total de Clases</div>
                        <div class="text-2xl font-bold text-indigo-600">
                            {{ totalSchedules }}
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
                                Grados que Enseño
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ Object.keys(studentsByGrade).length }}
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
                                Total de Estudiantes
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ totalStudents }}
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
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Registros de Hoy
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ todayAttendanceCount }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horario Semanal con Botones de Asistencia -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Mis Clases - Registrar Asistencia
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Haz clic en una clase para registrar la asistencia de
                        hoy
                    </p>
                </div>
                <div class="p-6">
                    <div
                        v-if="Object.keys(schedules).length === 0"
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
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            No hay clases asignadas
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            No tienes horarios configurados aún.
                        </p>
                    </div>

                    <div v-else class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        <div
                            v-for="(daySchedules, day) in schedules"
                            :key="day"
                            class="space-y-2"
                        >
                            <h3
                                class="font-semibold text-gray-900 text-center py-2 bg-gray-50 rounded-md"
                            >
                                {{ getDayName(day) }}
                            </h3>
                            <div class="space-y-2">
                                <div
                                    v-for="schedule in daySchedules"
                                    :key="schedule.id"
                                    class="p-3 border rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow"
                                >
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ schedule.course.name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ schedule.grade.name }}
                                        <span v-if="schedule.grade.section"
                                            >-
                                            {{ schedule.grade.section }}</span
                                        >
                                    </div>
                                    <div
                                        class="text-xs text-indigo-600 font-medium"
                                    >
                                        {{ formatTime(schedule.start_time) }} -
                                        {{ formatTime(schedule.end_time) }}
                                    </div>
                                    <div
                                        v-if="schedule.room"
                                        class="text-xs text-gray-400"
                                    >
                                        Aula: {{ schedule.room }}
                                    </div>

                                    <!-- Botón para registrar asistencia -->
                                    <div class="mt-3">
                                        <Link
                                            :href="
                                                route(
                                                    'attendance.create',
                                                    schedule.id
                                                )
                                            "
                                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
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
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                            Registrar Asistencia
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                Estadísticas de Asistencia
                            </h3>
                            <p class="text-sm text-gray-600">
                                Ver reportes y estadísticas de asistencia por
                                grado
                            </p>
                        </div>
                        <div class="ml-4">
                            <Link
                                :href="route('attendance.statistics')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                Ver Estadísticas
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Ver Mis Estudiantes -->
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
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900">
                                Mis Estudiantes
                            </h3>
                            <p class="text-sm text-gray-600">
                                Ver lista completa de estudiantes por grado
                            </p>
                        </div>
                        <div class="ml-4">
                            <Link
                                :href="route('my-schedules.index')"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                Ver Estudiantes
                            </Link>
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
    schedules: Object,
    studentsByGrade: Object,
    teacher: Object,
});

// Computed
const totalSchedules = computed(() => {
    return Object.values(props.schedules).flat().length;
});

const totalStudents = computed(() => {
    return Object.values(props.studentsByGrade).reduce((total, gradeData) => {
        return total + gradeData.total_students;
    }, 0);
});

const todayAttendanceCount = computed(() => {
    // Por ahora retornamos 0, más adelante podemos implementar la lógica
    return 0;
});

// Methods
const getDayName = (day) => {
    const days = {
        monday: "Lunes",
        tuesday: "Martes",
        wednesday: "Miércoles",
        thursday: "Jueves",
        friday: "Viernes",
    };
    return days[day] || day;
};

const formatTime = (time) => {
    return time.substring(0, 5); // HH:MM
};
</script>
