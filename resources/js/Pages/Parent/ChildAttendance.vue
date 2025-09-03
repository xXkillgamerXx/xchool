<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Asistencia de Mi Hijo
                    </h1>
                    <p class="mt-2 text-gray-600">
                        {{
                            child
                                ? `Registro de asistencia de ${child.name}`
                                : "Registro de asistencia del estudiante"
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

                <!-- Estadísticas de asistencia -->
                <div
                    v-if="child && attendance"
                    class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6"
                >
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
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Total Días
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ attendance.total_days }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                            Días Presentes
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ attendance.present_days }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-red-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Días Ausentes
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ attendance.absent_days }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Porcentaje
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
                </div>

                <!-- Gráfico de asistencia -->
                <div
                    v-if="child && attendance"
                    class="bg-white shadow rounded-lg p-6 mb-6"
                >
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        Resumen de Asistencia
                    </h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <div class="bg-gray-200 rounded-full h-4">
                                <div
                                    class="bg-green-500 h-4 rounded-full"
                                    :style="{
                                        width: `${attendance.attendance_percentage}%`,
                                    }"
                                ></div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ attendance.attendance_percentage }}% de
                            asistencia
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-6">
                        <div class="flex items-center">
                            <div
                                class="w-3 h-3 bg-green-500 rounded-full mr-2"
                            ></div>
                            <span class="text-sm text-gray-600">Presente</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-3 h-3 bg-red-500 rounded-full mr-2"
                            ></div>
                            <span class="text-sm text-gray-600">Ausente</span>
                        </div>
                    </div>
                </div>

                <!-- Registro reciente de asistencia -->
                <div
                    v-if="child && attendance && attendance.recent_attendance"
                    class="bg-white shadow rounded-lg overflow-hidden"
                >
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">
                            Registro Reciente
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div
                            v-for="(
                                record, index
                            ) in attendance.recent_attendance"
                            :key="index"
                            class="px-6 py-4 flex items-center justify-between"
                        >
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center"
                                    :class="
                                        record.status === 'present'
                                            ? 'bg-green-100'
                                            : 'bg-red-100'
                                    "
                                >
                                    <svg
                                        v-if="record.status === 'present'"
                                        class="w-4 h-4 text-green-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    <svg
                                        v-else
                                        class="w-4 h-4 text-red-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ record.course }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ formatDate(record.date) }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="
                                        record.status === 'present'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800'
                                    "
                                >
                                    {{
                                        record.status === "present"
                                            ? "Presente"
                                            : "Ausente"
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje si no hay datos de asistencia -->
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
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        No hay datos de asistencia
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Aún no se han registrado datos de asistencia para tu
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
    attendance: Object,
    grade: Object,
    child: Object,
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
</script>
