<template>
    <Head title="Historial de Actividades" />

    <AdminLayout :user="$page.props.auth.user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Historial de Actividades
                </h1>
                <p class="mt-2 text-gray-600">
                    Revisa todas las acciones realizadas en el sistema
                </p>
            </div>

            <!-- Filtros -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Acción</label
                        >
                        <select
                            v-model="filters.action"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Todas las acciones</option>
                            <option value="create">Crear</option>
                            <option value="update">Actualizar</option>
                            <option value="delete">Eliminar</option>
                            <option value="invite">Invitar</option>
                            <option value="accept_invitation">
                                Aceptar Invitación
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Tipo de Modelo</label
                        >
                        <select
                            v-model="filters.model_type"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Todos los tipos</option>
                            <option value="App\Models\User">Usuarios</option>
                            <option value="App\Models\Invitation">
                                Invitaciones
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Usuario</label
                        >
                        <select
                            v-model="filters.user_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Todos los usuarios</option>
                            <option
                                v-for="user in users"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.display_name }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Rango de Fechas</label
                        >
                        <select
                            v-model="filters.date_range"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="">Todas las fechas</option>
                            <option value="today">Hoy</option>
                            <option value="week">Esta semana</option>
                            <option value="month">Este mes</option>
                            <option value="year">Este año</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-4">
                    <button
                        @click="clearFilters"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                    >
                        Limpiar Filtros
                    </button>
                    <button
                        @click="applyFilters"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                    >
                        Aplicar Filtros
                    </button>
                </div>
            </div>

            <!-- Lista de actividades -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Actividades
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Usuario
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Acción
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Descripción
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Fecha
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
                                v-for="activity in activities.data"
                                :key="activity.id"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div
                                                class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center"
                                            >
                                                <span
                                                    class="text-xs font-medium text-indigo-600"
                                                >
                                                    {{
                                                        getInitials(
                                                            activity.user
                                                                ?.first_name,
                                                            activity.user
                                                                ?.last_name
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{
                                                    activity.user
                                                        ?.display_name ||
                                                    "Usuario eliminado"
                                                }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ activity.user?.email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="
                                            getActionBadgeClass(activity.action)
                                        "
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                    >
                                        {{
                                            getActionDisplayName(
                                                activity.action
                                            )
                                        }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ activity.description }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ formatDate(activity.created_at) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                >
                                    <button
                                        @click="showActivityDetails(activity)"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                    >
                                        Ver Detalles
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div
                    v-if="activities.links && activities.links.length > 3"
                    class="px-6 py-4 border-t border-gray-200"
                >
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Mostrando {{ activities.from }} a
                            {{ activities.to }} de
                            {{ activities.total }} resultados
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                v-for="link in activities.links"
                                :key="link.label"
                                :href="link.url"
                                :class="[
                                    'px-3 py-2 text-sm font-medium rounded-md transition-colors',
                                    link.active
                                        ? 'bg-indigo-600 text-white'
                                        : link.url
                                        ? 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50'
                                        : 'text-gray-400 bg-white border border-gray-300 cursor-not-allowed',
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de detalles de actividad -->
        <div
            v-if="selectedActivity"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Detalles de la Actividad
                        </h3>
                        <button
                            @click="selectedActivity = null"
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

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">
                                Usuario
                            </h4>
                            <p class="text-sm text-gray-900">
                                {{
                                    selectedActivity.user?.display_name ||
                                    "Usuario eliminado"
                                }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700">
                                Acción
                            </h4>
                            <span
                                :class="
                                    getActionBadgeClass(selectedActivity.action)
                                "
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                            >
                                {{
                                    getActionDisplayName(
                                        selectedActivity.action
                                    )
                                }}
                            </span>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700">
                                Descripción
                            </h4>
                            <p class="text-sm text-gray-900">
                                {{ selectedActivity.description }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700">
                                Fecha y Hora
                            </h4>
                            <p class="text-sm text-gray-900">
                                {{ formatDate(selectedActivity.created_at) }}
                            </p>
                        </div>

                        <div v-if="selectedActivity.ip_address">
                            <h4 class="text-sm font-medium text-gray-700">
                                Dirección IP
                            </h4>
                            <p class="text-sm text-gray-900">
                                {{ selectedActivity.ip_address }}
                            </p>
                        </div>

                        <div v-if="selectedActivity.user_agent">
                            <h4 class="text-sm font-medium text-gray-700">
                                Navegador
                            </h4>
                            <p class="text-sm text-gray-900">
                                {{ selectedActivity.user_agent }}
                            </p>
                        </div>

                        <div
                            v-if="
                                selectedActivity.old_values &&
                                Object.keys(selectedActivity.old_values)
                                    .length > 0
                            "
                        >
                            <h4 class="text-sm font-medium text-gray-700">
                                Valores Anteriores
                            </h4>
                            <pre
                                class="text-sm text-gray-900 bg-gray-50 p-3 rounded overflow-x-auto"
                                >{{
                                    JSON.stringify(
                                        selectedActivity.old_values,
                                        null,
                                        2
                                    )
                                }}</pre
                            >
                        </div>

                        <div
                            v-if="
                                selectedActivity.new_values &&
                                Object.keys(selectedActivity.new_values)
                                    .length > 0
                            "
                        >
                            <h4 class="text-sm font-medium text-gray-700">
                                Valores Nuevos
                            </h4>
                            <pre
                                class="text-sm text-gray-900 bg-gray-50 p-3 rounded overflow-x-auto"
                                >{{
                                    JSON.stringify(
                                        selectedActivity.new_values,
                                        null,
                                        2
                                    )
                                }}</pre
                            >
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button
                            @click="selectedActivity = null"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, reactive } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    activities: Object,
    users: Array,
    filters: Object,
});

// Estados reactivos
const selectedActivity = ref(null);

// Filtros locales
const filters = reactive({
    action: props.filters?.action || "",
    model_type: props.filters?.model_type || "",
    user_id: props.filters?.user_id || "",
    date_range: props.filters?.date_range || "",
});

// Métodos
const applyFilters = () => {
    router.get(route("activity-log.index"), filters, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    Object.keys(filters).forEach((key) => {
        filters[key] = "";
    });
    applyFilters();
};

const showActivityDetails = (activity) => {
    selectedActivity.value = activity;
};

const getActionDisplayName = (action) => {
    const actionNames = {
        create: "Crear",
        update: "Actualizar",
        delete: "Eliminar",
        invite: "Invitar",
        accept_invitation: "Aceptar Invitación",
    };
    return actionNames[action] || action;
};

const getActionBadgeClass = (action) => {
    const badgeClasses = {
        create: "bg-green-100 text-green-800",
        update: "bg-blue-100 text-blue-800",
        delete: "bg-red-100 text-red-800",
        invite: "bg-yellow-100 text-yellow-800",
        accept_invitation: "bg-purple-100 text-purple-800",
    };
    return badgeClasses[action] || "bg-gray-100 text-gray-800";
};

const getInitials = (firstName, lastName) => {
    if (firstName && lastName) {
        return (firstName.charAt(0) + lastName.charAt(0)).toUpperCase();
    }
    return "?";
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString("es-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>
