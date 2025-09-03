<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Estudiantes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Botón para crear nuevo estudiante -->
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">
                        Estudiantes Registrados
                    </h3>
                    <button
                        @click="showCreateForm = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                    >
                        + Crear Estudiante
                    </button>
                </div>

                <!-- Formulario para crear estudiante -->
                <div
                    v-if="showCreateForm"
                    class="bg-white p-6 rounded-lg shadow"
                >
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        Crear Nuevo Estudiante
                    </h4>
                    <form @submit.prevent="createStudent" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Nombre
                                </label>
                                <input
                                    v-model="studentForm.first_name"
                                    type="text"
                                    required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Apellido
                                </label>
                                <input
                                    v-model="studentForm.last_name"
                                    type="text"
                                    required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Email
                                </label>
                                <input
                                    v-model="studentForm.email"
                                    type="email"
                                    required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': emailError }"
                                />
                                <p
                                    v-if="emailError"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ emailError }}
                                </p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Padre
                                </label>
                                <div class="mt-1">
                                    <!-- Padre seleccionado -->
                                    <div
                                        v-if="selectedParent"
                                        class="flex items-center space-x-3 p-3 bg-gray-50 rounded-md border"
                                    >
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg"
                                            >
                                                {{
                                                    selectedParent.avatar_initial
                                                }}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ selectedParent.first_name }}
                                                {{ selectedParent.last_name }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ selectedParent.name }} •
                                                {{ selectedParent.email }}
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            @click="clearSelectedParent"
                                            class="text-gray-400 hover:text-gray-600"
                                        >
                                            <svg
                                                class="w-5 h-5"
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

                                    <!-- Botón para seleccionar padre -->
                                    <button
                                        v-else
                                        type="button"
                                        @click="showParentModal = true"
                                        class="w-full text-left p-3 border border-gray-300 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                        <span class="text-gray-500"
                                            >Selecciona un padre</span
                                        >
                                        <svg
                                            class="w-5 h-5 text-gray-400 float-right"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 9l-7 7-7-7"
                                            ></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Fecha de Nacimiento
                                </label>
                                <input
                                    v-model="studentForm.birth_date"
                                    type="date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Género
                                </label>
                                <select
                                    v-model="studentForm.gender"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="">Selecciona género</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="showCreateForm = false"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="studentForm.processing"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50"
                            >
                                <span v-if="studentForm.processing"
                                    >Creando...</span
                                >
                                <span v-else>Crear Estudiante</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabla de estudiantes -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            Lista de Estudiantes
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Estudiante
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Padre Asignado
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Email
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Fecha Nac.
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
                                    v-for="student in students"
                                    :key="student.id"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold text-lg"
                                                >
                                                    {{
                                                        getStudentInitial(
                                                            student
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ student.first_name }}
                                                    {{ student.last_name }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ student.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div
                                            v-if="student.parent"
                                            class="flex items-center"
                                        >
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm"
                                                >
                                                    {{
                                                        getParentInitial(
                                                            student.parent
                                                        )
                                                    }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{
                                                        student.parent
                                                            .first_name
                                                    }}
                                                    {{
                                                        student.parent.last_name
                                                    }}
                                                </div>
                                                <div
                                                    class="text-sm text-gray-500"
                                                >
                                                    {{ student.parent.name }}
                                                </div>
                                            </div>
                                        </div>
                                        <span
                                            v-else
                                            class="text-gray-400 text-sm"
                                            >Sin padre asignado</span
                                        >
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ student.email }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{
                                            student.birth_date
                                                ? formatDate(student.birth_date)
                                                : "-"
                                        }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                    >
                                        <button
                                            @click="editStudent(student)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3"
                                        >
                                            Editar
                                        </button>
                                        <button
                                            @click="
                                                confirmDeleteStudent(student)
                                            "
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
        </div>

        <!-- Modal de selección de padre -->
        <div
            v-if="showParentModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Seleccionar Padre
                        </h3>
                        <button
                            @click="showParentModal = false"
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

                    <!-- Búsqueda -->
                    <div class="mb-4">
                        <input
                            v-model="parentSearch"
                            type="text"
                            placeholder="Buscar por nombre, apellido, usuario o email..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            @input="searchParents"
                        />
                    </div>

                    <!-- Lista de padres -->
                    <div class="max-h-96 overflow-y-auto space-y-3">
                        <div
                            v-for="parent in filteredParents"
                            :key="parent.id"
                            @click="selectParent(parent)"
                            class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                        >
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg"
                                >
                                    {{ parent.avatar_initial }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ parent.first_name }}
                                    {{ parent.last_name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ parent.name }} • {{ parent.email }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <svg
                                    class="w-5 h-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    ></path>
                                </svg>
                            </div>
                        </div>

                        <div
                            v-if="filteredParents.length === 0"
                            class="text-center py-8 text-gray-500"
                        >
                            No se encontraron padres
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div
            v-if="showDeleteStudentModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Confirmar Eliminación
                        </h3>
                        <button
                            @click="showDeleteStudentModal = false"
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
                    <p class="text-sm text-gray-800">
                        ¿Estás seguro de que deseas eliminar al estudiante "{{
                            studentToDelete?.first_name
                        }}
                        {{ studentToDelete?.last_name }}"? Esta acción no se
                        puede deshacer.
                    </p>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button
                            @click="cancelDeleteStudent"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="deleteStudentConfirmed"
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

