<template>
    <Head title="Gestión de Estudiantes" />

    <AdminLayout :user="$page.props.auth.user">
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Gestión de Estudiantes
                </h1>
                <p class="mt-2 text-gray-600">
                    Crea y administra estudiantes del sistema
                </p>
            </div>

            <!-- Botón para crear estudiantes -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">
                        Gestión de Estudiantes
                    </h3>
                    <button
                        @click="showCreateStudentModal = true"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors"
                    >
                        Crear Estudiante
                    </button>
                </div>
            </div>

            <!-- Lista de estudiantes -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        Estudiantes Registrados
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
                                    Email
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Padre
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Grado
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Fecha de Nacimiento
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="student in students" :key="student.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center"
                                            >
                                                <span
                                                    class="text-sm font-medium text-indigo-600"
                                                >
                                                    {{
                                                        getInitials(
                                                            student.first_name,
                                                            student.last_name
                                                        )
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ student.first_name }}
                                                {{ student.last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                @{{ student.name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ student.email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        v-if="student.parent"
                                        class="flex items-center"
                                    >
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div
                                                class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center"
                                            >
                                                <span
                                                    class="text-xs font-medium text-green-600"
                                                >
                                                    {{
                                                        student.parent
                                                            .avatar_initial
                                                    }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{
                                                    student.parent.display_name
                                                }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                @{{ student.parent.name }}
                                            </div>
                                        </div>
                                    </div>
                                    <span v-else class="text-gray-400"
                                        >Sin asignar</span
                                    >
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        v-if="student.grade"
                                        class="flex items-center"
                                    >
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                        >
                                            {{ student.grade.name }}
                                            {{
                                                student.grade.section
                                                    ? "-" +
                                                      student.grade.section
                                                    : ""
                                            }}
                                        </span>
                                    </div>
                                    <span v-else class="text-gray-400"
                                        >Sin asignar</span
                                    >
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{
                                        student.birth_date
                                            ? new Date(
                                                  student.birth_date
                                              ).toLocaleDateString()
                                            : "N/A"
                                    }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2"
                                >
                                    <button
                                        @click="editStudent(student)"
                                        class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                    >
                                        Editar
                                    </button>
                                    <button
                                        @click="assignGrade(student)"
                                        class="text-green-600 hover:text-green-900 transition-colors"
                                    >
                                        {{
                                            student.grade
                                                ? "Cambiar Grado"
                                                : "Asignar Grado"
                                        }}
                                    </button>
                                    <button
                                        v-if="student.grade"
                                        @click="removeFromGrade(student)"
                                        class="text-orange-600 hover:text-orange-900 transition-colors"
                                    >
                                        Quitar Grado
                                    </button>
                                    <button
                                        @click="confirmDeleteStudent(student)"
                                        class="text-red-600 hover:text-red-900 transition-colors"
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

        <!-- Modal para crear estudiante -->
        <div
            v-if="showCreateStudentModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-40"
            @click.self="showCreateStudentModal = false"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Crear Nuevo Estudiante
                        </h3>
                        <button
                            @click="showCreateStudentModal = false"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg
                                class="h-6 w-6"
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

                    <form @submit.prevent="submitForm" class="space-y-4">
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Email</label
                                >
                                <input
                                    v-model="form.email"
                                    type="email"
                                    required
                                    @blur="validateEmail"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': emailError }"
                                />
                                <div
                                    v-if="emailError"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ emailError }}
                                </div>
                                <div
                                    v-if="form.errors.email"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Padre</label
                                >
                                <div class="relative">
                                    <input
                                        :value="
                                            getParentDisplayName(selectedParent)
                                        "
                                        type="text"
                                        readonly
                                        placeholder="Selecciona un padre"
                                        @click="showParentModal = true"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 cursor-pointer"
                                    />
                                    <div
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    >
                                        <button
                                            type="button"
                                            @click="clearSelectedParent"
                                            class="text-gray-400 hover:text-gray-600 mr-2"
                                            title="Limpiar selección"
                                        >
                                            <svg
                                                class="h-4 w-4"
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
                                                d="M19 9l-7 7-7-7"
                                            ></path>
                                        </svg>
                                    </div>
                                </div>
                                <div
                                    v-if="form.errors.parent_id"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.parent_id }}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Fecha de Nacimiento</label
                                >
                                <input
                                    v-model="form.birth_date"
                                    type="date"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <div
                                    v-if="form.errors.birth_date"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.birth_date }}
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Género</label
                                >
                                <select
                                    v-model="form.gender"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="">Selecciona género</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="otro">Otro</option>
                                </select>
                                <div
                                    v-if="form.errors.gender"
                                    class="text-red-500 text-sm mt-1"
                                >
                                    {{ form.errors.gender }}
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="showCreateStudentModal = false"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 transition-colors"
                            >
                                {{
                                    form.processing
                                        ? "Creando..."
                                        : "Crear Estudiante"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de selección de padre -->
        <div
            v-if="showParentModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="showParentModal = false"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
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
                                class="h-6 w-6"
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
                            placeholder="Buscar padre..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        />
                    </div>

                    <!-- Lista de padres -->
                    <div class="max-h-60 overflow-y-auto space-y-2">
                        <div
                            v-if="filteredParents.length === 0"
                            class="text-center py-4 text-gray-500"
                        >
                            {{
                                parentSearch
                                    ? "No se encontraron padres"
                                    : "No hay padres registrados"
                            }}
                        </div>
                        <div
                            v-for="parent in filteredParents"
                            :key="parent.id"
                            @click="selectParent(parent)"
                            class="p-3 border rounded-lg cursor-pointer hover:bg-indigo-50 hover:border-indigo-300 transition-colors"
                        >
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div
                                        class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center"
                                    >
                                        <span
                                            class="text-sm font-medium text-indigo-600"
                                        >
                                            {{
                                                getInitials(
                                                    parent.first_name,
                                                    parent.last_name
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ getParentDisplayName(parent) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @{{ parent.name }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ parent.email }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button
                            @click="showParentModal = false"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de edición de estudiante -->
        <div
            v-if="showEditStudentModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-40"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Editar Estudiante
                    </h3>

                    <form @submit.prevent="updateStudent" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Nombre</label
                                >
                                <input
                                    v-model="editStudentForm.first_name"
                                    type="text"
                                    required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Apellido</label
                                >
                                <input
                                    v-model="editStudentForm.last_name"
                                    type="text"
                                    required
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Email</label
                            >
                            <input
                                v-model="editStudentForm.email"
                                type="email"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700"
                                >Padre</label
                            >
                            <div class="relative">
                                <input
                                    :value="
                                        getParentDisplayName(selectedParent)
                                    "
                                    type="text"
                                    readonly
                                    placeholder="Selecciona un padre"
                                    @click="showParentModal = true"
                                    class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50 cursor-pointer"
                                />
                                <div
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                >
                                    <button
                                        type="button"
                                        @click="clearSelectedParent"
                                        class="text-gray-400 hover:text-gray-600"
                                        title="Limpiar selección"
                                    >
                                        <svg
                                            class="h-4 w-4"
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
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Fecha de Nacimiento</label
                                >
                                <input
                                    v-model="editStudentForm.birth_date"
                                    type="date"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                />
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Género</label
                                >
                                <select
                                    v-model="editStudentForm.gender"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
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
                                @click="cancelEdit"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="editStudentForm.processing"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50 transition-colors"
                            >
                                {{
                                    editStudentForm.processing
                                        ? "Actualizando..."
                                        : "Actualizar"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación para eliminar estudiante -->
        <div
            v-if="showDeleteStudentModal"
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
                            ¿Está seguro de que desea eliminar al estudiante
                            <strong
                                >{{ studentToDelete?.first_name }}
                                {{ studentToDelete?.last_name }}</strong
                            >?
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-3 mt-4">
                        <button
                            @click="cancelDeleteStudent"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="deleteStudentConfirmed"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
                        >
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para asignar grado -->
        <div
            v-if="showAssignGradeModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{
                            studentToAssignGrade?.grade
                                ? "Cambiar Grado"
                                : "Asignar Grado"
                        }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Estudiante:
                        <strong
                            >{{ studentToAssignGrade?.first_name }}
                            {{ studentToAssignGrade?.last_name }}</strong
                        >
                    </p>

                    <form
                        @submit.prevent="confirmAssignGrade"
                        class="space-y-4"
                    >
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-2"
                            >
                                Seleccionar Grado
                            </label>
                            <select
                                v-model="assignGradeForm.grade_id"
                                required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Selecciona un grado</option>
                                <option
                                    v-for="grade in grades"
                                    :key="grade.id"
                                    :value="grade.id"
                                >
                                    {{ grade.name }}
                                    {{
                                        grade.section ? "-" + grade.section : ""
                                    }}
                                </option>
                            </select>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="cancelAssignGrade"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="assignGradeForm.processing"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium disabled:opacity-50 transition-colors"
                            >
                                {{
                                    assignGradeForm.processing
                                        ? "Asignando..."
                                        : "Asignar Grado"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    students: Array,
    parents: Array,
    grades: Array,
});

// Debug temporal
console.log("Parents data:", props.parents);

// Estados reactivos
const showCreateStudentModal = ref(false);
const showParentModal = ref(false);
const showEditStudentModal = ref(false);
const showDeleteStudentModal = ref(false);
const showAssignGradeModal = ref(false);
const parentSearch = ref("");
const selectedParent = ref({});
const studentToEdit = ref(null);
const studentToDelete = ref(null);
const studentToAssignGrade = ref(null);

// Formularios
const form = useForm({
    first_name: "",
    last_name: "",
    email: "",
    parent_id: "",
    birth_date: "",
    gender: "",
});

const editStudentForm = useForm({
    first_name: "",
    last_name: "",
    email: "",
    parent_id: "",
    birth_date: "",
    gender: "",
});

const assignGradeForm = useForm({
    grade_id: "",
});

// Validación de email
const emailError = ref("");

const validateEmail = () => {
    const email = form.email;
    if (email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            emailError.value = "Formato de email inválido";
        } else {
            emailError.value = "";
        }
    }
};

// Filtrado de padres
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
const submitForm = () => {
    if (selectedParent.value.id) {
        form.parent_id = selectedParent.value.id;
    }

    form.post(route("students.store"), {
        onSuccess: () => {
            form.reset();
            selectedParent.value = {};
            showCreateStudentModal.value = false;
        },
    });
};

const selectParent = (parent) => {
    selectedParent.value = parent;
    parentSearch.value = ""; // Limpiar búsqueda
    showParentModal.value = false;
};

const clearSelectedParent = () => {
    selectedParent.value = {};
    form.parent_id = "";
    editStudentForm.parent_id = "";
};

const editStudent = (student) => {
    studentToEdit.value = student;
    editStudentForm.first_name = student.first_name;
    editStudentForm.last_name = student.last_name;
    editStudentForm.email = student.email;
    editStudentForm.parent_id = student.parent_id;
    editStudentForm.birth_date = student.birth_date;
    editStudentForm.gender = student.gender;

    // Cargar el padre actual del estudiante
    if (student.parent) {
        selectedParent.value = student.parent;
    } else {
        selectedParent.value = {};
    }

    showEditStudentModal.value = true;
};

const updateStudent = () => {
    if (selectedParent.value.id) {
        editStudentForm.parent_id = selectedParent.value.id;
    }

    editStudentForm.put(route("students.update", studentToEdit.value.id), {
        onSuccess: () => {
            showEditStudentModal.value = false;
            studentToEdit.value = null;
            selectedParent.value = {};
        },
    });
};

const cancelEdit = () => {
    showEditStudentModal.value = false;
    studentToEdit.value = null;
    selectedParent.value = {};
};

const confirmDeleteStudent = (student) => {
    studentToDelete.value = student;
    showDeleteStudentModal.value = true;
};

const deleteStudentConfirmed = () => {
    if (studentToDelete.value) {
        useForm().delete(route("students.destroy", studentToDelete.value.id), {
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

const assignGrade = (student) => {
    studentToAssignGrade.value = student;
    assignGradeForm.grade_id = student.grade_id || "";
    showAssignGradeModal.value = true;
};

const confirmAssignGrade = () => {
    if (studentToAssignGrade.value) {
        assignGradeForm.post(
            route("students.assign-grade", studentToAssignGrade.value.id),
            {
                onSuccess: () => {
                    showAssignGradeModal.value = false;
                    studentToAssignGrade.value = null;
                    assignGradeForm.reset();
                },
            }
        );
    }
};

const removeFromGrade = (student) => {
    if (
        confirm(
            `¿Está seguro de que desea quitar a ${student.first_name} ${student.last_name} del grado?`
        )
    ) {
        useForm().delete(route("students.remove-grade", student.id), {
            onSuccess: () => {
                // La página se actualizará automáticamente
            },
        });
    }
};

const cancelAssignGrade = () => {
    showAssignGradeModal.value = false;
    studentToAssignGrade.value = null;
    assignGradeForm.reset();
};

const getParentDisplayName = (parent) => {
    if (!parent || !parent.id) return "";

    if (parent.display_name) {
        return parent.display_name;
    }

    if (parent.first_name && parent.last_name) {
        return `${parent.first_name} ${parent.last_name}`;
    }

    if (parent.name) {
        return parent.name;
    }

    return "";
};

const getInitials = (firstName, lastName) => {
    if (firstName && lastName) {
        return (firstName.charAt(0) + lastName.charAt(0)).toUpperCase();
    }
    return "?";
};
</script>
