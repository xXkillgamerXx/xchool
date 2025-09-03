<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Registrar Asistencia
                        </h1>
                        <div class="mt-2 space-y-1">
                            <p class="text-lg text-gray-700">
                                <span class="font-semibold">{{
                                    schedule.course.name
                                }}</span>
                                - {{ schedule.grade.name }}
                                <span v-if="schedule.grade.section"
                                    >- {{ schedule.grade.section }}</span
                                >
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ getDayName(schedule.day) }} -
                                {{ formatTime(schedule.start_time) }} -
                                {{ formatTime(schedule.end_time) }}
                                <span v-if="schedule.room"
                                    >| Aula: {{ schedule.room }}</span
                                >
                            </p>
                            <p class="text-sm text-indigo-600 font-medium">
                                Fecha: {{ formatDate(attendanceDate) }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">
                            Total de Estudiantes
                        </div>
                        <div class="text-2xl font-bold text-indigo-600">
                            {{ students.length }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Asistencia -->
            <form
                @submit.prevent="submitAttendance"
                class="bg-white shadow rounded-lg"
            >
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Lista de Estudiantes
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Marca la asistencia de cada estudiante
                    </p>
                </div>

                <div class="p-6">
                    <div
                        v-if="students.length === 0"
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
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                            />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            No hay estudiantes
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            No hay estudiantes asignados a este grado.
                        </p>
                    </div>

                    <div v-else class="space-y-4">
                        <!-- Controles Rápidos -->
                        <div
                            class="flex flex-wrap gap-2 p-4 bg-gray-50 rounded-lg"
                        >
                            <button
                                type="button"
                                @click="markAllPresent"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
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
                                Marcar Todos Presentes
                            </button>
                            <button
                                type="button"
                                @click="markAllAbsent"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
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
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                                Marcar Todos Ausentes
                            </button>
                            <button
                                type="button"
                                @click="clearAll"
                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>
                                Limpiar Todo
                            </button>
                        </div>

                        <!-- Lista de Estudiantes -->
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                        >
                            <div
                                v-for="student in students"
                                :key="student.id"
                                class="p-4 border rounded-lg bg-white shadow-sm"
                            >
                                <!-- Información del Estudiante -->
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center"
                                        >
                                            <span
                                                class="text-indigo-600 font-medium text-sm"
                                            >
                                                {{ getInitials(student.name) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4
                                            class="text-sm font-medium text-gray-900 truncate"
                                        >
                                            {{ student.name }}
                                        </h4>
                                        <p
                                            class="text-xs text-gray-500 truncate"
                                        >
                                            {{ student.email }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Opciones de Asistencia -->
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <input
                                            :id="`present-${student.id}`"
                                            v-model="
                                                attendance[student.id].status
                                            "
                                            type="radio"
                                            value="present"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300"
                                        />
                                        <label
                                            :for="`present-${student.id}`"
                                            class="text-sm text-gray-700"
                                        >
                                            <span
                                                class="inline-flex items-center"
                                            >
                                                <span
                                                    class="w-2 h-2 bg-green-500 rounded-full mr-2"
                                                ></span>
                                                Presente
                                            </span>
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <input
                                            :id="`absent-${student.id}`"
                                            v-model="
                                                attendance[student.id].status
                                            "
                                            type="radio"
                                            value="absent"
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300"
                                        />
                                        <label
                                            :for="`absent-${student.id}`"
                                            class="text-sm text-gray-700"
                                        >
                                            <span
                                                class="inline-flex items-center"
                                            >
                                                <span
                                                    class="w-2 h-2 bg-red-500 rounded-full mr-2"
                                                ></span>
                                                Ausente
                                            </span>
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <input
                                            :id="`late-${student.id}`"
                                            v-model="
                                                attendance[student.id].status
                                            "
                                            type="radio"
                                            value="late"
                                            class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300"
                                        />
                                        <label
                                            :for="`late-${student.id}`"
                                            class="text-sm text-gray-700"
                                        >
                                            <span
                                                class="inline-flex items-center"
                                            >
                                                <span
                                                    class="w-2 h-2 bg-yellow-500 rounded-full mr-2"
                                                ></span>
                                                Tardanza
                                            </span>
                                        </label>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <input
                                            :id="`excused-${student.id}`"
                                            v-model="
                                                attendance[student.id].status
                                            "
                                            type="radio"
                                            value="excused"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                        />
                                        <label
                                            :for="`excused-${student.id}`"
                                            class="text-sm text-gray-700"
                                        >
                                            <span
                                                class="inline-flex items-center"
                                            >
                                                <span
                                                    class="w-2 h-2 bg-blue-500 rounded-full mr-2"
                                                ></span>
                                                Justificado
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Campo de Notas -->
                                <div class="mt-3">
                                    <label
                                        :for="`notes-${student.id}`"
                                        class="block text-xs font-medium text-gray-700 mb-1"
                                    >
                                        Notas (opcional)
                                    </label>
                                    <textarea
                                        :id="`notes-${student.id}`"
                                        v-model="attendance[student.id].notes"
                                        rows="2"
                                        class="block w-full text-xs border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Observaciones..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div
                    class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between"
                >
                    <Link
                        :href="route('attendance.index')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        Cancelar
                    </Link>

                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg
                            v-if="isSubmitting"
                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        <svg
                            v-else
                            class="w-4 h-4 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                        {{
                            isSubmitting ? "Guardando..." : "Guardar Asistencia"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive } from "vue";
import { Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

// Props
const props = defineProps({
    schedule: Object,
    students: Array,
    existingAttendance: Object,
    attendanceDate: String,
});

// Reactive data
const isSubmitting = ref(false);

// Initialize attendance data reactively
const attendance = reactive(
    props.students.reduce((acc, student) => {
        const existing = props.existingAttendance[student.id];
        acc[student.id] = {
            student_id: student.id,
            status: existing ? existing.status : "present",
            notes: existing ? existing.notes : "",
        };
        return acc;
    }, {})
);

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

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("es-ES", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const getInitials = (name) => {
    return name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .substring(0, 2);
};

const markAllPresent = () => {
    props.students.forEach((student) => {
        attendance[student.id].status = "present";
    });
};

const markAllAbsent = () => {
    props.students.forEach((student) => {
        attendance[student.id].status = "absent";
    });
};

const clearAll = () => {
    props.students.forEach((student) => {
        attendance[student.id].status = "";
        attendance[student.id].notes = "";
    });
};

const submitAttendance = () => {
    isSubmitting.value = true;

    const attendanceData = Object.values(attendance).map((item) => ({
        student_id: item.student_id,
        status: item.status,
        notes: item.notes,
    }));

    router.post(
        route("attendance.store"),
        {
            schedule_id: props.schedule.id,
            attendance_date: props.attendanceDate,
            attendance: attendanceData,
        },
        {
            onFinish: () => {
                isSubmitting.value = false;
            },
        }
    );
};
</script>
