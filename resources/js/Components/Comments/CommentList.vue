<script setup>
import CommentItem from './CommentItem.vue'

defineProps({
    comments: Array,
    loading: Boolean
})

defineEmits(['refresh-comments'])
</script>

<template>
    <div class="mt-3 space-y-2">
        <p v-if="comments.length === 0" class="text-gray-500">No comments yet.</p>

        <div v-if="loading" class="space-y-2">
            <div class="h-24 bg-gray-200 rounded-lg animate-pulse"></div>
            <div class="h-24 bg-gray-200 rounded-lg animate-pulse"></div>
            <div class="h-24 bg-gray-200 rounded-lg animate-pulse"></div>
        </div>
        <ul v-else class="space-y-2 overflow-y-auto max-h-80 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <CommentItem
                v-for="comment in comments"
                :key="comment.id"
                :comment="comment"
                @refresh-comments="$emit('refresh-comments')"
            />
        </ul>
    </div>
</template>
