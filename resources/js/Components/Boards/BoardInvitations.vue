<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import InviteUserForm from '../Invitations/InviteUserForm.vue'
import InvitationsList from '../Invitations/InvitationsList.vue'

const props = defineProps({ board: Object })
const invitations = ref([])
const loading = ref(true)

async function fetchInvitations() {
    loading.value = true
    try {
        const response = await axios.get(route('api.invitations.index', { board_id: props.board.id }))
        invitations.value = response.data
    } catch (error) {
        console.error('Error fetching invitations:', error.response)
    } finally {
        loading.value = false
    }
}

onMounted(fetchInvitations)
</script>

<template>
    <div class="max-w-3xl mx-auto mt-6">
        <InviteUserForm :boardId="board.id" @invitation-sent="fetchInvitations" />

        <div class="mt-6">
            <InvitationsList :invitations="invitations" @refresh="fetchInvitations"/>
        </div>
    </div>
</template>
