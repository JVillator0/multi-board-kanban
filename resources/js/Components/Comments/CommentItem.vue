<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/solid'
import ConfirmModal from '@/Components/UI/ConfirmModal.vue'
import PrimaryButton from '../PrimaryButton.vue'
import SecondaryButton from '../SecondaryButton.vue'
import { useNotificationStore } from '@/Stores/useNotificationStore'

const props = defineProps({ comment: Object })
const emit = defineEmits(['refresh-comments'])

const isEditing = ref(false)
const showDeleteModal = ref(false)
const content = ref(props.comment.content)
const error = ref(null)
const notificationStore = useNotificationStore()

async function saveComment() {
    try {
        await axios.put(route('api.comments.update', props.comment.id), { content: content.value })
        isEditing.value = false
        emit('refresh-comments')
        notificationStore.notify('Comment updated successfully', 'success')
    } catch (err) {
        error.value = err.response?.data?.errors?.content?.[0] || 'Failed to update comment'
    }
}

function discardChanges() {
    content.value = props.comment.content
    isEditing.value = false
}

async function confirmDelete() {
    try {
        await axios.delete(route('api.comments.destroy', props.comment.id))
        showDeleteModal.value = false
        emit('refresh-comments')
        notificationStore.notify('Comment deleted successfully', 'success')
    } catch (error) {
        console.error('Error deleting comment:', error.response)
    }
}
</script>

<template>
    <li class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-900">
                    {{ comment.user.name }}
                </p>
                <p class="text-xs text-gray-500" :title="comment.created_at.datetime">
                    {{ comment.created_at.ago }} <span v-if="comment.edited">(Edited)</span>
                </p>
            </div>

            <div v-if="$page.props.auth.user.id === comment.user.id" class="flex items-center gap-2">
                <button v-if="!isEditing" @click="isEditing = true" class="text-blue-500 hover:text-blue-700">
                    <PencilIcon class="w-4 h-4" />
                </button>
                <button v-if="!isEditing" @click="showDeleteModal = true" class="text-red-500 hover:text-red-700">
                    <TrashIcon class="w-4 h-4" />
                </button>
            </div>
        </div>

        <div v-if="!isEditing" class="mt-2">
            <p class="text-sm text-gray-700">{{ comment.content }}</p>
        </div>

        <div v-else class="flex flex-col gap-2 mt-2">
            <input v-model="content" type="text" class="w-full p-2 text-sm border rounded-md">
            <div class="flex gap-2">
                <PrimaryButton @click="saveComment" class="px-3 py-1 text-xs">
                    Save
                </PrimaryButton>
                <SecondaryButton @click="discardChanges" class="px-3 py-1 text-xs">
                    Cancel
                </SecondaryButton>
            </div>
        </div>

        <p v-if="error" class="mt-2 text-sm text-red-500">{{ error }}</p>

        <ConfirmModal
            :show="showDeleteModal"
            title="Delete Comment"
            message="Are you sure you want to delete this comment? This action cannot be undone."
            @confirm="confirmDelete"
            @close="showDeleteModal = false"
        />
    </li>
</template>
