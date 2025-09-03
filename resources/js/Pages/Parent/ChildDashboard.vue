<template>
    <ParentLayout :children="children" current-tab="dashboard">
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
                                Por favor selecciona un estudiante para ver su
                                información completa.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard completo del estudiante -->
            <div v-else class="space-y-6">
                <!-- Header del estudiante -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-purple-600"
                    >
                        <div class="flex items-center space-x-4">
                            <!-- Avatar del estudiante -->
                            <div
                                class="h-16 w-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center"
                            >
                                <span class="text-white font-medium text-2xl">
                                    {{ getInitials(selectedChild.name) }}
                                </span>
                            </div>

                            <!-- Información del estudiante -->
                            <div class="flex-1 text-white">
                                <h1 class="text-2xl font-bold">
                                    {{ selectedChild.name }}
                                </h1>
                                <p class="text-indigo-100">
                                    {{ selectedChild.email }}
                                </p>
                                <div class="mt-2 flex items-center space-x-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white"
                                    >
                                        Estudiante
                                    </span>
                                    <span
                                        v-if="selectedChild.grade"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 text-white"
                                    >
                                        {{ selectedChild.grade.full_name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas rápidas -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Asistencia -->
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
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Asistencia
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{
                                                attendance.attendance_percentage
                                            }}%
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Promedio General -->
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
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Promedio
                                        </dt>
                                        <dd
                                            :class="[
                                                getGradeColor(
                                                    grades.overall_average
                                                ),
                                                'text-lg font-medium',
                                            ]"
                                        >
                                            {{ grades.overall_average }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Profesores -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-purple-600"
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
                                            {{ Object.keys(teachers).length }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de Cursos -->
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
                                            Cursos
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ grades.courses.length }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal en dos columnas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Columna izquierda -->
                    <div class="space-y-6">
                        <!-- Horario de hoy -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Horario de Hoy
                                </h2>
                            </div>
                            <div class="p-6">
                                <div
                                    v-if="getTodaySchedule().length === 0"
                                    class="text-center text-gray-500 py-4"
                                >
                                    <p>No hay clases programadas para hoy</p>
                                </div>
                                <div v-else class="space-y-3">
                                    <div
                                        v-for="schedule in getTodaySchedule()"
                                        :key="schedule.id"
                                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                    >
                                        <div>
                                            <h4
                                                class="font-medium text-gray-900"
                                            >
                                                {{ schedule.course.name }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                {{ schedule.teacher.name }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{
                                                    formatTime(
                                                        schedule.start_time
                                                    )
                                                }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{
                                                    formatTime(
                                                        schedule.end_time
                                                    )
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Profesores -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Profesores
                                </h2>
                            </div>
                            <div class="p-6">
                                <div
                                    v-if="Object.keys(teachers).length === 0"
                                    class="text-center text-gray-500 py-4"
                                >
                                    <p>No hay profesores asignados</p>
                                </div>
                                <div v-else class="space-y-4">
                                    <div
                                        v-for="(
                                            teacherData, teacherId
                                        ) in teachers"
                                        :key="teacherId"
                                        class="flex items-center space-x-3"
                                    >
                                        <div
                                            class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center"
                                        >
                                            <span
                                                class="text-green-600 font-medium text-sm"
                                            >
                                                {{
                                                    getInitials(
                                                        teacherData.teacher.name
                                                    )
                                                }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <h4
                                                class="font-medium text-gray-900"
                                            >
                                                {{ teacherData.teacher.name }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                {{
                                                    teacherData.courses
                                                        .map((c) => c.name)
                                                        .join(", ")
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="space-y-6">
                        <!-- Calificaciones recientes -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Calificaciones Recientes
                                </h2>
                            </div>
                            <div class="p-6">
                                <div
                                    v-if="getRecentGrades().length === 0"
                                    class="text-center text-gray-500 py-4"
                                >
                                    <p>No hay calificaciones recientes</p>
                                </div>
                                <div v-else class="space-y-3">
                                    <div
                                        v-for="grade in getRecentGrades()"
                                        :key="grade.id"
                                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                    >
                                        <div>
                                            <h4
                                                class="font-medium text-gray-900"
                                            >
                                                {{ grade.assignment }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                {{ grade.course }}
                                            </p>
                                        </div>
                                        <div
                                            :class="[
                                                getGradeColor(grade.grade),
                                                'text-lg font-medium',
                                            ]"
                                        >
                                            {{ grade.grade }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Asistencia reciente -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Asistencia Reciente
                                </h2>
                            </div>
                            <div class="p-6">
                                <div
                                    v-if="
                                        attendance.recent_attendance.length ===
                                        0
                                    "
                                    class="text-center text-gray-500 py-4"
                                >
                                    <p>No hay registro de asistencia</p>
                                </div>
                                <div v-else class="space-y-3">
                                    <div
                                        v-for="record in attendance.recent_attendance.slice(
                                            0,
                                            5
                                        )"
                                        :key="record.date"
                                        class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                                    >
                                        <div>
                                            <h4
                                                class="font-medium text-gray-900"
                                            >
                                                {{ record.course }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                {{ formatDate(record.date) }}
                                            </p>
                                        </div>
                                        <div
                                            :class="[
                                                record.status === 'present'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800',
                                                'px-2 py-1 rounded-full text-xs font-medium',
                                            ]"
                                        >
                                            {{
                                                record.status === "present"
                                                    ? "Presente"
                                                    : "Ausente"
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de reportes -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">
                            Progreso Académico
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-700 mb-2"
                                >
                                    Desempeño General
                                </h3>
                                <p class="text-sm text-gray-900 mb-4">
                                    {{
                                        reports.academic_progress
                                            .overall_performance
                                    }}
                                </p>

                                <h3
                                    class="text-sm font-medium text-gray-700 mb-2"
                                >
                                    Fortalezas
                                </h3>
                                <ul class="text-sm text-gray-900 space-y-1">
                                    <li
                                        v-for="strength in reports
                                            .academic_progress.strengths"
                                        :key="strength"
                                        class="flex items-center"
                                    >
                                        <svg
                                            class="h-4 w-4 text-green-500 mr-2"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        {{ strength }}
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-700 mb-2"
                                >
                                    Áreas de Mejora
                                </h3>
                                <ul class="text-sm text-gray-900 space-y-1">
                                    <li
                                        v-for="area in reports.academic_progress
                                            .areas_for_improvement"
                                        :key="area"
                                        class="flex items-center"
                                    >
                                        <svg
                                            class="h-4 w-4 text-yellow-500 mr-2"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        {{ area }}
                                    </li>
                                </ul>

                                <h3
                                    class="text-sm font-medium text-gray-700 mb-2 mt-4"
                                >
                                    Recomendaciones
                                </h3>
                                <p class="text-sm text-gray-900">
                                    {{
                                        reports.academic_progress
                                            .recommendations
                                    }}
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
    schedules: Object,
    teachers: Object,
    attendance: Object,
    grades: Object,
    reports: Object,
    grade: Object,
    child: Object,
    children: Array,
    user: Object,
    message: String,
});

const getInitials = (name) => {
    if (!name) return "E";
    const names = name.split(" ");
    if (names.length >= 2) {
        return (names[0][0] + names[1][0]).toUpperCase();
    }
    return names[0][0].toUpperCase();
};

const getGradeColor = (grade) => {
    if (grade >= 90) return "text-green-600";
    if (grade >= 80) return "text-blue-600";
    if (grade >= 70) return "text-yellow-600";
    return "text-red-600";
};

const formatTime = (time) => {
    return new Date(`2000-01-01T${time}`).toLocaleTimeString("es-ES", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("es-ES", {
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

const getTodaySchedule = () => {
    const today = new Date();
    const dayNames = [
        "sunday",
        "monday",
        "tuesday",
        "wednesday",
        "thursday",
        "friday",
        "saturday",
    ];
    const todayName = dayNames[today.getDay()];

    if (props.schedules[todayName]) {
        return props.schedules[todayName];
    }
    return [];
};

const getRecentGrades = () => {
    const allGrades = [];
    props.grades.courses.forEach((course) => {
        course.grades.forEach((grade) => {
            allGrades.push({
                ...grade,
                course: course.course,
            });
        });
    });

    return allGrades
        .sort((a, b) => new Date(b.date) - new Date(a.date))
        .slice(0, 5);
};
</script>
