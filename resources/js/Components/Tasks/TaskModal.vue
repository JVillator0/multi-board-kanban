<script setup>
import { ref, watch, onMounted } from 'vue'
import Modal from '@/Components/Modal.vue'
import TaskForm from './TaskForm.vue'
import TaskComments from './TaskComments.vue'
import { PencilIcon, TrashIcon, EllipsisVerticalIcon, XMarkIcon } from '@heroicons/vue/24/solid'
import { useDropdownStore } from '@/Stores/dropdownStore'

const props = defineProps({
    show: Boolean,
    task: Object,
    isCreating: Boolean,
    editMode: Boolean
})

const emit = defineEmits(['close', 'delete'])

const isEditing = ref(props.isCreating)
const showMenu = ref(false)

function enableEditing() {
    isEditing.value = true
    showMenu.value = false
}

function discardChanges() {
    isEditing.value = false
}

function closeModal() {
    isEditing.value = false
    emit('close')
}

watch(() => props.show, (newValue) => {
    if (newValue) {
        isEditing.value = props.isCreating
        useDropdownStore().closeDropdown()
    }
})

onMounted(() => {
    if (props.editMode) {
        enableEditing()
    }
})
</script>

<template>
    <Modal :show="show" maxWidth="2xl" @close="closeModal">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Task</h3>
            <div class="flex items-center gap-2">
                <div v-if="task.id && ! isEditing" class="relative">
                    <button @click="showMenu = !showMenu" class="p-2 rounded hover:bg-gray-100" data-testid="modal-task-options">
                        <EllipsisVerticalIcon class="w-5 h-5 text-gray-500" />
                    </button>
                    <div
                        v-if="showMenu"
                        class="absolute right-0 z-50 w-40 bg-white border rounded-lg shadow-lg"
                    >
                        <button
                            @click="enableEditing"
                            class="flex items-center w-full gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        >
                            <PencilIcon class="w-4 h-4" />
                            Edit
                        </button>
                        <button
                            @click="$emit('delete', task.id)"
                            class="flex items-center w-full gap-2 px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                        >
                            <TrashIcon class="w-4 h-4" />
                            Delete
                        </button>
                    </div>
                </div>

                <button @click="closeModal" class="text-gray-600 hover:text-gray-800">
                    <XMarkIcon class="w-6 h-6" />
                </button>
            </div>
        </div>

        <div class="p-4">
            <TaskForm
                :task="task"
                :isEditing="isEditing"
                @saved="closeModal"
                @discard="discardChanges"
            />
        </div>

        <div v-if="task.id" class="p-4 border-t">
            <TaskComments :taskId="task.id" />
        </div>
    </Modal>
</template>
