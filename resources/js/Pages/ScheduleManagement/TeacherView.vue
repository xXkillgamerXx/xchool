<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Mis Horarios
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Profesor: {{ teacher.display_name || teacher.name }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Total de Clases</div>
                        <div class="text-2xl font-bold text-indigo-600">
                            {{ totalClasses }}
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
                                    ></path>
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
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Cursos que Imparto
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
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Horas por Semana
                            </p>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ totalHours }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horario Semanal -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Mi Horario Semanal
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        <div
                            v-for="(schedules, day) in mySchedules"
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
                                    v-for="schedule in schedules"
                                    :key="schedule.id"
                                    class="p-3 border rounded-lg bg-white shadow-sm"
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Mis Clases -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Mis Clases Asignadas
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Curso
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Grado
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Día
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Horario
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Aula
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="schedule in allSchedules"
                                :key="schedule.id"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-3 h-3 rounded-full mr-3"
                                            :style="{
                                                backgroundColor:
                                                    schedule.course.color,
                                            }"
                                        ></div>
                                        <div
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ schedule.course.name }}
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ schedule.grade.name }}
                                    <span
                                        v-if="schedule.grade.section"
                                        class="text-gray-500"
                                    >
                                        - {{ schedule.grade.section }}
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ getDayName(schedule.day) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ formatTime(schedule.start_time) }} -
                                    {{ formatTime(schedule.end_time) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ schedule.room || "No asignada" }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

// Props
const props = defineProps({
    mySchedules: Object,
    myGrades: Array,
    myCourses: Array,
    userRole: String,
    teacher: Object,
});

// Computed
const totalClasses = computed(() => {
    return Object.values(props.mySchedules).flat().length;
});

const totalHours = computed(() => {
    let totalMinutes = 0;
    Object.values(props.mySchedules)
        .flat()
        .forEach((schedule) => {
            const start = new Date(`2000-01-01T${schedule.start_time}`);
            const end = new Date(`2000-01-01T${schedule.end_time}`);
            totalMinutes += (end - start) / (1000 * 60);
        });
    return Math.round(totalMinutes / 60);
});

const allSchedules = computed(() => {
    return Object.values(props.mySchedules).flat();
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
