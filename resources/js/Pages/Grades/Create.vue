<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Asignar Calificaciones
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Profesor: {{ teacher.display_name || teacher.name }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Fecha Actual</div>
                        <div class="text-2xl font-bold text-indigo-600">
                            {{ formatDate(today) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de Calificaciones -->
            <form
                @submit.prevent="submitGrades"
                class="bg-white shadow rounded-lg"
            >
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">
                        Información de la Tarea/Examen
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Configura los detalles de la evaluación
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Información Básica -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                for="course_id"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Curso *
                            </label>
                            <select
                                id="course_id"
                                v-model="form.course_id"
                                @change="onCourseChange"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            >
                                <option value="">Selecciona un curso</option>
                                <option
                                    v-for="course in myCourses"
                                    :key="course.id"
                                    :value="course.id"
                                >
                                    {{ course.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label
                                for="grade_id"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Grado *
                            </label>
                            <select
                                id="grade_id"
                                v-model="form.grade_id"
                                @change="onGradeChange"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            >
                                <option value="">Selecciona un grado</option>
                                <option
                                    v-for="grade in myGrades"
                                    :key="grade.id"
                                    :value="grade.id"
                                >
                                    {{ grade.name }}
                                    <span v-if="grade.section"
                                        >- {{ grade.section }}</span
                                    >
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                for="assignment_name"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Nombre de la Tarea/Examen *
                            </label>
                            <input
                                id="assignment_name"
                                v-model="form.assignment_name"
                                type="text"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Ej: Examen de Matemáticas - Unidad 3"
                                required
                            />
                        </div>

                        <div>
                            <label
                                for="type"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Tipo de Evaluación *
                            </label>
                            <select
                                id="type"
                                v-model="form.type"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            >
                                <option value="exam">Examen</option>
                                <option value="homework">Tarea</option>
                                <option value="project">Proyecto</option>
                                <option value="quiz">Quiz</option>
                                <option value="participation">
                                    Participación
                                </option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                for="max_score"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Puntuación Máxima *
                            </label>
                            <input
                                id="max_score"
                                v-model="form.max_score"
                                type="number"
                                min="1"
                                max="1000"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="100"
                                required
                            />
                        </div>

                        <div>
                            <label
                                for="grade_date"
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Fecha de Calificación *
                            </label>
                            <input
                                id="grade_date"
                                v-model="form.grade_date"
                                type="date"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            />
                        </div>
                    </div>
                </div>

                <!-- Lista de Estudiantes -->
                <div
                    v-if="students.length > 0"
                    class="px-6 py-4 border-t border-gray-200"
                >
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Calificaciones de Estudiantes
                    </h3>

                    <!-- Controles Rápidos -->
                    <div
                        class="flex flex-wrap gap-2 p-4 bg-gray-50 rounded-lg mb-6"
                    >
                        <button
                            type="button"
                            @click="setAllScores(100)"
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
                            Marcar Todos 100%
                        </button>
                        <button
                            type="button"
                            @click="setAllScores(80)"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
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
                            Marcar Todos 80%
                        </button>
                        <button
                            type="button"
                            @click="setAllScores(60)"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
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
                            Marcar Todos 60%
                        </button>
                        <button
                            type="button"
                            @click="clearAllScores"
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
                            <div class="flex items-center space-x-3 mb-4">
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
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ student.email }}
                                    </p>
                                </div>
                            </div>

                            <!-- Campo de Calificación -->
                            <div class="space-y-3">
                                <div>
                                    <label
                                        :for="`score-${student.id}`"
                                        class="block text-xs font-medium text-gray-700 mb-1"
                                    >
                                        Puntuación (0 - {{ form.max_score }})
                                    </label>
                                    <input
                                        :id="`score-${student.id}`"
                                        v-model="
                                            studentGrades[student.id].score
                                        "
                                        type="number"
                                        :min="0"
                                        :max="form.max_score"
                                        step="0.01"
                                        class="block w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="0"
                                    />
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{
                                            getPercentage(
                                                studentGrades[student.id].score,
                                                form.max_score
                                            )
                                        }}%
                                    </div>
                                </div>

                                <!-- Campo de Comentarios -->
                                <div>
                                    <label
                                        :for="`comments-${student.id}`"
                                        class="block text-xs font-medium text-gray-700 mb-1"
                                    >
                                        Comentarios (opcional)
                                    </label>
                                    <textarea
                                        :id="`comments-${student.id}`"
                                        v-model="
                                            studentGrades[student.id].comments
                                        "
                                        rows="2"
                                        class="block w-full text-xs border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Observaciones..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje cuando no hay estudiantes -->
                <div
                    v-else-if="form.grade_id"
                    class="px-6 py-8 text-center text-gray-500"
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
                        Cargando estudiantes...
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Selecciona un grado para ver los estudiantes.
                    </p>
                </div>

                <!-- Botones de Acción -->
                <div
                    class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between"
                >
                    <Link
                        :href="route('grades.index')"
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
                        :disabled="isSubmitting || students.length === 0"
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
                        {{
                            isSubmitting
                                ? "Guardando..."
                                : "Guardar Calificaciones"
                        }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

// Props
const props = defineProps({
    myCourses: Array,
    myGrades: Array,
    teacher: Object,
});

// Reactive data
const isSubmitting = ref(false);
const students = ref([]);
const today = new Date().toISOString().split("T")[0];

// Form data
const form = reactive({
    course_id: "",
    grade_id: "",
    assignment_name: "",
    type: "exam",
    max_score: 100,
    grade_date: today,
});

// Student grades data
const studentGrades = reactive({});

// Methods
const formatDate = (date) => {
    return new Date(date).toLocaleDateString("es-ES", {
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

const getPercentage = (score, maxScore) => {
    if (maxScore > 0 && score) {
        return Math.round((score / maxScore) * 100);
    }
    return 0;
};

const onCourseChange = () => {
    // Reset grade selection when course changes
    form.grade_id = "";
    students.value = [];
    Object.keys(studentGrades).forEach((key) => delete studentGrades[key]);
};

const onGradeChange = async () => {
    if (!form.grade_id) {
        students.value = [];
        Object.keys(studentGrades).forEach((key) => delete studentGrades[key]);
        return;
    }

    try {
        const response = await fetch(route("grades.students", form.grade_id));
        const data = await response.json();

        students.value = data;

        // Initialize student grades
        data.forEach((student) => {
            studentGrades[student.id] = {
                student_id: student.id,
                score: "",
                comments: "",
            };
        });
    } catch (error) {
        console.error("Error loading students:", error);
    }
};

const setAllScores = (percentage) => {
    const score = (percentage / 100) * form.max_score;
    students.value.forEach((student) => {
        studentGrades[student.id].score = score;
    });
};

const clearAllScores = () => {
    students.value.forEach((student) => {
        studentGrades[student.id].score = "";
        studentGrades[student.id].comments = "";
    });
};

const submitGrades = () => {
    isSubmitting.value = true;

    const grades = Object.values(studentGrades).filter(
        (grade) => grade.score !== "" && grade.score !== null
    );

    router.post(
        route("grades.store"),
        {
            ...form,
            grades: grades,
        },
        {
            onFinish: () => {
                isSubmitting.value = false;
            },
        }
    );
};

// Watch for max_score changes to update percentages
watch(
    () => form.max_score,
    () => {
        // Trigger reactivity for percentage calculations
        students.value.forEach((student) => {
            if (studentGrades[student.id]) {
                const currentScore = studentGrades[student.id].score;
                studentGrades[student.id].score = currentScore;
            }
        });
    }
);
</script>
