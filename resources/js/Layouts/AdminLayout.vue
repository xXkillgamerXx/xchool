<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out"
            :class="{
                '-translate-x-full': !sidebarOpen && !isFullscreen,
                'translate-x-0': sidebarOpen || isFullscreen,
            }"
        >
            <!-- Logo y título -->
            <div
                class="flex items-center justify-between h-16 px-6 bg-indigo-600"
            >
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 bg-white rounded-lg flex items-center justify-center"
                    >
                        <span class="text-indigo-600 font-bold text-xl">X</span>
                    </div>
                    <h1 class="ml-3 text-white font-semibold text-lg">
                        Admin Panel
                    </h1>
                </div>
                <button
                    @click="sidebarOpen = false"
                    class="text-white lg:hidden"
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

            <!-- Navegación -->
            <nav class="mt-6 px-3">
                <div class="space-y-2">
                    <!-- Dashboard -->
                    <Link
                        :href="route('dashboard')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('dashboard')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"
                            ></path>
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"
                            ></path>
                        </svg>
                        Dashboard
                    </Link>

                    <!-- Gestión de Usuarios (Solo Colegio) -->
                    <Link
                        v-if="user.role && user.role.name === 'colegio'"
                        :href="route('user-management.index')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('user-management.*')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                            ></path>
                        </svg>
                        Gestión de Usuarios
                    </Link>

                    <!-- Gestión de Estudiantes (Solo Colegio) -->
                    <Link
                        v-if="user.role && user.role.name === 'colegio'"
                        :href="route('students.index')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('students.*')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            ></path>
                        </svg>
                        Gestión de Estudiantes
                    </Link>

                    <!-- Gestión de Horarios (Solo Colegio) -->
                    <Link
                        v-if="user.role && user.role.name === 'colegio'"
                        :href="route('schedule-management.index')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('schedule-management.*')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        Gestión de Horarios
                    </Link>

                    <!-- Mis Horarios (Solo Profesores) -->
                    <Link
                        v-if="user.role && user.role.name === 'profesor'"
                        :href="route('my-schedules.index')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('my-schedules.*')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        Mis Horarios
                    </Link>

                    <!-- Mi Horario (Solo Estudiantes) -->
                    <Link
                        v-if="user.role && user.role.name === 'estudiante'"
                        :href="route('student.schedule')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('student.schedule')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        Mi Horario
                    </Link>

                    <!-- Mis Cursos (Solo Estudiantes) -->
                    <Link
                        v-if="user.role && user.role.name === 'estudiante'"
                        :href="route('student.courses')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('student.courses')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            ></path>
                        </svg>
                        Mis Cursos
                    </Link>

                    <!-- Mis Profesores (Solo Estudiantes) -->
                    <Link
                        v-if="user.role && user.role.name === 'estudiante'"
                        :href="route('student.teachers')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('student.teachers')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            ></path>
                        </svg>
                        Mis Profesores
                    </Link>

                    <!-- Historial de Actividades (Solo Colegio) -->
                    <Link
                        v-if="user.role && user.role.name === 'colegio'"
                        :href="route('activity-log.index')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('activity-log.*')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                            ></path>
                        </svg>
                        Historial de Actividades
                    </Link>

                    <!-- ==================== OPCIONES PARA PADRES ==================== -->

                    <!-- Mi Hijo (Solo Padres) -->
                    <Link
                        v-if="user.role && user.role.name === 'padre'"
                        :href="route('parent.child')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('parent.child')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            ></path>
                        </svg>
                        Mi Hijo
                    </Link>

                    <!-- Horario de Mi Hijo (Solo Padres) -->
                    <Link
                        v-if="user.role && user.role.name === 'padre'"
                        :href="route('parent.child.schedule')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('parent.child.schedule')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        Horario de Mi Hijo
                    </Link>

                    <!-- Profesores de Mi Hijo (Solo Padres) -->
                    <Link
                        v-if="user.role && user.role.name === 'padre'"
                        :href="route('parent.child.teachers')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('parent.child.teachers')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                            ></path>
                        </svg>
                        Profesores de Mi Hijo
                    </Link>

                    <!-- Asistencia de Mi Hijo (Solo Padres) -->
                    <Link
                        v-if="user.role && user.role.name === 'padre'"
                        :href="route('parent.child.attendance')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('parent.child.attendance')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        Asistencia de Mi Hijo
                    </Link>

                    <!-- Calificaciones de Mi Hijo (Solo Padres) -->
                    <Link
                        v-if="user.role && user.role.name === 'padre'"
                        :href="route('parent.child.grades')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('parent.child.grades')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            ></path>
                        </svg>
                        Calificaciones de Mi Hijo
                    </Link>

                    <!-- Reportes de Mi Hijo (Solo Padres) -->
                    <Link
                        v-if="user.role && user.role.name === 'padre'"
                        :href="route('parent.child.reports')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('parent.child.reports')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            ></path>
                        </svg>
                        Reportes de Mi Hijo
                    </Link>

                    <!-- Perfil -->
                    <Link
                        :href="route('profile.edit')"
                        :class="[
                            'flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            route().current('profile.*')
                                ? 'bg-indigo-100 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900',
                        ]"
                    >
                        <svg
                            class="w-5 h-5 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            ></path>
                        </svg>
                        Mi Perfil
                    </Link>
                </div>
            </nav>

            <!-- Información del usuario -->
            <div
                class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200"
            >
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold text-sm"
                    >
                        {{ getUserInitials() }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">
                            {{ user.name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ user.role_display }}
                        </p>
                    </div>
                </div>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors"
                >
                    Cerrar Sesión
                </Link>
            </div>
        </div>

        <!-- Overlay para móvil -->
        <div
            v-if="sidebarOpen && !isFullscreen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden"
        ></div>

        <!-- Contenido principal -->
        <div
            :class="{
                'lg:pl-64': isLargeScreen || isFullscreen,
                'pl-0': !isLargeScreen && !isFullscreen,
            }"
        >
            <!-- Header móvil -->
            <div class="lg:hidden bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-4 py-3">
                    <button
                        @click="sidebarOpen = true"
                        class="text-gray-500 hover:text-gray-700"
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
                                d="M4 6h16M4 12h16M4 18h16"
                            ></path>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-900">
                        Xchool Admin
                    </h1>
                    <div class="w-6"></div>
                </div>
            </div>

            <!-- Contenido de la página -->
            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";

