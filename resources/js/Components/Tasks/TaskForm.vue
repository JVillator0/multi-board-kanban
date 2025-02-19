<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch, ref } from 'vue'
import { CheckIcon } from '@heroicons/vue/24/solid'
import PrimaryButton from '../PrimaryButton.vue'
import SecondaryButton from '../SecondaryButton.vue'
import InputLabel from '../InputLabel.vue'
import InputError from '../InputError.vue'
import TextInput from '../TextInput.vue'
import TextAreaInput from '../TextAreaInput.vue'
import SelectInput from '../SelectInput.vue'
import { useBoardStore } from '@/Stores/boardStore'
import { useNotificationStore } from '@/Stores/useNotificationStore'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    task: {
        type: Object,
        default: () => ({
            id: null,
            title: '',
            description: '',
            priority: 'low',
            status: 'backlog',
        })
    },
    isEditing: Boolean
})

const emit = defineEmits(['saved', 'discard'])

const notificationStore = useNotificationStore()
const boardStore = useBoardStore()

const authUser = usePage().props.auth.user;
const members = ref([
    { id: null, name: 'Unassigned' },
    { id: authUser.id, name: authUser.name },
    ...boardStore.board.members
])
const boardId = ref(boardStore.board.id)

const form = useForm({
    title: props.task.title,
    description: props.task.description,
    priority: props.task.priority,
    status: props.task.status,
    due_date: props.task.due_date,
    assigned_user_id: props.task.assigned_user_id,
    board_id: props.task.board_id || boardId.value
})

const originalTask = ref({ ...props.task })

watch(() => props.task, (newTask) => {
    form.title = newTask.title
    form.description = newTask.description
    form.priority = newTask.priority
    form.status = newTask.status
    form.board_id = newTask.board_id
    originalTask.value = { ...newTask }
})

async function submit() {
    if (props.task.id) {
        form.put(route('tasks.update', props.task.id))
    } else {
        form.post(route('tasks.store'))
    }

    emit('saved')

    form.reset()

    notificationStore.notify('Task saved successfully!', 'success')
}

function discardChanges() {
    form.title = originalTask.value.title
    form.description = originalTask.value.description
    form.priority = originalTask.value.priority
    form.status = originalTask.value.status
    form.board_id = originalTask.value.board_id

    emit('discard')
}
</script>
<template>
    <form @submit.prevent="submit" class="space-y-6">
        <input type="hidden" v-model="boardId" name="board_id" />
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <InputLabel value="Title" for="title" />
                <TextInput v-model="form.title" id="title" required :disabled="!isEditing" />
                <InputError :message="form.errors.title" />
            </div>

            <div>
                <InputLabel value="Priority" for="priority" />
                <SelectInput v-model="form.priority" id="priority" :disabled="!isEditing"
                    :options="[
                        { value: 'low', label: 'Low' },
                        { value: 'medium', label: 'Medium' },
                        { value: 'high', label: 'High' }
                    ]"
                />
                <InputError :message="form.errors.priority" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <InputLabel value="Description" for="description" />
                <TextAreaInput v-model="form.description" id="description" :disabled="!isEditing" />
                <InputError :message="form.errors.description" />
            </div>

            <div>
                <InputLabel value="Status" for="status" />
                <SelectInput v-model="form.status" id="status" :disabled="!isEditing"
                    :options="[
                        { value: 'backlog', label: 'Backlog' },
                        { value: 'todo', label: 'To Do' },
                        { value: 'in_progress', label: 'In Progress' },
                        { value: 'done', label: 'Done' }
                    ]"
                />
                <InputError :message="form.errors.status" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <InputLabel value="Assigned To" for="assigned_user_id" />
                <SelectInput v-model="form.assigned_user_id" id="assigned_user_id" :disabled="!isEditing"
                    :options="members.map(member => ({ value: member.id, label: member.name }))"
                />
                <InputError :message="form.errors.assigned_user_id" />
            </div>

            <div>
                <InputLabel value="Due Date" for="due_date" />
                <TextInput v-model="form.due_date" id="due_date" type="date" :disabled="!isEditing" />
                <InputError :message="form.errors.due_date" />
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <PrimaryButton v-if="isEditing" type="submit">
                <CheckIcon class="w-5 h-5" />
                Save
            </PrimaryButton>

            <SecondaryButton v-if="isEditing && task.id" type="button" @click="discardChanges">
                Discard
            </SecondaryButton>
        </div>
    </form>
</template>
