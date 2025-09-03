<template>
    <div class="space-y-6">
        <!-- Botón para crear horario -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    Gestión de Horarios
                </h3>
                <button
                    @click="showCreateModal = true"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        ></path>
                    </svg>
                    Crear Nuevo Horario
                </button>
            </div>
        </div>

        <!-- Vista de horarios por grado -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    Vista de Horarios por Grado
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
                <!-- Estadísticas del grado seleccionado -->
                <div v-if="selectedGrade" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-blue-600">
                                Total de Clases
                            </div>
                            <div class="text-2xl font-bold text-blue-900">
                                {{ totalClasses }}
                            </div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-green-600">
                                Cursos Únicos
                            </div>
                            <div class="text-2xl font-bold text-green-900">
                                {{ uniqueCourses }}
                            </div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-sm font-medium text-purple-600">
                                Profesores Asignados
                            </div>
                            <div class="text-2xl font-bold text-purple-900">
                                {{ uniqueTeachers }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horario semanal visual -->
                <div
                    v-if="selectedGrade && Object.keys(schedules).length > 0"
                    class="mb-6"
                >
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        Horario Semanal
                    </h4>
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                        <div
                            v-for="(daySchedules, day) in schedules"
                            :key="day"
                            class="space-y-2"
                        >
                            <h5
                                class="font-semibold text-gray-900 text-center py-2 bg-gray-50 rounded-md"
                            >
                                {{ getDayName(day) }}
                            </h5>
                            <div class="space-y-2">
                                <div
                                    v-for="schedule in daySchedules"
                                    :key="schedule.id"
                                    class="p-3 border rounded-lg bg-white shadow-sm"
                                >
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ schedule.course.name }}
                                    </div>
                                    <div
                                        class="text-xs text-indigo-600 font-medium"
                                    >
                                        {{ formatTime(schedule.start_time) }} -
                                        {{ formatTime(schedule.end_time) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ schedule.teacher.first_name }}
                                        {{ schedule.teacher.last_name }}
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

                <!-- Lista detallada -->
                <div v-if="selectedGrade && Object.keys(schedules).length > 0">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        Lista Detallada de Horarios
                    </h4>
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
                                        Profesor
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Aula
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Acciones
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
                                        {{ getDayName(schedule.day) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ formatTime(schedule.start_time) }} -
                                        {{ formatTime(schedule.end_time) }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ schedule.teacher.first_name }}
                                        {{ schedule.teacher.last_name }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    >
                                        {{ schedule.room || "No asignada" }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                    >
                                        <button
                                            class="text-indigo-600 hover:text-indigo-900 mr-3"
                                        >
                                            Editar
                                        </button>
                                        <button
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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

        <!-- Modal para crear horario -->
        <div
            v-if="showCreateModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <!-- Header del modal -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Crear Nuevo Horario
                        </h3>
                        <button
                            @click="closeModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Mensaje de éxito -->
                    <div
                        v-if="successMessage"
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
                                    {{ successMessage }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="createSchedule" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    for="modal_grade_id"
                                    class="block text-sm font-medium text-gray-700"
                                    >Grado</label
                                >
                                <select
                                    id="modal_grade_id"
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
                                        }}{{
                                            grade.section
                                                ? " " + grade.section
                                                : ""
                                        }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    for="modal_course_id"
                                    class="block text-sm font-medium text-gray-700"
                                    >Curso</label
                                >
                                <select
                                    id="modal_course_id"
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
                                    for="modal_teacher_id"
                                    class="block text-sm font-medium text-gray-700"
                                    >Profesor</label
                                >
                                <select
                                    id="modal_teacher_id"
                                    v-model="form.teacher_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                >
                                    <option value="">
                                        Seleccionar profesor
                                    </option>
                                    <option
                                        v-for="teacher in teachers"
                                        :key="teacher.id"
                                        :value="teacher.id"
                                    >
                                        {{ teacher.first_name }}
                                        {{ teacher.last_name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label
                                    for="modal_day"
                                    class="block text-sm font-medium text-gray-700"
                                    >Día</label
                                >
                                <select
                                    id="modal_day"
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
                                    for="modal_start_time"
                                    class="block text-sm font-medium text-gray-700"
                                    >Hora de Inicio</label
                                >
                                <input
                                    type="time"
                                    id="modal_start_time"
                                    v-model="form.start_time"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    for="modal_end_time"
                                    class="block text-sm font-medium text-gray-700"
                                    >Hora de Fin</label
                                >
                                <input
                                    type="time"
                                    id="modal_end_time"
                                    v-model="form.end_time"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    for="modal_room"
                                    class="block text-sm font-medium text-gray-700"
                                    >Sala (opcional)</label
                                >
                                <input
                                    type="text"
                                    id="modal_room"
                                    v-model="form.room"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="ej: Sala 101, Laboratorio"
                                />
                            </div>
                        </div>

                        <!-- Botones del modal -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                            >
                                <svg
                                    v-if="isSubmitting"
                                    class="animate-spin -ml-1 mr-3 h-4 w-4 text-white"
                                    xmlns="http://www.w3.org/2000/svg"
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
                                {{
                                    isSubmitting
                                        ? "Creando..."
                                        : "Crear Horario"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import { router } from "@inertiajs/vue3";

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
const showCreateModal = ref(false);
const isSubmitting = ref(false);
const successMessage = ref("");

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
        isSubmitting.value = true;
        await router.post(route("schedule-management.schedules.store"), form);

        // Mostrar mensaje de éxito
        successMessage.value = "¡Horario creado exitosamente!";

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

        // Recargar horarios si hay un grado seleccionado
        if (selectedGrade.value) {
            await loadSchedules();
        }

        // Cerrar modal después de 2 segundos
        setTimeout(() => {
            closeModal();
        }, 2000);
    } catch (error) {
        console.error("Error creating schedule:", error);
        successMessage.value = "Error al crear el horario. Inténtalo de nuevo.";
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    showCreateModal.value = false;
    successMessage.value = "";
    // Limpiar formulario
    form.grade_id = "";
    form.course_id = "";
    form.teacher_id = "";
    form.day = "";
    form.start_time = "";
    form.end_time = "";
    form.room = "";
};

const loadSchedules = async () => {
    if (!selectedGrade.value) {
        schedules.value = [];
        return;
    }

    try {
        // Hacer petición a la API para obtener horarios del grado seleccionado
        const response = await fetch(
            `/schedule-management/grades/${selectedGrade.value}/schedules`
        );
        const data = await response.json();

        // Convertir los datos agrupados por día en el formato esperado
        schedules.value = data;
    } catch (error) {
        console.error("Error loading schedules:", error);
        schedules.value = [];
    }
};

// Computed properties
const totalClasses = computed(() => {
    return Object.values(schedules.value).flat().length;
});

const uniqueCourses = computed(() => {
    const courses = Object.values(schedules.value)
        .flat()
        .map((schedule) => schedule.course.id);
    return [...new Set(courses)].length;
});

const uniqueTeachers = computed(() => {
    const teachers = Object.values(schedules.value)
        .flat()
        .map((schedule) => schedule.teacher.id);
    return [...new Set(teachers)].length;
});

const allSchedules = computed(() => {
    return Object.values(schedules.value).flat();
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
