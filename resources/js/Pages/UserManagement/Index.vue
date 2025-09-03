<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    users: Array,
    invitations: Array,
    roles: Array,
});

const showInviteForm = ref(false);
const showDeleteUserModal = ref(false);
const showDeleteInvitationModal = ref(false);
const userToDelete = ref(null);
const invitationToDelete = ref(null);

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
</script>

<template>
    <Head title="Gestión de Usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Usuarios
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Botón para invitar usuarios -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6"
                >
                    <div class="p-6">
                        <button
                            @click="showInviteForm = !showInviteForm"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            {{
                                showInviteForm ? "Cancelar" : "Invitar Usuario"
                            }}
                        </button>

                        <!-- Formulario de invitación -->
                        <div v-if="showInviteForm" class="mt-6">
                            <form
                                @submit.prevent="submitInvite"
                                class="space-y-4"
                            >
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                        >Email</label
                                    >
                                    <input
                                        v-model="inviteForm.email"
                                        type="email"
                                        required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
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
                                        class="block text-sm font-medium text-gray-700"
                                        >Rol</label
                                    >
                                    <select
                                        v-model="inviteForm.role_id"
                                        required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                        <option value="">
                                            Selecciona un rol
                                        </option>
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

                                <button
                                    type="submit"
                                    :disabled="inviteForm.processing"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                                >
                                    {{
                                        inviteForm.processing
                                            ? "Enviando..."
                                            : "Enviar Invitación"
                                    }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lista de usuarios existentes -->
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6"
                >
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">
                            Usuarios Registrados
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Nombre
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Rol
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Fecha de Registro
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="bg-white divide-y divide-gray-200"
                                >
                                    <tr v-for="user in users" :key="user.id">
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
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                        >
                                            <button
                                                @click="confirmDeleteUser(user)"
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
                </div>

                <!-- Lista de invitaciones pendientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">
                            Invitaciones Pendientes
                        </h3>
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
                                    <p class="font-medium">
                                        {{ invitation.name }}
                                    </p>
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
                                    class="bg-red-500 hover:bg-red-700 text-white text-sm px-3 py-1 rounded"
                                >
                                    Eliminar
                                </button>
                            </div>
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
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="deleteUser"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
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
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="deleteInvitation"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