// Props
const props = defineProps({
    user: Object,
});

// Estado reactivo
const sidebarOpen = ref(true); // Cambiar a true por defecto
const isFullscreen = ref(false);
const isLargeScreen = ref(false);

// Métodos
const getUserInitials = () => {
    if (props.user.first_name && props.user.last_name) {
        return (
            props.user.first_name.charAt(0) + props.user.last_name.charAt(0)
        ).toUpperCase();
    }
    return props.user.name.charAt(0).toUpperCase();
};

// Detectar modo pantalla completa
const checkFullscreen = () => {
    isFullscreen.value = !!(
        document.fullscreenElement ||
        document.webkitFullscreenElement ||
        document.mozFullScreenElement ||
        document.msFullscreenElement
    );
};

// Detectar tamaño de pantalla
const checkScreenSize = () => {
    isLargeScreen.value = window.innerWidth >= 1024; // lg breakpoint de Tailwind
};

// Manejar cambios de tamaño de ventana
const handleResize = () => {
    checkScreenSize();
    // Si es pantalla grande o está en modo pantalla completa, mantener sidebar abierto
    // Si es pantalla pequeña, ocultar sidebar
    if (isLargeScreen.value || isFullscreen.value) {
        sidebarOpen.value = true;
    } else {
        sidebarOpen.value = false;
    }
};

// Manejar eventos de pantalla completa
const handleFullscreenChange = () => {
    checkFullscreen();
    // Si está en modo pantalla completa, mantener sidebar abierto
    if (isFullscreen.value) {
        sidebarOpen.value = true;
    }
};

// Lifecycle hooks
onMounted(() => {
    checkFullscreen();
    checkScreenSize();

    // Configurar sidebar inicial basado en tamaño de pantalla
    // En móviles (pantalla pequeña), ocultar sidebar por defecto
    // En desktop (pantalla grande), mostrar sidebar por defecto
    if (!isLargeScreen.value && !isFullscreen.value) {
        sidebarOpen.value = false;
    } else {
        sidebarOpen.value = true;
    }

    // Event listeners
    window.addEventListener("resize", handleResize);
    document.addEventListener("fullscreenchange", handleFullscreenChange);
    document.addEventListener("webkitfullscreenchange", handleFullscreenChange);
    document.addEventListener("mozfullscreenchange", handleFullscreenChange);
    document.addEventListener("MSFullscreenChange", handleFullscreenChange);
});

onUnmounted(() => {
    window.removeEventListener("resize", handleResize);
    document.removeEventListener("fullscreenchange", handleFullscreenChange);
    document.removeEventListener(
        "webkitfullscreenchange",
        handleFullscreenChange
    );
    document.removeEventListener("mozfullscreenchange", handleFullscreenChange);
    document.removeEventListener("MSFullscreenChange", handleFullscreenChange);
});
</script>
