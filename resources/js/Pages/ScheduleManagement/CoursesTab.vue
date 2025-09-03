<template>
    <div class="space-y-6">
        <!-- Botón para crear curso -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">
                    Gestión de Cursos
                </h3>
                <button
                    @click="showCreateModal = true"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    <svg
                        class="w-5 h-5 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        ></path>
                    </svg>
                    Crear Nuevo Curso
                </button>
            </div>
        </div>

        <!-- Tabla de cursos -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    Cursos Existentes
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Curso
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Color
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Profesores
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Estado
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="course in courses" :key="course.id">
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                            >
                                {{ course.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="w-6 h-6 rounded border border-gray-300"
                                        :style="{
                                            backgroundColor: course.color,
                                        }"
                                    ></div>
                                    <span class="ml-2 text-sm text-gray-500">{{
                                        course.color
                                    }}</span>
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                >
                                    {{ course.teachers_count }} profesores
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="[
                                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                        course.is_active
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800',
                                    ]"
                                >
                                    {{
                                        course.is_active ? "Activo" : "Inactivo"
                                    }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2"
                            >
                                <button
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    Editar
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                        <tr v-if="courses.length === 0">
                            <td
                                colspan="5"
                                class="px-6 py-4 text-center text-sm text-gray-500"
                            >
                                No hay cursos creados aún
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para crear curso -->
        <div
            v-if="showCreateModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <!-- Header del modal -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Crear Nuevo Curso
                        </h3>
                        <button
                            @click="closeModal"
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

                    <!-- Mensaje de éxito -->
                    <div
                        v-if="successMessage"
                        class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md"
                    >
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-5 w-5 text-green-400"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ successMessage }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="createCourse" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    for="modal_name"
                                    class="block text-sm font-medium text-gray-700"
                                    >Nombre del Curso</label
                                >
                                <input
                                    type="text"
                                    id="modal_name"
                                    v-model="form.name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="ej: Matemáticas"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    for="modal_color"
                                    class="block text-sm font-medium text-gray-700"
                                    >Color</label
                                >
                                <input
                                    type="color"
                                    id="modal_color"
                                    v-model="form.color"
                                    class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    for="modal_description"
                                    class="block text-sm font-medium text-gray-700"
                                    >Descripción (opcional)</label
                                >
                                <input
                                    type="text"
                                    id="modal_description"
                                    v-model="form.description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Descripción del curso"
                                />
                            </div>
                        </div>

                        <!-- Botones del modal -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                            >
                                <svg
                                    v-if="isSubmitting"
                                    class="animate-spin -ml-1 mr-3 h-4 w-4 text-white"
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
                                    isSubmitting ? "Creando..." : "Crear Curso"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { router } from "@inertiajs/vue3";

// Props
const props = defineProps({
    courses: Array,
});

// Emits
const emit = defineEmits(["course-created"]);

// Estado reactivo
const showCreateModal = ref(false);
const isSubmitting = ref(false);
const successMessage = ref("");

// Formulario
const form = reactive({
    name: "",
    color: "#3B82F6",
    description: "",
});

// Métodos
const createCourse = async () => {
    try {
        isSubmitting.value = true;
        await router.post(route("schedule-management.courses.store"), form);

        // Mostrar mensaje de éxito
        successMessage.value = "¡Curso creado exitosamente!";

        // Limpiar formulario después de crear
        form.name = "";
        form.color = "#3B82F6";
        form.description = "";

        // Emitir evento para refrescar datos
        emit("course-created");

        // Cerrar modal después de 2 segundos
        setTimeout(() => {
            closeModal();
        }, 2000);
    } catch (error) {
        console.error("Error creating course:", error);
        successMessage.value = "Error al crear el curso. Inténtalo de nuevo.";
    } finally {
        isSubmitting.value = false;
    }
};

const closeModal = () => {
    showCreateModal.value = false;
    successMessage.value = "";
    // Limpiar formulario
    form.name = "";
    form.color = "#3B82F6";
    form.description = "";
};
</script>
