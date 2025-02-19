<script setup>
import { computed } from 'vue'
import { useNotificationStore } from '@/Stores/useNotificationStore'
import { XMarkIcon, CheckCircleIcon, ExclamationTriangleIcon, InformationCircleIcon } from '@heroicons/vue/24/solid'

const notificationStore = useNotificationStore()

const styles = computed(() => ({
    success: { bg: 'bg-green-100 text-green-800 border-green-400', icon: CheckCircleIcon },
    error: { bg: 'bg-red-100 text-red-800 border-red-400', icon: ExclamationTriangleIcon },
    info: { bg: 'bg-blue-100 text-blue-800 border-blue-400', icon: InformationCircleIcon }
}[notificationStore.type] || { bg: 'bg-gray-100 text-gray-800 border-gray-400', icon: InformationCircleIcon }))

const closeNotification = () => {
    notificationStore.show = false
}
</script>

<template>
    <transition name="fade">
        <div v-if="notificationStore.show"
            class="fixed flex items-center px-4 py-3 text-sm font-medium border rounded-lg shadow-md top-4 right-4 w-80"
            :class="[styles.bg, styles.border]">

            <component :is="styles.icon" class="w-5 h-5 mr-2" />

            <span class="flex-1">
                {{ notificationStore.message }}
            </span>

            <button @click="closeNotification" class="ml-2 text-gray-500 hover:text-gray-700" data-testid="close-notification">
                <XMarkIcon class="w-4 h-4" />
            </button>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease-out, transform 0.3s ease-out;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-5px);
}
</style>
