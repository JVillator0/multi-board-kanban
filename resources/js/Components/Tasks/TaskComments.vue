<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import CommentForm from '../Comments/CommentForm.vue'
import CommentList from '../Comments/CommentList.vue'

const props = defineProps({ taskId: Number })

const comments = ref([])
const loading = ref(true)

async function fetchComments() {
    loading.value = true
    try {
        const response = await axios.get(route('api.comments.index', { task_id: props.taskId }))
        comments.value = response.data
    } catch (error) {
        console.error('Error fetching comments:', error.response)
    } finally {
        loading.value = false
    }
}

onMounted(fetchComments)
</script>

<template>
    <div>
        <h4 class="mb-2 font-semibold text-gray-700 text-md">
            Comments <span v-if="loading" class="animate-pulse">Loading...</span> <span v-else>({{ comments.length }})</span>
        </h4>

        <CommentForm :taskId="taskId" @refresh-comments="fetchComments" />

        <CommentList :comments="comments" :loading="loading" @refresh-comments="fetchComments" />
    </div>
</template>
