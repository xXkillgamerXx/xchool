<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    first_name: props.user.first_name || "",
    last_name: props.user.last_name || "",
    email: props.user.email || "",
});

const submit = () => {
    form.put(route("profile.update"));
};
</script>

<template>
    <Head title="Mi Perfil" />

    <AdminLayout :user="$page.props.auth.user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">Mi Perfil</h1>
                <p class="mt-2 text-gray-600">
                    Completa y actualiza tu información personal
                </p>
            </div>

            <!-- Formulario de perfil -->
            <div class="bg-white shadow rounded-lg p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Información del rol -->
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Información del Rol
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Rol</label
                                >
                                <input
                                    :value="user.role_display"
                                    type="text"
                                    disabled
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Nombre de Usuario</label
                                >
                                <input
                                    :value="user.name"
                                    type="text"
                                    disabled
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Información personal -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Información Personal
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Nombre</label
                                >
                                <input
                                    v-model="form.first_name"
                                    type="text"
                                    required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <div
                                    v-if="form.errors.first_name"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.first_name }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Apellido</label
                                >
                                <input
                                    v-model="form.last_name"
                                    type="text"
                                    required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <div
                                    v-if="form.errors.last_name"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.last_name }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Email</label
                            >
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <div
                                v-if="form.errors.email"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-3">
                        <Link
                            :href="route('dashboard')"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50 transition-colors"
                        >
                            {{
                                form.processing
                                    ? "Guardando..."
                                    : "Guardar Cambios"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
