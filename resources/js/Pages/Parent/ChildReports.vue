<template>
    <ParentLayout :children="children" current-tab="reports">
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
                                Por favor selecciona un estudiante para ver sus
                                reportes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de reportes -->
            <div v-else class="space-y-6">
                <!-- Header -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Reportes - {{ selectedChild.name }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        {{
                            selectedChild.grade
                                ? selectedChild.grade.full_name
                                : "Sin grado asignado"
                        }}
                    </p>
                </div>

                <!-- Progreso académico -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">
                            Progreso Académico
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">
                                Desempeño General
                            </h3>
                            <p class="text-sm text-gray-900">
                                {{
                                    reports.academic_progress
                                        .overall_performance
                                }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
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
                            </div>
                        </div>

                        <div class="mt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">
                                Recomendaciones
                            </h3>
                            <p class="text-sm text-gray-900">
                                {{ reports.academic_progress.recommendations }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Notas de comportamiento -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">
                            Notas de Comportamiento
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div
                            v-for="note in reports.behavioral_notes"
                            :key="note.date"
                            class="px-6 py-4"
                        >
                            <div class="flex items-start">
                                <div
                                    :class="[
                                        note.type === 'positive'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-yellow-100 text-yellow-800',
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-3 mt-1',
                                    ]"
                                >
                                    {{
                                        note.type === "positive"
                                            ? "Positivo"
                                            : "Mejora"
                                    }}
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
                </div>

                <!-- Próximos eventos -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">
                            Próximos Eventos
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div
                            v-for="event in reports.upcoming_events"
                            :key="event.date"
                            class="px-6 py-4 flex items-center"
                        >
                            <div
                                :class="[
                                    getEventTypeColor(event.type),
                                    'flex-shrink-0 w-2 h-2 rounded-full mr-3',
                                ]"
                            ></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ event.event }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ formatDate(event.date) }}
                                </p>
                            </div>
                            <div
                                :class="[
                                    getEventTypeColor(event.type),
                                    'text-xs font-medium px-2 py-1 rounded-full',
                                ]"
                            >
                                {{
                                    event.type === "exam"
                                        ? "Examen"
                                        : event.type === "presentation"
                                        ? "Presentación"
                                        : "Reunión"
                                }}
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
    reports: Object,
    grade: Object,
    child: Object,
    children: Array,
    user: Object,
    message: String,
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("es-ES", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const getEventTypeColor = (type) => {
    switch (type) {
        case "exam":
            return "bg-red-500 text-red-100";
        case "presentation":
            return "bg-blue-500 text-blue-100";
        case "meeting":
            return "bg-green-500 text-green-100";
        default:
            return "bg-gray-500 text-gray-100";
    }
};
</script>
