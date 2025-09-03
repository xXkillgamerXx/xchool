<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Mi Horario</h1>
                    <p class="mt-2 text-gray-600">
                        {{
                            grade
                                ? `Grado: ${grade.full_name || grade.name}`
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

                <!-- Horario semanal -->
                <div v-else class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">
                            Horario Semanal
                        </h2>
                    </div>

                    <div
                        v-if="Object.keys(schedules).length === 0"
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
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            No hay horarios configurados
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Tu grado aún no tiene horarios asignados.
                        </p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Hora
                                    </th>
                                    <th
                                        v-for="day in days"
                                        :key="day"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        {{ day }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="timeSlot in timeSlots"
                                    :key="timeSlot"
                                >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        {{ timeSlot }}
                                    </td>
                                    <td
                                        v-for="day in days"
                                        :key="day"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        <div
                                            v-if="
                                                getScheduleForDayAndTime(
                                                    day,
                                                    timeSlot
                                                )
                                            "
                                            class="bg-indigo-50 border border-indigo-200 rounded-lg p-3"
                                        >
                                            <div
                                                class="font-medium text-indigo-900"
                                            >
                                                {{
                                                    getScheduleForDayAndTime(
                                                        day,
                                                        timeSlot
                                                    ).course.name
                                                }}
                                            </div>
                                            <div
                                                class="text-sm text-indigo-700"
                                            >
                                                {{
                                                    getScheduleForDayAndTime(
                                                        day,
                                                        timeSlot
                                                    ).teacher.name
                                                }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Lista detallada de horarios -->
                <div
                    v-if="grade && Object.keys(schedules).length > 0"
                    class="mt-6 bg-white shadow rounded-lg"
                >
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">
                            Detalle de Horarios
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div
                            v-for="(daySchedules, day) in schedules"
                            :key="day"
                            class="p-6"
                        >
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ day }}
                            </h3>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="schedule in daySchedules"
                                    :key="schedule.id"
                                    class="bg-gray-50 rounded-lg p-4"
                                >
                                    <div
                                        class="flex items-center justify-between mb-2"
                                    >
                                        <h4 class="font-medium text-gray-900">
                                            {{ schedule.course.name }}
                                        </h4>
                                        <span class="text-sm text-gray-500"
                                            >{{
                                                formatTime(schedule.start_time)
                                            }}
                                            -
                                            {{
                                                formatTime(schedule.end_time)
                                            }}</span
                                        >
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium"
                                            >Profesor:</span
                                        >
                                        {{ schedule.teacher.name }}
                                    </p>
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
    schedules: Object,
    grade: Object,
    user: Object,
    message: String,
});

const days = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes"];

const timeSlots = computed(() => {
    const slots = new Set();

    Object.values(props.schedules).forEach((daySchedules) => {
        daySchedules.forEach((schedule) => {
            slots.add(
                `${formatTime(schedule.start_time)} - ${formatTime(
                    schedule.end_time
                )}`
            );
        });
    });

    return Array.from(slots).sort();
});

const getScheduleForDayAndTime = (day, timeSlot) => {
    const dayKey = getDayKey(day);
    if (!props.schedules[dayKey]) return null;

    return props.schedules[dayKey].find((schedule) => {
        const scheduleTime = `${formatTime(schedule.start_time)} - ${formatTime(
            schedule.end_time
        )}`;
        return scheduleTime === timeSlot;
    });
};

const getDayKey = (day) => {
    const dayMap = {
        Lunes: "monday",
        Martes: "tuesday",
        Miércoles: "wednesday",
        Jueves: "thursday",
        Viernes: "friday",
    };
    return dayMap[day];
};

const formatTime = (time) => {
    return new Date(`2000-01-01T${time}`).toLocaleTimeString("es-ES", {
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>
