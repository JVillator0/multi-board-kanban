<script setup>
import { onMounted, ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import TaskCard from './TaskCard.vue'
import TaskModal from './TaskModal.vue'
import ConfirmModal from '@/Components/UI/ConfirmModal.vue'
import { PlusIcon } from '@heroicons/vue/24/solid'
import { useDropdownStore } from '@/Stores/dropdownStore'
import draggable from 'vuedraggable'
import axios from 'axios'
import { useNotificationStore } from '@/Stores/useNotificationStore'

const notificationStore = useNotificationStore()
const dropdownStore = useDropdownStore()

const props = defineProps({
    tasks: Array,
    boardId: Number
})

const statuses = ['backlog', 'todo', 'in_progress', 'done']
const statusLabels = {
    backlog: 'Backlog',
    todo: 'To Do',
    in_progress: 'In Progress',
    done: 'Done'
}

const taskList = ref(
    statuses.reduce((acc, status) => {
        acc[status] = props.tasks.filter(task => task.status === status).sort((a, b) => a.order - b.order);
        return acc;
    }, {})
)

async function onTaskDrop(event, newStatus) {
    const movedTask = event.added ? event.added.element : null;

    if (!movedTask) return;

    if (movedTask.status !== newStatus) {
        try {
            await axios.patch(route('api.tasks.update', movedTask.id), {
                status: newStatus,
                order: taskList.value[newStatus].length - 1
            });

            movedTask.status = newStatus;
        } catch (error) {
            console.error('Error updating task status:', error);
        }
    }
}

async function onTaskReorder(status) {
    const updatedTasks = taskList.value[status].map((task, index) => {
        task.order = index;
        return { id: task.id, order: index };
    });

    try {
        await axios.post(route('api.tasks.reorder'), { tasks: updatedTasks });
    } catch (error) {
        console.error('Error updating task order:', error);
    }
}

const showModal = ref(false)
const showDeleteModal = ref(false)
const selectedTask = ref(null)
const isCreating = ref(false)
const editMode = ref(false)
const taskToDelete = ref(null)

function openModal(task = null, isEditMode = false) {
    dropdownStore.closeDropdown()
    selectedTask.value = task || { title: '', description: '', priority: 'medium', status: 'backlog', board_id: props.boardId }
    isCreating.value = task.id == null
    showModal.value = true
    editMode.value = isEditMode
}

function closeModal() {
    showModal.value = false
    selectedTask.value = null
    editMode.value = false
}

function cloaseDeleteModal() {
    showDeleteModal.value = false
    taskToDelete.value = null
    if (selectedTask.value) {
        showModal.value = true
    }
}

function deleteBoard(taskId) {
    taskToDelete.value = taskId
    showDeleteModal.value = true
    showModal.value = false
    dropdownStore.closeDropdown()
}

function confirmDelete() {
    if (taskToDelete.value) {
        router.delete(route('tasks.destroy', taskToDelete.value))
    }
    showDeleteModal.value = false
    taskToDelete.value = null
    selectedTask.value = null
    notificationStore.notify('Task deleted successfully!', 'success')

}

async function fetchTasks() {
    try {
        const response = await axios.get(route('api.tasks.index', { board_id: props.boardId }))
        taskList.value = statuses.reduce((acc, status) => {
            acc[status] = response.data.filter(task => task.status === status).sort((a, b) => a.order - b.order)
            return acc
        }, {})
    } catch (error) {
        console.error('Error fetching tasks:', error.response || error)
    }
}

onMounted(() => {
    const userId = usePage().props.auth.user.id;
    if (userId) {
        window.Echo.private(`App.Models.User.${userId}`)
            .notification(async (notification) => {
                if (notification.type === 'App\\Notifications\\TaskUpdatedNotification') {
                    fetchTasks()
                }
            });
    }
});

watch(() => props.tasks, (newTasks) => {
    taskList.value = statuses.reduce((acc, status) => {
        acc[status] = newTasks.filter(task => task.status === status).sort((a, b) => a.order - b.order)
        return acc
    }, {})
})
</script>

<template>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div v-for="status in statuses" :key="status" class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-center justify-between pb-2 border-b-2" :class="{
                'border-purple-500': status === 'backlog',
                'border-blue-500': status === 'todo',
                'border-orange-500': status === 'in_progress',
                'border-green-500': status === 'done'
            }">
                <h3 class="font-semibold text-md">
                    {{ statusLabels[status] }}
                    <span class="text-sm text-gray-500">({{ taskList[status].length }})</span>
                </h3>
                <button @click="openModal({ status: status, board_id: boardId, priority: 'medium' })"
                    class="p-1 text-white rounded"
                    :class="{
                        'bg-purple-500': status === 'backlog',
                        'bg-blue-500': status === 'todo',
                        'bg-orange-500': status === 'in_progress',
                        'bg-green-500': status === 'done'
                    }"
                    :data-testid="`add-task-${status}`"
                >
                    <PlusIcon class="w-5 h-5" />
                </button>
            </div>

            <!-- Drag and Drop con vuedraggable -->
            <draggable
                v-model="taskList[status]"
                :group="{ name: 'tasks', pull: true, put: true }"
                :sort="true"
                item-key="id"
                @end="onTaskReorder(status)"
                @change="onTaskDrop($event, status)"
                class="mt-4 space-y-2"
            >
                <template #item="{ element: task }">
                    <TaskCard
                        :task="task"
                        @edit="openModal"
                        @delete="deleteBoard"
                    />
                </template>
            </draggable>

            <p v-if="taskList[status].length === 0" class="mt-4 text-sm text-gray-500">
                No tasks in this section
            </p>
        </div>
    </div>

    <TaskModal
        v-if="showModal"
        :show="showModal"
        :task="selectedTask"
        :isCreating="isCreating"
        :editMode="editMode"
        @close="closeModal()"
        @delete="deleteBoard(selectedTask.id)"
    />

    <ConfirmModal
        :show="showDeleteModal"
        title="Delete Task"
        message="Are you sure you want to delete this task? This action cannot be undone."
        @confirm="confirmDelete"
        @close="cloaseDeleteModal"
    />
</template>
