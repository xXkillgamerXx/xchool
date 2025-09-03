<template>
    <div class="space-y-6">
        <!-- Formulario para crear horario -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                Crear Nuevo Horario
            </h3>

            <!-- Mensaje de éxito -->
            <div
                v-if="$page.props.flash && $page.props.flash.success"
                class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md"
            >
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg
                            class="h-5 w-5 text-green-400"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ $page.props.flash.success }}
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="createSchedule" class="space-y-4">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <div>
                        <label
                            for="grade_id"
                            class="block text-sm font-medium text-gray-700"
                            >Grado</label
                        >
                        <select
                            id="grade_id"
                            v-model="form.grade_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required
                        >
                            <option value="">Seleccionar grado</option>
                            <option
                                v-for="grade in grades"
                                :key="grade.id"
                                :value="grade.id"
                            >
                                {{ grade.name
                                }}{{ grade.section ? " " + grade.section : "" }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            for="course_id"
                            class="block text-sm font-medium text-gray-700"
                            >Curso</label
                        >
                        <select
                            id="course_id"
                            v-model="form.course_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required
                        >
                            <option value="">Seleccionar curso</option>
                            <option
                                v-for="course in courses"
                                :key="course.id"
                                :value="course.id"
                            >
                                {{ course.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            for="teacher_id"
                            class="block text-sm font-medium text-gray-700"
                            >Profesor</label
                        >
                        <select
                            id="teacher_id"
                            v-model="form.teacher_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required
                        >
                            <option value="">Seleccionar profesor</option>
                            <option
                                v-for="teacher in teachers"
                                :key="teacher.id"
                                :value="teacher.id"
                            >
                                {{ teacher.first_name }} {{ teacher.last_name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <label
                            for="day"
                            class="block text-sm font-medium text-gray-700"
                            >Día</label
                        >
                        <select
                            id="day"
                            v-model="form.day"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required
                        >
                            <option value="">Seleccionar día</option>
                            <option value="monday">Lunes</option>
                            <option value="tuesday">Martes</option>
                            <option value="wednesday">Miércoles</option>
                            <option value="thursday">Jueves</option>
                            <option value="friday">Viernes</option>
                        </select>
                    </div>
                    <div>
                        <label
                            for="start_time"
                            class="block text-sm font-medium text-gray-700"
                            >Hora de Inicio</label
                        >
                        <input
                            type="time"
                            id="start_time"
                            v-model="form.start_time"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required
                        />
                    </div>
                    <div>
                        <label
                            for="end_time"
                            class="block text-sm font-medium text-gray-700"
                            >Hora de Fin</label
                        >
                        <input
                            type="time"
                            id="end_time"
                            v-model="form.end_time"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            required
                        />
                    </div>
                    <div>
                        <label
                            for="room"
                            class="block text-sm font-medium text-gray-700"
                            >Sala (opcional)</label
                        >
                        <input
                            type="text"
                            id="room"
                            v-model="form.room"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="ej: Sala 101, Laboratorio"
                        />
                    </div>
                </div>
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Crear Horario
                    </button>
                </div>
            </form>
        </div>

        <!-- Vista de horarios por grado -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    Vista de Horarios
                </h3>
                <div class="mt-2">
                    <label
                        for="view_grade"
                        class="block text-sm font-medium text-gray-700"
                        >Seleccionar Grado para Ver Horarios</label
                    >
                    <select
                        id="view_grade"
                        v-model="selectedGrade"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        @change="loadSchedules"
                    >
                        <option value="">Seleccionar grado</option>
                        <option
                            v-for="grade in grades"
                            :key="grade.id"
                            :value="grade.id"
                        >
                            {{ grade.name
                            }}{{ grade.section ? " " + grade.section : "" }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="p-6">
                <div
                    v-if="selectedGrade && schedules.length > 0"
                    class="space-y-4"
                >
                    <div
                        v-for="(daySchedules, day) in schedules"
                        :key="day"
                        class="border rounded-lg p-4"
                    >
                        <h4 class="text-lg font-medium text-gray-900 mb-3">
                            {{ getDayName(day) }}
                        </h4>
                        <div class="space-y-2">
                            <div
                                v-for="schedule in daySchedules"
                                :key="schedule.id"
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                            >
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-4 h-4 rounded"
                                        :style="{
                                            backgroundColor:
                                                schedule.course.color,
                                        }"
                                    ></div>
                                    <span class="font-medium">{{
                                        schedule.course.name
                                    }}</span>
                                    <span class="text-sm text-gray-500">{{
                                        schedule.time_range
                                    }}</span>
                                    <span class="text-sm text-gray-500"
                                        >{{ schedule.teacher.first_name }}
                                        {{ schedule.teacher.last_name }}</span
                                    >
                                    <span
                                        v-if="schedule.room"
                                        class="text-sm text-gray-500"
                                        >({{ schedule.room }})</span
                                    >
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        class="text-indigo-600 hover:text-indigo-900 text-sm"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        class="text-red-600 hover:text-red-900 text-sm"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    v-else-if="selectedGrade"
                    class="text-center text-gray-500 py-8"
                >
                    No hay horarios configurados para este grado
                </div>
                <div v-else class="text-center text-gray-500 py-8">
                    Selecciona un grado para ver sus horarios
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from "vue";

// Props
const props = defineProps({
    grades: Array,
    courses: Array,
    teachers: Array,
});

// Emits
const emit = defineEmits(["schedule-created"]);

// Estado reactivo
const selectedGrade = ref("");
const schedules = ref([]);

// Formulario
const form = reactive({
    grade_id: "",
    course_id: "",
    teacher_id: "",
    day: "",
    start_time: "",
    end_time: "",
    room: "",
});

// Métodos
const createSchedule = async () => {
    try {
        await router.post(route("schedule-management.schedules.store"), form);

        // Limpiar formulario después de crear
        form.grade_id = "";
        form.course_id = "";
        form.teacher_id = "";
        form.day = "";
        form.start_time = "";
        form.end_time = "";
        form.room = "";

        // Emitir evento para refrescar datos
        emit("schedule-created");

        // Mostrar mensaje de éxito (Laravel maneja esto automáticamente)
    } catch (error) {
        console.error("Error creating schedule:", error);
    }
};

const loadSchedules = () => {
    if (!selectedGrade.value) {
        schedules.value = [];
        return;
    }

    // Simular carga de horarios (aquí se haría la llamada a la API)
    schedules.value = [];
};

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
</script>
