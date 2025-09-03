<template>
    <AdminLayout :user="$page.props.auth.user">
        <Head title="Gestión de Horarios" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Gestión de Horarios
                </h1>
                <p class="mt-2 text-gray-600">
                    Administra grados, cursos y horarios del colegio
                </p>
            </div>

            <!-- Tabs de navegación -->
            <div class="bg-white shadow rounded-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6">
                        <button
                            v-for="tab in tabs"
                            :key="tab.id"
                            @click="activeTab = tab.id"
                            :class="[
                                'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
                                activeTab === tab.id
                                    ? 'border-indigo-500 text-indigo-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                            ]"
                        >
                            {{ tab.name }}
                        </button>
                    </nav>
                </div>

                <!-- Contenido de las tabs -->
                <div class="p-6">
                    <!-- Tab Grados -->
                    <div v-if="activeTab === 'grades'" class="space-y-6">
                        <GradesTab
                            :grades="grades"
                            @grade-created="refreshData"
                            @grade-updated="refreshData"
                            @grade-deleted="refreshData"
                        />
                    </div>

                    <!-- Tab Cursos -->
                    <div v-if="activeTab === 'courses'" class="space-y-6">
                        <CoursesTab
                            :courses="courses"
                            @course-created="refreshData"
                            @course-updated="refreshData"
                            @course-deleted="refreshData"
                        />
                    </div>

                    <!-- Tab Horarios -->
                    <div v-if="activeTab === 'schedules'" class="space-y-6">
                        <SchedulesTab
                            :grades="grades"
                            :courses="courses"
                            :teachers="teachers"
                            @schedule-created="refreshData"
                            @schedule-updated="refreshData"
                            @schedule-deleted="refreshData"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Head } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import GradesTab from "./GradesTab.vue";
import CoursesTab from "./CoursesTab.vue";
import SchedulesTab from "./SchedulesTab.vue";

// Props
const props = defineProps({
    grades: Array,
    courses: Array,
    teachers: Array,
});

// Estado reactivo
const activeTab = ref("grades");

// Tabs disponibles
const tabs = [
    { id: "grades", name: "Grados" },
    { id: "courses", name: "Cursos" },
    { id: "schedules", name: "Horarios" },
];

// Métodos
const refreshData = () => {
    // Recargar la página para obtener datos actualizados
    window.location.reload();
};
</script>
