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
                                    @click="editCourse(course)"
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    Editar
                                </button>
                                <button
                                    @click="deleteCourse(course)"
                                    class="text-red-600 hover:text-red-900"
                                >
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

        <!-- Modal para editar curso -->
        <div
            v-if="showEditModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <!-- Header del modal -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Editar Curso
                        </h3>
                        <button
                            @click="closeEditModal"
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
                        v-if="editSuccessMessage"
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
                                    {{ editSuccessMessage }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="updateCourse" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    for="edit_modal_name"
                                    class="block text-sm font-medium text-gray-700"
                                    >Nombre del Curso</label
                                >
                                <input
                                    type="text"
                                    id="edit_modal_name"
                                    v-model="editForm.name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="ej: Matemáticas"
                                    required
                                />
                            </div>
                            <div>
                                <label
                                    for="edit_modal_color"
                                    class="block text-sm font-medium text-gray-700"
                                    >Color</label
                                >
                                <input
                                    type="color"
                                    id="edit_modal_color"
                                    v-model="editForm.color"
                                    class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    for="edit_modal_description"
                                    class="block text-sm font-medium text-gray-700"
                                    >Descripción (opcional)</label
                                >
                                <input
                                    type="text"
                                    id="edit_modal_description"
                                    v-model="editForm.description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Descripción del curso"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <div class="flex items-center">
                                    <input
                                        id="edit_modal_is_active"
                                        v-model="editForm.is_active"
                                        type="checkbox"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    />
                                    <label
                                        for="edit_modal_is_active"
                                        class="ml-2 block text-sm text-gray-900"
                                    >
                                        Curso activo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Botones del modal -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="closeEditModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="isEditSubmitting"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                            >
                                <svg
                                    v-if="isEditSubmitting"
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
                                    isEditSubmitting
                                        ? "Actualizando..."
                                        : "Actualizar Curso"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación para eliminar -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <!-- Header del modal -->
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Confirmar Eliminación
                        </h3>
                        <button
                            @click="closeDeleteModal"
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

                    <!-- Contenido del modal -->
                    <div class="mb-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-8 w-8 text-red-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">
                                    ¿Estás seguro de que quieres eliminar este
                                    curso?
                                </h3>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p>
                                        Esta acción eliminará permanentemente el
                                        curso
                                        <strong>{{
                                            courseToDelete?.name
                                        }}</strong>
                                    </p>
                                    <p class="mt-2 text-red-600">
                                        <strong>Advertencia:</strong> Esta
                                        acción no se puede deshacer.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones del modal -->
                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            @click="closeDeleteModal"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="confirmDelete"
                            :disabled="isDeleteSubmitting"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50"
                        >
                            <svg
                                v-if="isDeleteSubmitting"
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
                                isDeleteSubmitting
                                    ? "Eliminando..."
                                    : "Eliminar Curso"
                            }}
                        </button>
                    </div>
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
const emit = defineEmits([
    "course-created",
    "course-updated",
    "course-deleted",
]);

// Estado reactivo
const showCreateModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const isSubmitting = ref(false);
const isEditSubmitting = ref(false);
const isDeleteSubmitting = ref(false);
const successMessage = ref("");
const editSuccessMessage = ref("");
const courseToDelete = ref(null);

// Formularios
const form = reactive({
    name: "",
    color: "#3B82F6",
    description: "",
});

const editForm = reactive({
    id: null,
    name: "",
    color: "#3B82F6",
    description: "",
    is_active: true,
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

// Métodos para editar
const editCourse = (course) => {
    editForm.id = course.id;
    editForm.name = course.name;
    editForm.color = course.color;
    editForm.description = course.description || "";
    editForm.is_active = course.is_active;
    showEditModal.value = true;
};

const updateCourse = async () => {
    try {
        isEditSubmitting.value = true;
        await router.put(
            route("schedule-management.courses.update", editForm.id),
            editForm
        );

        // Mostrar mensaje de éxito
        editSuccessMessage.value = "¡Curso actualizado exitosamente!";

        // Emitir evento para refrescar datos
        emit("course-updated");

        // Cerrar modal después de 2 segundos
        setTimeout(() => {
            closeEditModal();
        }, 2000);
    } catch (error) {
        console.error("Error updating course:", error);
        editSuccessMessage.value =
            "Error al actualizar el curso. Inténtalo de nuevo.";
    } finally {
        isEditSubmitting.value = false;
    }
};

const closeEditModal = () => {
    showEditModal.value = false;
    editSuccessMessage.value = "";
    // Limpiar formulario
    editForm.id = null;
    editForm.name = "";
    editForm.color = "#3B82F6";
    editForm.description = "";
    editForm.is_active = true;
};

// Métodos para eliminar
const deleteCourse = (course) => {
    courseToDelete.value = course;
    showDeleteModal.value = true;
};

const confirmDelete = async () => {
    try {
        isDeleteSubmitting.value = true;
        await router.delete(
            route(
                "schedule-management.courses.destroy",
                courseToDelete.value.id
            )
        );

        // Emitir evento para refrescar datos
        emit("course-deleted");

        // Cerrar modal
        closeDeleteModal();
    } catch (error) {
        console.error("Error deleting course:", error);
        // Aquí podrías mostrar un mensaje de error
    } finally {
        isDeleteSubmitting.value = false;
    }
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    courseToDelete.value = null;
};
</script>
