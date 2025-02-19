<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { useDropdownStore } from '@/Stores/dropdownStore'
import { useTaskPriorityStore } from '@/Stores/useTaskPriorityStore'
import {
    EllipsisVerticalIcon,
    PencilIcon,
    TrashIcon,
    CalendarIcon,
    UserIcon,
    ClockIcon
} from '@heroicons/vue/24/solid'

const props = defineProps({ task: Object })
const emit = defineEmits(['edit', 'delete'])

const dropdownStore = useDropdownStore()
const priorityStore = useTaskPriorityStore()
const dropdownRef = ref(null)

const isOpen = computed(() => dropdownStore.openDropdown === props.task.id)

function toggleMenu(event) {
    event.stopPropagation()
    if (isOpen.value) {
        dropdownStore.closeDropdown()
    } else {
        dropdownStore.setDropdown(props.task.id)
    }
}

function handleClickOutside(event) {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        dropdownStore.closeDropdown()
    }
}

const formattedDueDate = computed(() => {
    if (!props.task.due_date) return null;
    const date = new Date(props.task.due_date);
    return date.toLocaleDateString('default', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
});

const isOverdue = computed(() => {
    if (!props.task.due_date) return false;
    return new Date(props.task.due_date) < new Date();
});

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>

<template>
    <div @click="emit('edit', task, false)"
         class="relative p-4 bg-white rounded-lg shadow-sm cursor-pointer transition-all duration-200 hover:shadow-md hover:translate-y-[-2px] border border-gray-300">
        <!-- Dropdown Menu Button -->
        <div class="absolute top-3 right-3" ref="dropdownRef">
            <button @click.stop="toggleMenu"
                    class="p-2 transition-colors duration-150 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    data-testid="task-options">
                <EllipsisVerticalIcon class="w-5 h-5 text-gray-500" />
            </button>

            <!-- Dropdown Menu -->
            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <div v-if="isOpen"
                     class="absolute right-0 z-50 mt-1 overflow-hidden bg-white border rounded-lg shadow-lg w-36">
                    <button @click.stop="emit('edit', task, true)"
                            class="flex items-center w-full px-4 py-2 text-sm text-gray-700 transition-colors duration-150 hover:bg-gray-50">
                        <PencilIcon class="w-4 h-4 mr-2 text-gray-500" />
                        Edit Task
                    </button>
                    <button @click.stop="emit('delete', task.id)"
                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 transition-colors duration-150 hover:bg-red-50">
                        <TrashIcon class="w-4 h-4 mr-2" />
                        Delete
                    </button>
                </div>
            </transition>
        </div>

        <!-- Task Content -->
        <div class="pr-8">
            <h3 class="text-lg font-semibold text-gray-800 line-clamp-1">{{ task.title }}</h3>
            <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ task.description || 'No description' }}</p>

            <!-- Priority Badge -->
            <div class="flex flex-wrap items-center gap-2 mt-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                      :class="priorityStore.getPriorityColor(task.priority)">
                    {{ priorityStore.getPriorityLabel(task.priority) }}
                </span>

                <!-- Due Date Badge -->
                <span v-if="formattedDueDate"
                      class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full"
                      :class="isOverdue ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'">
                    <CalendarIcon class="w-3.5 h-3.5 mr-1" />
                    {{ formattedDueDate }}
                </span>
            </div>

            <!-- Assigned User -->
            <div v-if="task.assigned_user" class="flex items-center gap-2 mt-3">
                <div class="flex items-center justify-center w-6 h-6 text-sm font-medium text-indigo-700 bg-indigo-100 rounded-full">
                    {{ task.assigned_user.name.charAt(0).toUpperCase() }}
                </div>
                <span class="text-sm text-gray-700">{{ task.assigned_user.name }}</span>
            </div>
        </div>
    </div>
</template>
