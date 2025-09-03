<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="py-6 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header elegante -->
                <div
                    class="bg-white shadow-lg rounded-xl p-6 mb-6 border-l-4 border-indigo-500"
                >
                    <div class="flex items-center space-x-4">
                        <div class="bg-indigo-100 rounded-full p-3">
                            <svg
                                class="h-6 w-6 text-indigo-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Horario de Mi Hijo
                            </h1>
                            <p class="mt-1 text-gray-600">
                                {{
                                    child
                                        ? `Horario de ${child.name}`
                                        : "Horario del estudiante"
                                }}
                                {{ grade ? ` - Grado: ${grade.name}` : "" }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Mensaje si no hay hijo asignado -->
                <div
                    v-if="!child"
                    class="bg-yellow-50 border border-yellow-200 rounded-xl p-6"
                >
                    <div class="flex items-center space-x-3">
                        <div class="bg-yellow-100 rounded-full p-2">
                            <svg
                                class="h-5 w-5 text-yellow-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-yellow-800">
                                Hijo no asignado
                            </h3>
                            <p class="mt-1 text-yellow-700">{{ message }}</p>
                        </div>
                    </div>
                </div>

                <!-- Horario semanal -->
                <div
                    v-else
                    class="bg-white shadow-lg rounded-xl overflow-hidden"
                >
                    <div class="bg-indigo-600 px-6 py-4 text-white">
                        <div class="flex items-center space-x-3">
                            <div
                                class="bg-white bg-opacity-20 rounded-full p-2"
                            >
                                <svg
                                    class="h-5 w-5 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold">
                                Horario Semanal
                            </h2>
                        </div>
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
                            El grado de tu hijo aún no tiene horarios asignados.
                        </p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-indigo-50"
                                    >
                                        Hora
                                    </th>
                                    <th
                                        v-for="(day, index) in days"
                                        :key="day"
                                        class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        :class="getDayHeaderClass(index)"
                                    >
                                        {{ day }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="timeSlot in timeSlots"
                                    :key="timeSlot"
                                    class="hover:bg-gray-50"
                                >
                                    <td
                                        class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-indigo-50"
                                    >
                                        {{ timeSlot }}
                                    </td>
                                    <td
                                        v-for="(day, index) in days"
                                        :key="day"
                                        class="px-4 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        <div
                                            v-if="
                                                getScheduleForDayAndTime(
                                                    day,
                                                    timeSlot
                                                )
                                            "
                                            class="rounded-lg p-3 shadow-sm border-l-4"
                                            :class="getSubjectCardClass(index)"
                                        >
                                            <div
                                                class="font-medium text-gray-900"
                                            >
                                                {{
                                                    getScheduleForDayAndTime(
                                                        day,
                                                        timeSlot
                                                    ).course.name
                                                }}
                                            </div>
                                            <div
                                                class="text-sm text-gray-600 mt-1"
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
                    v-if="child && Object.keys(schedules).length > 0"
                    class="mt-6 bg-white shadow-lg rounded-xl"
                >
                    <div class="bg-green-600 px-6 py-4 text-white rounded-t-xl">
                        <div class="flex items-center space-x-3">
                            <div
                                class="bg-white bg-opacity-20 rounded-full p-2"
                            >
                                <svg
                                    class="h-5 w-5 text-white"
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
                            <h2 class="text-xl font-semibold">
                                Detalle de Horarios
                            </h2>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div
                            v-for="(daySchedules, day, index) in schedules"
                            :key="day"
                            class="p-6"
                        >
                            <h3
                                class="text-lg font-medium text-gray-900 mb-4 flex items-center"
                            >
                                <div
                                    class="w-3 h-3 rounded-full mr-3"
                                    :class="getDayColor(index)"
                                ></div>
                                {{ day }}
                            </h3>
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                            >
                                <div
                                    v-for="schedule in daySchedules"
                                    :key="schedule.id"
                                    class="bg-gray-50 rounded-lg p-4 border-l-4 hover:shadow-md transition-shadow"
                                    :class="getSubjectBorderClass(index)"
                                >
                                    <div
                                        class="flex items-center justify-between mb-2"
                                    >
                                        <h4 class="font-medium text-gray-900">
                                            {{ schedule.course.name }}
                                        </h4>
                                        <span
                                            class="text-sm text-gray-500 bg-white px-2 py-1 rounded"
                                        >
                                            {{
                                                formatTime(schedule.start_time)
                                            }}
                                            -
                                            {{ formatTime(schedule.end_time) }}
                                        </span>
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
    child: Object,
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

// Métodos para colores
const getDayHeaderClass = (index) => {
    const colors = [
        "bg-blue-50 text-blue-700",
        "bg-green-50 text-green-700",
        "bg-yellow-50 text-yellow-700",
        "bg-purple-50 text-purple-700",
        "bg-pink-50 text-pink-700",
    ];
    return colors[index % colors.length];
};

const getSubjectCardClass = (index) => {
    const colors = [
        "bg-blue-50 border-blue-400",
        "bg-green-50 border-green-400",
        "bg-yellow-50 border-yellow-400",
        "bg-purple-50 border-purple-400",
        "bg-pink-50 border-pink-400",
    ];
    return colors[index % colors.length];
};

const getDayColor = (index) => {
    const colors = [
        "bg-blue-500",
        "bg-green-500",
        "bg-yellow-500",
        "bg-purple-500",
        "bg-pink-500",
    ];
    return colors[index % colors.length];
};

const getSubjectBorderClass = (index) => {
    const colors = [
        "border-blue-400",
        "border-green-400",
        "border-yellow-400",
        "border-purple-400",
        "border-pink-400",
    ];
    return colors[index % colors.length];
};
</script>
