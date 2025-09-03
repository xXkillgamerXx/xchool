<template>
    <AdminLayout :user="$page.props.auth.user">
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Profesores de Mi Hijo
                    </h1>
                    <p class="mt-2 text-gray-600">
                        {{
                            child
                                ? `Profesores de ${child.name}`
                                : "Profesores del estudiante"
                        }}
                        {{ grade ? ` - Grado: ${grade.name}` : "" }}
                    </p>
                </div>

                <!-- Mensaje si no hay hijo asignado -->
                <div
                    v-if="!child"
                    class="bg-yellow-50 border border-yellow-200 rounded-lg p-6"
                >
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg
                                class="h-5 w-5 text-yellow-400"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Hijo no asignado
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>{{ message }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div
                    v-if="child && Object.keys(teachers).length > 0"
                    class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6"
                >
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-indigo-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Total Profesores
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ Object.keys(teachers).length }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-green-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Total Cursos
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{
                                                Object.values(teachers).reduce(
                                                    (total, teacher) =>
                                                        total +
                                                        teacher.courses.length,
                                                    0
                                                )
                                            }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="h-6 w-6 text-blue-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt
                                            class="text-sm font-medium text-gray-500 truncate"
                                        >
                                            Total Clases
                                        </dt>
                                        <dd
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{
                                                Object.values(teachers).reduce(
                                                    (total, teacher) =>
                                                        total +
                                                        teacher.total_classes,
                                                    0
                                                )
                                            }}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de profesores -->
                <div
                    v-if="child && Object.keys(teachers).length > 0"
                    class="bg-white shadow rounded-lg overflow-hidden"
                >
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">
                            Lista de Profesores
                        </h2>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div
                            v-for="(teacherData, teacherId) in teachers"
                            :key="teacherId"
                            class="p-6"
                        >
                            <div class="flex items-start space-x-4">
                                <!-- Avatar del profesor -->
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center"
                                    >
                                        <span
                                            class="text-indigo-600 font-medium text-lg"
                                        >
                                            {{
                                                getInitials(
                                                    teacherData.teacher.name
                                                )
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Información del profesor -->
                                <div class="flex-1 min-w-0">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <h3
                                                class="text-lg font-medium text-gray-900"
                                            >
                                                {{ teacherData.teacher.name }}
                                            </h3>
                                            <p class="text-sm text-gray-600">
                                                {{ teacherData.teacher.email }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                            >
                                                {{ teacherData.courses.length }}
                                                {{
                                                    teacherData.courses
                                                        .length === 1
                                                        ? "curso"
                                                        : "cursos"
                                                }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Cursos que enseña -->
                                    <div class="mt-4">
                                        <h4
                                            class="text-sm font-medium text-gray-900 mb-2"
                                        >
                                            Cursos que enseña:
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                v-for="course in teacherData.courses"
                                                :key="course.id"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                            >
                                                {{ course.name }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Horarios -->
                                    <div class="mt-4">
                                        <h4
                                            class="text-sm font-medium text-gray-900 mb-2"
                                        >
                                            Horarios:
                                        </h4>
                                        <div
                                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2"
                                        >
                                            <div
                                                v-for="schedule in teacherData.schedules"
                                                :key="schedule.id"
                                                class="bg-gray-50 rounded-lg p-3"
                                            >
                                                <div
                                                    class="flex items-center justify-between"
                                                >
                                                    <span
                                                        class="text-sm font-medium text-gray-900"
                                                    >
                                                        {{
                                                            schedule.course.name
                                                        }}
                                                    </span>
                                                    <span
                                                        class="text-xs text-gray-500"
                                                    >
                                                        {{
                                                            formatTime(
                                                                schedule.start_time
                                                            )
                                                        }}
                                                        -
                                                        {{
                                                            formatTime(
                                                                schedule.end_time
                                                            )
                                                        }}
                                                    </span>
                                                </div>
                                                <p
                                                    class="text-xs text-gray-600 mt-1"
                                                >
                                                    {{
                                                        getDayName(schedule.day)
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mensaje si no hay profesores -->
                <div
                    v-else-if="child"
                    class="bg-white shadow rounded-lg p-6 text-center"
                >
                    <svg
                        class="mx-auto h-12 w-12 text-gray-400"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                        />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        No hay profesores asignados
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        El grado de tu hijo aún no tiene profesores asignados.
                    </p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    teachers: Object,
    grade: Object,
    child: Object,
    user: Object,
    message: String,
});

const getInitials = (name) => {
    if (!name) return "P";
    const names = name.split(" ");
    if (names.length >= 2) {
        return (names[0][0] + names[1][0]).toUpperCase();
    }
    return names[0][0].toUpperCase();
};

const formatTime = (time) => {
    return new Date(`2000-01-01T${time}`).toLocaleTimeString("es-ES", {
        hour: "2-digit",
        minute: "2-digit",
    });
};

const getDayName = (day) => {
    const dayMap = {
        monday: "Lunes",
        tuesday: "Martes",
        wednesday: "Miércoles",
        thursday: "Jueves",
        friday: "Viernes",
    };
    return dayMap[day] || day;
};
</script>
