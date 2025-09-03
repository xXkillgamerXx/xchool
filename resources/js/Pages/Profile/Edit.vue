<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    mustVerifyEmail: Boolean,
    status: String,
});

const form = useForm({
    name: props.user.name,
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
});

const submit = () => {
    form.patch(route("profile.update"));
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Perfil de Usuario
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Información del Perfil
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Actualiza la información de tu perfil y tu
                                dirección de email.
                            </p>
                        </header>

                        <form @submit.prevent="submit" class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="first_name" value="Nombre" />
                                <TextInput
                                    id="first_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.first_name"
                                    required
                                    autofocus
                                    autocomplete="given-name"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.first_name"
                                />
                            </div>

                            <div>
                                <InputLabel for="last_name" value="Apellido" />
                                <TextInput
                                    id="last_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.last_name"
                                    required
                                    autocomplete="family-name"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.last_name"
                                />
                            </div>

                            <div>
                                <InputLabel for="name" value="Usuario" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    autocomplete="username"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    Tu nombre de usuario para iniciar sesión.
                                </p>
                            </div>

                            <div>
                                <InputLabel for="email" value="Email" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.email"
                                />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing"
                                    >Guardar</PrimaryButton
                                >

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p
                                        v-if="form.recentlySuccessful"
                                        class="text-sm text-gray-600"
                                    >
                                        Guardado.
                                    </p>
                                </Transition>
                            </div>
                        </form>
                    </section>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <section class="space-y-6">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                Información del Rol
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Información sobre tu rol en el sistema.
                            </p>
                        </header>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span
                                        class="text-sm font-medium text-gray-500"
                                        >Rol:</span
                                    >
                                    <p class="text-sm text-gray-900">
                                        {{ user.role }}
                                    </p>
                                </div>
                                <div>
                                    <span
                                        class="text-sm font-medium text-gray-500"
                                        >Email:</span
                                    >
                                    <p class="text-sm text-gray-900">
                                        {{ user.email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
