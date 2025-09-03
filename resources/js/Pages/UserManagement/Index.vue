<script setup>
import { Head, useForm, router, Link } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    users: Object, // Cambiado a Object para paginación
    invitations: Array,
    roles: Array,
    filters: Object,
});

const showInviteForm = ref(false);
const showDeleteUserModal = ref(false);
const showDeleteInvitationModal = ref(false);
const userToDelete = ref(null);
const invitationToDelete = ref(null);
const sendingCredentials = ref(null);

// Filtros y paginación
const filters = ref({
    search: props.filters?.search || "",
    role: props.filters?.role || "",
    sort: props.filters?.sort || "name",
    direction: props.filters?.direction || "asc",
});

const showFilters = ref(false);

const inviteForm = useForm({
    email: "",
    role_id: "",
});

const submitInvite = () => {
    inviteForm.post(route("user-management.invite"), {
        onSuccess: () => {
            inviteForm.reset();
            showInviteForm.value = false;
        },
    });
};

const closeInviteModal = () => {
    showInviteForm.value = false;
    inviteForm.reset();
};

// Funciones de filtrado y paginación
const applyFilters = () => {
    router.get(
        route("user-management.index"),
        {
            search: filters.value.search,
            role: filters.value.role,
            sort: filters.value.sort,
            direction: filters.value.direction,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const clearFilters = () => {
    filters.value = {
        search: "",
        role: "",
        sort: "name",
        direction: "asc",
    };
    applyFilters();
};

const sortBy = (column) => {
    if (filters.value.sort === column) {
        filters.value.direction =
            filters.value.direction === "asc" ? "desc" : "asc";
    } else {
        filters.value.sort = column;
        filters.value.direction = "asc";
    }
    applyFilters();
};

const getSortIcon = (column) => {
    if (filters.value.sort !== column) {
        return "M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4";
    }
    return filters.value.direction === "asc"
        ? "M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
        : "M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4";
};

// Watchers para aplicar filtros automáticamente
watch(
    () => filters.value.search,
    () => {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(applyFilters, 500);
    }
);

watch(() => filters.value.role, applyFilters);

// Funciones auxiliares para paginación
const getPageNumbers = () => {
    const current = users.current_page;
    const last = users.last_page;
    const delta = 2;
    const range = [];
    const rangeWithDots = [];

    for (
        let i = Math.max(2, current - delta);
        i <= Math.min(last - 1, current + delta);
        i++
    ) {
        range.push(i);
    }

    if (current - delta > 2) {
        rangeWithDots.push(1, "...");
    } else {
        rangeWithDots.push(1);
    }

    rangeWithDots.push(...range);

    if (current + delta < last - 1) {
        rangeWithDots.push("...", last);
    } else {
        rangeWithDots.push(last);
    }

    return rangeWithDots;
};

const getFilterParams = () => {
    const params = new URLSearchParams();
    if (filters.value.search) params.append("search", filters.value.search);
    if (filters.value.role) params.append("role", filters.value.role);
    if (filters.value.sort) params.append("sort", filters.value.sort);
    if (filters.value.direction)
        params.append("direction", filters.value.direction);

    const paramString = params.toString();
    return paramString ? "&" + paramString : "";
};

const confirmDeleteUser = (user) => {
    userToDelete.value = user;
    showDeleteUserModal.value = true;
};

const confirmDeleteInvitation = (invitation) => {
    invitationToDelete.value = invitation;
    showDeleteInvitationModal.value = true;
};

const deleteUser = () => {
    if (userToDelete.value) {
        useForm().delete(
            route("user-management.delete-user", userToDelete.value.id),
            {
                onSuccess: () => {
                    showDeleteUserModal.value = false;
                    userToDelete.value = null;
                },
            }
        );
    }
};

const deleteInvitation = () => {
    if (invitationToDelete.value) {
        useForm().delete(
            route(
                "user-management.delete-invitation",
                invitationToDelete.value.id
            ),
            {
                onSuccess: () => {
                    showDeleteInvitationModal.value = false;
                    invitationToDelete.value = null;
                },
            }
        );
    }
};

const cancelDelete = () => {
    showDeleteUserModal.value = false;
    showDeleteInvitationModal.value = false;
    userToDelete.value = null;
    invitationToDelete.value = null;
};

const sendCredentials = (user) => {
    sendingCredentials.value = user.id;

    useForm().post(
        route("user-management.send-credentials", user.id),
        {},
        {
            onSuccess: () => {
                sendingCredentials.value = null;
            },
            onError: () => {
                sendingCredentials.value = null;
            },
        }
    );
};
</script>

<template>
    <Head title="Gestión de Usuarios" />

    <AdminLayout :user="$page.props.auth.user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Gestión de Usuarios
                </h1>
                <p class="mt-2 text-gray-600">
                    Administra usuarios, roles e invitaciones del sistema
                </p>
            </div>

            <!-- Botón para invitar usuarios -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            Invitar Nuevos Usuarios
                        </h3>
                        <p class="text-sm text-gray-600">
                            Envía invitaciones a profesores y padres
                        </p>
                    </div>
                    <button
                        @click="showInviteForm = true"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
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
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                            />
                        </svg>
                        Invitar Usuario
                    </button>
                </div>
            </div>

            <!-- Lista de usuarios existentes -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Usuarios Registrados
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Total: {{ users.total }} usuarios
                            </p>
                        </div>
                        <button
                            @click="showFilters = !showFilters"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"
                                />
                            </svg>
                            {{
                                showFilters
                                    ? "Ocultar Filtros"
                                    : "Mostrar Filtros"
                            }}
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div
                    v-if="showFilters"
                    class="px-6 py-4 bg-gray-50 border-b border-gray-200"
                >
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Búsqueda -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Buscar Usuario
                            </label>
                            <div class="relative">
                                <input
                                    v-model="filters.search"
                                    type="text"
                                    placeholder="Nombre, email..."
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                />
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-5 w-5 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Filtro por rol -->
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Filtrar por Rol
                            </label>
                            <select
                                v-model="filters.role"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            >
                                <option value="">Todos los roles</option>
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.id"
                                >
                                    {{ role.display_name }}
                                </option>
                            </select>
                        </div>

                        <!-- Botón limpiar filtros -->
                        <div class="flex items-end">
                            <button
                                @click="clearFilters"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>
                                Limpiar Filtros
                            </button>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    @click="sortBy('name')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                                >
                                    <div class="flex items-center space-x-1">
                                        <span>Nombre</span>
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                :d="getSortIcon('name')"
                                            />
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    @click="sortBy('email')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                                >
                                    <div class="flex items-center space-x-1">
                                        <span>Email</span>
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                :d="getSortIcon('email')"
                                            />
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    @click="sortBy('role_id')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                                >
                                    <div class="flex items-center space-x-1">
                                        <span>Rol</span>
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                :d="getSortIcon('role_id')"
                                            />
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    @click="sortBy('created_at')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                                >
                                    <div class="flex items-center space-x-1">
                                        <span>Fecha de Registro</span>
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                :d="getSortIcon('created_at')"
                                            />
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in users.data" :key="user.id">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ user.name }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ user.email }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ user.role.display_name }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{
                                        new Date(
                                            user.created_at
                                        ).toLocaleDateString()
                                    }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2"
                                >
                                    <button
                                        @click="sendCredentials(user)"
                                        class="text-blue-600 hover:text-blue-900 transition-colors"
                                        :disabled="
                                            sendingCredentials === user.id
                                        "
                                    >
                                        <svg
                                            v-if="
                                                sendingCredentials === user.id
                                            "
                                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600 inline"
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
                                            sendingCredentials === user.id
                                                ? "Enviando..."
                                                : "Enviar Credenciales"
                                        }}
                                    </button>
                                    <button
                                        @click="confirmDeleteUser(user)"
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div
                    v-if="users.last_page > 1"
                    class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
                >
                    <div class="flex-1 flex justify-between sm:hidden">
                        <!-- Navegación móvil -->
                        <Link
                            v-if="users.prev_page_url"
                            :href="users.prev_page_url"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Anterior
                        </Link>
                        <Link
                            v-if="users.next_page_url"
                            :href="users.next_page_url"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Siguiente
                        </Link>
                    </div>
                    <div
                        class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
                    >
                        <div>
                            <p class="text-sm text-gray-700">
                                Mostrando
                                <span class="font-medium">{{
                                    users.from
                                }}</span>
                                a
                                <span class="font-medium">{{ users.to }}</span>
                                de
                                <span class="font-medium">{{
                                    users.total
                                }}</span>
                                resultados
                            </p>
                        </div>
                        <div>
                            <nav
                                class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination"
                            >
                                <!-- Página anterior -->
                                <Link
                                    v-if="users.prev_page_url"
                                    :href="users.prev_page_url"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                >
                                    <span class="sr-only">Anterior</span>
                                    <svg
                                        class="h-5 w-5"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </Link>
                                <span
                                    v-else
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed"
                                >
                                    <span class="sr-only">Anterior</span>
                                    <svg
                                        class="h-5 w-5"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>

                                <!-- Números de página -->
                                <template
                                    v-for="page in getPageNumbers()"
                                    :key="page"
                                >
                                    <Link
                                        v-if="page !== '...'"
                                        :href="
                                            users.path +
                                            '?page=' +
                                            page +
                                            getFilterParams()
                                        "
                                        :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            page == users.current_page
                                                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                        ]"
                                    >
                                        {{ page }}
                                    </Link>
                                    <span
                                        v-else
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                    >
                                        ...
                                    </span>
                                </template>

                                <!-- Página siguiente -->
                                <Link
                                    v-if="users.next_page_url"
                                    :href="users.next_page_url"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                >
                                    <span class="sr-only">Siguiente</span>
                                    <svg
                                        class="h-5 w-5"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </Link>
                                <span
                                    v-else
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed"
                                >
                                    <span class="sr-only">Siguiente</span>
                                    <svg
                                        class="h-5 w-5"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </span>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de invitaciones pendientes -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Invitaciones Pendientes
                    </h3>
                </div>
                <div class="p-6">
                    <div
                        v-if="invitations.length === 0"
                        class="text-gray-500 text-center py-4"
                    >
                        No hay invitaciones pendientes
                    </div>
                    <div v-else class="space-y-4">
                        <div
                            v-for="invitation in invitations"
                            :key="invitation.id"
                            class="border rounded-lg p-4 flex justify-between items-center"
                        >
                            <div>
                                <p class="font-medium">{{ invitation.name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ invitation.email }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ invitation.role.display_name }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    Expira:
                                    {{
                                        new Date(
                                            invitation.expires_at
                                        ).toLocaleDateString()
                                    }}
                                </p>
                            </div>
                            <button
                                @click="confirmDeleteInvitation(invitation)"
                                class="bg-red-500 hover:bg-red-700 text-white text-sm px-3 py-1 rounded transition-colors"
                            >
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación para eliminar usuario -->
        <div
            v-if="showDeleteUserModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3 text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100"
                    >
                        <svg
                            class="h-6 w-6 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-4">
                        Confirmar Eliminación
                    </h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            ¿Está seguro de que desea eliminar al usuario
                            <strong>{{ userToDelete?.name }}</strong
                            >?
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-3 mt-4">
                        <button
                            @click="cancelDelete"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="deleteUser"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación para eliminar invitación -->
        <div
            v-if="showDeleteInvitationModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3 text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100"
                    >
                        <svg
                            class="h-6 w-6 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                            ></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-4">
                        Confirmar Eliminación
                    </h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            ¿Está seguro de que desea eliminar la invitación
                            para
                            <strong>{{ invitationToDelete?.email }}</strong
                            >?
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-3 mt-4">
                        <button
                            @click="cancelDelete"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="deleteInvitation"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de invitación de usuario -->
        <div
            v-if="showInviteForm"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <!-- Header del modal -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Invitar Nuevo Usuario
                        </h3>
                        <button
                            @click="closeInviteModal"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
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
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Formulario de invitación -->
                    <form @submit.prevent="submitInvite" class="space-y-4">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Email del Usuario
                            </label>
                            <input
                                v-model="inviteForm.email"
                                type="email"
                                required
                                placeholder="usuario@ejemplo.com"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                            <div
                                v-if="inviteForm.errors.email"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ inviteForm.errors.email }}
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Rol del Usuario
                            </label>
                            <select
                                v-model="inviteForm.role_id"
                                required
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            >
                                <option value="">Selecciona un rol</option>
                                <option
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.id"
                                >
                                    {{ role.display_name }}
                                </option>
                            </select>
                            <div
                                v-if="inviteForm.errors.role_id"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ inviteForm.errors.role_id }}
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div
                            class="bg-blue-50 border border-blue-200 rounded-md p-3"
                        >
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-5 w-5 text-blue-400"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Se enviará un email de invitación al
                                        usuario con las credenciales para
                                        acceder al sistema.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Botones del modal -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="closeInviteModal"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="inviteForm.processing"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg
                                    v-if="inviteForm.processing"
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
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                                {{
                                    inviteForm.processing
                                        ? "Enviando..."
                                        : "Enviar Invitación"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
