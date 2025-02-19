<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { BellAlertIcon, XMarkIcon } from '@heroicons/vue/24/solid'
import { useNotificationsStore } from '@/Stores/notificationsStore'
import { usePage } from '@inertiajs/vue3'

const notificationsStore = useNotificationsStore()
const isOpen = ref(false)
const dropdownRef = ref(null)

async function fetchNotifications() {
    const { data } = await axios.get(route('api.notifications.index'))
    notificationsStore.setNotifications(data)
}

async function markAsRead(notificationId) {
    await axios.post(route('api.notifications.read', notificationId))
    notificationsStore.removeNotification(notificationId)
}

function toggleDropdown() {
    isOpen.value = !isOpen.value
    if (isOpen.value) {
        fetchNotifications()
    }
}

// Cerrar dropdown al hacer clic fuera
function handleClickOutside(event) {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isOpen.value = false
    }
}

onMounted(() => {
    fetchNotifications()
    document.addEventListener('click', handleClickOutside)

    const userId = usePage().props.auth.user.id
    if (userId) {
        window.Echo.private(`App.Models.User.${userId}`)
            .notification((notification) => {
                notificationsStore.addNotification(notification)
            })
    }
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>

<template>
    <div class="relative" ref="dropdownRef">
        <button @click="toggleDropdown"
                class="relative flex items-center p-2 transition-all duration-200 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <BellAlertIcon class="w-6 h-6 text-gray-600" />
            <span v-if="notificationsStore.notifications.length > 0"
                  class="absolute flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full -top-1 -right-1 animate-pulse">
                {{ notificationsStore.notifications.length }}
            </span>
        </button>

        <div v-if="isOpen"
             class="absolute right-0 z-50 mt-2 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-lg shadow-lg w-80 ring-1 ring-black ring-opacity-5 focus:outline-none">
            <div class="flex items-center justify-between px-4 py-3 rounded-t-lg bg-gray-50">
                <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                <span v-if="notificationsStore.notifications.length > 0"
                      class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-200 rounded-full">
                    {{ notificationsStore.notifications.length }} new
                </span>
            </div>

            <div class="overflow-y-auto max-h-96">
                <div v-if="notificationsStore.notifications.length === 0" class="flex flex-col items-center justify-center py-8">
                    <BellAlertIcon class="w-12 h-12 mb-2 text-gray-300" />
                    <p class="text-sm text-gray-500">No notifications yet</p>
                </div>

                <ul v-else class="divide-y divide-gray-100">
                    <li v-for="notification in notificationsStore.notifications"
                        :key="notification.id"
                        class="relative transition-colors duration-150 hover:bg-gray-50">
                        <div class="px-4 py-3">
                            <div class="flex items-start justify-between">
                                <p class="text-sm text-gray-900">{{ notification?.data?.message }}</p>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <a :href="notification.data.url"
                                   class="inline-flex items-center text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                    View details
                                    <BellAlertIcon class="w-4 h-4 ml-1" />
                                </a>
                                <button @click="markAsRead(notification.id)" class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-100 rounded hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                                    Mark as read
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