<script setup>
import { ref, computed, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router } from "@inertiajs/vue3";

// Props
const props = defineProps({
    students: Array,
    parents: Array,
});

// Estado reactivo
const showCreateForm = ref(false);
const showParentModal = ref(false);
const parentSearch = ref("");
const selectedParent = ref(null);
const emailError = ref("");
const showDeleteStudentModal = ref(false);
const studentToDelete = ref(null);

// Formulario
const studentForm = useForm({
    first_name: "",
    last_name: "",
    email: "",
    parent_id: "",
    birth_date: "",
    gender: "",
});

// Computed
const filteredParents = computed(() => {
    if (!parentSearch.value) return props.parents;

    const search = parentSearch.value.toLowerCase();
    return props.parents.filter(
        (parent) =>
            parent.first_name?.toLowerCase().includes(search) ||
            parent.last_name?.toLowerCase().includes(search) ||
            parent.name?.toLowerCase().includes(search) ||
            parent.email?.toLowerCase().includes(search)
    );
});

// Métodos
const createStudent = () => {
    if (!selectedParent.value) {
        alert("Debe seleccionar un padre para el estudiante");
        return;
    }

    studentForm.parent_id = selectedParent.value.id;

    studentForm.post(route("students.store"), {
        onSuccess: () => {
            showCreateForm.value = false;
            clearForm();
            clearSelectedParent();
        },
        onError: (errors) => {
            if (errors.email) {
                emailError.value = errors.email;
            }
        },
    });
};

const selectParent = (parent) => {
    selectedParent.value = parent;
    showParentModal.value = false;
    parentSearch.value = "";
};

const clearSelectedParent = () => {
    selectedParent.value = null;
    studentForm.parent_id = "";
};

const clearForm = () => {
    studentForm.reset();
    emailError.value = "";
};

const searchParents = () => {
    // La búsqueda se hace localmente con computed
};

const editStudent = (student) => {
    // TODO: Implementar edición
    console.log("Editar estudiante:", student);
};

const deleteStudent = (studentId) => {
    if (confirm("¿Está seguro de que desea eliminar este estudiante?")) {
        router.delete(route("students.destroy", studentId));
    }
};

const confirmDeleteStudent = (student) => {
    studentToDelete.value = student;
    showDeleteStudentModal.value = true;
};

const deleteStudentConfirmed = () => {
    if (studentToDelete.value) {
        router.delete(route("students.destroy", studentToDelete.value.id), {
            onSuccess: () => {
                showDeleteStudentModal.value = false;
                studentToDelete.value = null;
            },
        });
    }
};

const cancelDeleteStudent = () => {
    showDeleteStudentModal.value = false;
    studentToDelete.value = null;
};

const getStudentInitial = (student) => {
    return (student.first_name || student.name || "E").charAt(0).toUpperCase();
};

const getParentInitial = (parent) => {
    return (parent.first_name || parent.name || "P").charAt(0).toUpperCase();
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString("es-ES");
};

// Limpiar error de email cuando cambie
const clearEmailError = () => {
    emailError.value = "";
};

// Watchers
watch(() => studentForm.email, clearEmailError);
</script>
