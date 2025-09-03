<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header con selector de hijos -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{
                                    children && children.length > 1
                                        ? "Estudiantes"
                                        : "Estudiante"
                                }}
                            </h1>
                            <p class="mt-2 text-gray-600">
                                {{
                                    selectedChild
                                        ? `${getTabLabel(currentTab)} - ${
                                              selectedChild.name
                                          }`
                                        : "Selecciona un estudiante"
                                }}
                                {{
                                    selectedChild && selectedChild.grade
                                        ? ` (${selectedChild.grade.full_name})`
                                        : ""
                                }}
                            </p>
                        </div>

                        <!-- Selector de hijos -->
                        <div
                            v-if="children && children.length > 1"
                            class="ml-4"
                        >
                            <select
                                v-model="selectedChildId"
                                @change="changeChild"
                                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Seleccionar hijo</option>
                                <option
                                    v-for="child in children"
                                    :key="child.id"
                                    :value="child.id"
                                >
                                    {{ child.name }} -
                                    {{
                                        child.grade
                                            ? child.grade.full_name
                                            : "Sin grado"
                                    }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tabs de navegación -->
                <div
                    v-if="selectedChild"
                    class="bg-white shadow rounded-lg mb-6"
                >
                    <div class="border-b border-gray-200">
                        <nav
                            class="-mb-px flex space-x-8 px-6"
                            aria-label="Tabs"
                        >
                            <button
                                v-for="tab in tabs"
                                :key="tab.name"
                                @click="navigateToTab(tab.route)"
                                :class="[
                                    currentTab === tab.name
                                        ? 'border-indigo-500 text-indigo-600'
                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                    'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm',
                                ]"
                            >
                                {{ tab.label }}
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Contenido de la página -->
                <slot :selectedChild="selectedChild" :children="children" />
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    children: {
        type: Array,
        default: () => [],
    },
    currentTab: {
        type: String,
        default: "dashboard",
    },
});

// Estado reactivo
const selectedChildId = ref("");

// Computed
const selectedChild = computed(() => {
    if (selectedChildId.value && props.children.length > 0) {
        return (
            props.children.find((child) => child.id == selectedChildId.value) ||
            props.children[0]
        );
    }
    return props.children.length > 0 ? props.children[0] : null;
});

// Tabs disponibles
const tabs = [
    { name: "dashboard", label: "Dashboard", route: "parent.child.dashboard" },
    { name: "schedule", label: "Horario", route: "parent.child.schedule" },
    { name: "teachers", label: "Profesores", route: "parent.child.teachers" },
    {
        name: "attendance",
        label: "Asistencia",
        route: "parent.child.attendance",
    },
    { name: "grades", label: "Calificaciones", route: "parent.child.grades" },
    { name: "reports", label: "Reportes", route: "parent.child.reports" },
];

// Métodos
const changeChild = () => {
    if (selectedChildId.value) {
        // Navegar a la misma tab pero con el hijo seleccionado
        const currentRoute = tabs.find((tab) => tab.name === props.currentTab);
        if (currentRoute) {
            router.visit(
                route(currentRoute.route, { child_id: selectedChildId.value }),
                { preserveState: true, preserveScroll: true }
            );
        }
    }
};

const navigateToTab = (routeName) => {
    if (selectedChildId.value) {
        router.visit(route(routeName, { child_id: selectedChildId.value }));
    } else {
        router.visit(route(routeName));
    }
};

const getTabLabel = (tabName) => {
    const tab = tabs.find((t) => t.name === tabName);
    return tab ? tab.label : "Información";
};

// Watch para actualizar cuando cambien los children
watch(
    () => props.children,
    (newChildren) => {
        if (newChildren.length > 0) {
            // Si no hay hijo seleccionado, seleccionar el primero
            if (!selectedChildId.value) {
                selectedChildId.value = newChildren[0].id;
            }
            // Si el hijo seleccionado ya no existe, seleccionar el primero
            else if (
                !newChildren.find((child) => child.id == selectedChildId.value)
            ) {
                selectedChildId.value = newChildren[0].id;
            }
        } else {
            selectedChildId.value = "";
        }
    },
    { immediate: true }
);

// Watch para detectar cambios en la URL (child_id)
watch(
    () => route().params.child_id,
    (newChildId) => {
        if (newChildId && props.children.length > 0) {
            const childExists = props.children.find(
                (child) => child.id == newChildId
            );
            if (childExists) {
                selectedChildId.value = newChildId;
            }
        }
    },
    { immediate: true }
);
</script>
