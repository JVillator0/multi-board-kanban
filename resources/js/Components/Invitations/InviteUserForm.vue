<script setup>
import { useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import { ref } from 'vue'
import InputError from '../InputError.vue'
import { useNotificationStore } from '@/Stores/useNotificationStore'

const props = defineProps({ boardId: Number })
const emit = defineEmits(['invitation-sent'])

const form = useForm({
    email: '',
    board_id: props.boardId
})

const errors = ref({})
const notificationStore = useNotificationStore()

async function sendInvitation() {
    errors.value = {}
    try {
        await axios.post(route('api.invitations.store'), { email: form.email, board_id: form.board_id })
        form.reset('email')
        emit('invitation-sent')
        notificationStore.notify('Invitation sent successfully', 'success')
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
    <div class="p-4 bg-white rounded-lg shadow">
        <InputLabel for="email" value="Invite" class="text-lg font-semibold" />
        <div class="mt-2">
            <TextInput v-model="form.email" id="email" type="email" placeholder="Email..." required />
            <InputError :message="errors.email ? errors.email[0] : null" />
        </div>
        <div class="flex justify-end mt-4">
            <PrimaryButton @click="sendInvitation">
                Send Invitation
            </PrimaryButton>
        </div>
    </div>
</template>
