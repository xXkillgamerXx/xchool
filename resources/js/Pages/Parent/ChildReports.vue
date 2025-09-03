<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Reportes de Mi Hijo
                    </h1>
                    <p class="mt-2 text-gray-600">
                        {{
                            child
                                ? `Reportes de progreso de ${child.name}`
                                : "Reportes de progreso del estudiante"
                        }}
                        {{ grade ? ` - Grado: ${grade.name}` : "" }}
                    </p>
                </div>

                <!-- Mensaje si no hay hijo asignado -->
                <div
                    v-if="!child"
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
                                Hijo no asignado
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>{{ message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reportes de progreso académico -->
                <div
                    v-if="child && reports && reports.academic_progress"
                    class="space-y-6"
                >
                    <!-- Progreso académico general -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                            Progreso Académico
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-900 mb-2"
                                >
                                    Rendimiento General
                                </h3>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                        :class="
                                            getPerformanceColor(
                                                reports.academic_progress
                                                    .overall_performance
                                            )
                                        "
                                    >
                                        {{
                                            reports.academic_progress
                                                .overall_performance
                                        }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-900 mb-2"
                                >
                                    Recomendaciones
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{
                                        reports.academic_progress
                                            .recommendations
                                    }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-900 mb-2"
                                >
                                    Fortalezas
                                </h3>
                                <ul class="space-y-1">
                                    <li
                                        v-for="strength in reports
                                            .academic_progress.strengths"
                                        :key="strength"
                                        class="flex items-center text-sm text-green-600"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2"
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
                                    class="text-sm font-medium text-gray-900 mb-2"
                                >
                                    Áreas de Mejora
                                </h3>
                                <ul class="space-y-1">
                                    <li
                                        v-for="area in reports.academic_progress
                                            .areas_for_improvement"
                                        :key="area"
                                        class="flex items-center text-sm text-yellow-600"
                                    >
                                        <svg
                                            class="w-4 h-4 mr-2"
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
                            </div>
                        </div>
                    </div>

                    <!-- Notas de comportamiento -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                            Notas de Comportamiento
                        </h2>

                        <div class="space-y-4">
                            <div
                                v-for="(
                                    note, index
                                ) in reports.behavioral_notes"
                                :key="index"
                                class="flex items-start space-x-3 p-4 rounded-lg"
                                :class="getNoteBackground(note.type)"
                            >
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center"
                                        :class="getNoteIconColor(note.type)"
                                    >
                                        <svg
                                            v-if="note.type === 'positive'"
                                            class="w-4 h-4"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            class="w-4 h-4"
                                            fill="currentColor"
                                            viewBox="0 0 20 20"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">
                                        {{ note.note }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ formatDate(note.date) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Próximos eventos -->
                    <div class="bg-white shadow rounded-lg p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">
                            Próximos Eventos
                        </h2>

                        <div class="space-y-3">
                            <div
                                v-for="(
                                    event, index
                                ) in reports.upcoming_events"
                                :key="index"
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                            >
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center"
                                        :class="getEventIconColor(event.type)"
                                    >
                                        <svg
                                            v-if="event.type === 'exam'"
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                        <svg
                                            v-else-if="
                                                event.type === 'presentation'
                                            "
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1V1a1 1 0 011-1h2a1 1 0 011 1v3z"
                                            />
                                        </svg>
                                        <svg
                                            v-else
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ event.event }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ formatDate(event.date) }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="getEventTypeColor(event.type)"
                                >
                                    {{ getEventTypeName(event.type) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje si no hay reportes -->
                <div
                    v-else-if="child"
                    class="bg-white shadow rounded-lg p-6 text-center"
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
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        No hay reportes disponibles
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Aún no se han generado reportes de progreso para tu
                        hijo.
                    </p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    reports: Object,
    grade: Object,
    child: Object,
    user: Object,
    message: String,
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("es-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const getPerformanceColor = (performance) => {
    switch (performance.toLowerCase()) {
        case "excelente":
            return "bg-green-100 text-green-800";
        case "bueno":
            return "bg-blue-100 text-blue-800";
        case "regular":
            return "bg-yellow-100 text-yellow-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const getNoteBackground = (type) => {
    return type === "positive" ? "bg-green-50" : "bg-yellow-50";
};

const getNoteIconColor = (type) => {
    return type === "positive"
        ? "bg-green-100 text-green-600"
        : "bg-yellow-100 text-yellow-600";
};

const getEventIconColor = (type) => {
    switch (type) {
        case "exam":
            return "bg-red-100 text-red-600";
        case "presentation":
            return "bg-blue-100 text-blue-600";
        case "meeting":
            return "bg-purple-100 text-purple-600";
        default:
            return "bg-gray-100 text-gray-600";
    }
};

const getEventTypeColor = (type) => {
    switch (type) {
        case "exam":
            return "bg-red-100 text-red-800";
        case "presentation":
            return "bg-blue-100 text-blue-800";
        case "meeting":
            return "bg-purple-100 text-purple-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const getEventTypeName = (type) => {
    switch (type) {
        case "exam":
            return "Examen";
        case "presentation":
            return "Presentación";
        case "meeting":
            return "Reunión";
        default:
            return "Evento";
    }
};
</script>
