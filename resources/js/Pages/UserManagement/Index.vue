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

const deleteInvitation = (invitationId) => {
    if (confirm("¿Estás seguro de que quieres eliminar esta invitación?")) {
        useForm().delete(
            route("user-management.delete-invitation", invitationId)
        );
    }
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
                                    @click="deleteInvitation(invitation.id)"
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
    </AuthenticatedLayout>
</template>
