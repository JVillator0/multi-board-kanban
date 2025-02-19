<script setup>
import InvitationItem from './InvitationItem.vue';

defineProps({ invitations: Array })

defineEmits(['refresh'])
</script>

<template>
    <div>
        <h3 class="font-semibold text-md">
            Invitations <span class="text-sm text-gray-500">({{ invitations.length }})</span>
        </h3>
        <div v-if="invitations.length === 0" class="p-4 mt-2 text-gray-500 bg-white rounded-lg shadow">
            No invitations.
        </div>
        <ul v-else class="mt-2 space-y-2">
            <li v-for="invite in invitations" :key="invite.id" class="">
                <InvitationItem :invitation="invite" @refresh="$emit('refresh')" />
            </li>
        </ul>
    </div>
</template>
