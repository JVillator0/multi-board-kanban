<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { ArrowPathIcon, XCircleIcon } from '@heroicons/vue/24/solid'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import ConfirmModal from '@/Components/UI/ConfirmModal.vue'
import { useNotificationStore } from '@/Stores/useNotificationStore'

const props = defineProps({ invitation: Object })
const emit = defineEmits(['refresh'])

const processing = ref(false)
const showRevokeModal = ref(false)
const invitationToRevoke = ref(null)
const notificationStore = useNotificationStore()

async function resendInvitation() {
    processing.value = true
    try {
        await axios.post(route('api.invitations.resend', { invitation: props.invitation.id }))
        emit('refresh')
        notificationStore.notify('Invitation resent successfully', 'success')
    } catch (error) {
        console.error('Error resending invitation:', error)
    } finally {
        processing.value = false
    }
}

function confirmRevoke(invitationId) {
    invitationToRevoke.value = invitationId
    showRevokeModal.value = true
}

async function revokeInvitation() {
    if (!invitationToRevoke.value) return

    processing.value = true
    try {
        await axios.post(route('api.invitations.revoke', { invitation: invitationToRevoke.value }))
        emit('refresh')
        notificationStore.notify('Invitation revoked successfully', 'success')
    } catch (error) {
        console.error('Error revoking invitation:', error)
    } finally {
        processing.value = false
        showRevokeModal.value = false
        invitationToRevoke.value = null
    }
}
</script>

<template>
    <li class="flex items-center justify-between p-4 bg-white rounded-lg shadow">
        <div>
            <p class="text-gray-800"><strong>Email:</strong> {{ invitation.email }}</p>
            <p class="flex items-center gap-1 text-gray-700">
                <strong>Status:</strong>
                <span class="px-2 py-1 text-sm font-semibold rounded-md"
                    :class="{
                        'bg-yellow-100 text-yellow-600': invitation.status === 'pending',
                        'bg-red-100 text-red-600': invitation.status === 'declined',
                        'bg-green-100 text-green-600': invitation.status === 'accepted',
                        'bg-gray-200 text-gray-700': invitation.status === 'revoked'
                    }">
                    {{ invitation.status }}
                </span>
            </p>
        </div>

        <div class="flex gap-2">
            <PrimaryButton v-if="['pending', 'declined'].includes(invitation.status)"
                @click="resendInvitation" :disabled="processing">
                <ArrowPathIcon class="w-5 h-5 mr-1" />
                Resend
            </PrimaryButton>

            <DangerButton v-if="['pending', 'accepted'].includes(invitation.status)" @click="confirmRevoke(invitation.id)" :disabled="processing">
                <XCircleIcon class="w-5 h-5 mr-1" />
                Revoke
            </DangerButton>
        </div>
    </li>

    <ConfirmModal
        :show="showRevokeModal"
        title="Revoke Invitation"
        message="Are you sure you want to revoke this invitation? This action cannot be undone."
        @confirm="revokeInvitation"
        @close="showRevokeModal = false"
    />
</template>
