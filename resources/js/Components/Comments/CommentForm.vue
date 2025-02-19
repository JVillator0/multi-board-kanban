<script setup>
import { ref } from 'vue'
import axios from 'axios'
import PrimaryButton from '../PrimaryButton.vue'
import InputLabel from '../InputLabel.vue'
import TextInput from '../TextInput.vue'
import InputError from '../InputError.vue'
import { useNotificationStore } from '@/Stores/useNotificationStore'

const props = defineProps({ taskId: Number })
const emit = defineEmits(['refresh-comments'])

const content = ref('')
const errors = ref({})
const notificationStore = useNotificationStore()

async function submitComment() {
    errors.value = {}
    try {
        await axios.post(route('api.comments.store'), { content: content.value, task_id: props.taskId })
        content.value = ''
        emit('refresh-comments')
        notificationStore.notify('Comment posted successfully', 'success')
    } catch (err) {
        if (err.response?.status === 422) {
            errors.value = err.response.data.errors
        } else {
            console.error('Error updating comment:', err.response)
        }
    }
}
</script>

<template>
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2">
            <TextInput v-model="content" placeholder="Write a comment..." class="w-full"/>

            <PrimaryButton @click="submitComment" >
                Post
            </PrimaryButton>
        </div>
        <InputError :message="errors.content ? errors.content[0] : null" />
    </div>
</template>
