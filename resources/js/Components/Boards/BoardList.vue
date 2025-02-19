<script setup>
import BoardCard from './BoardCard'

defineProps({
    boards: Array
})
</script>

<template>
    <div class="grid grid-cols-3 gap-4">
        <BoardCard v-for="board in boards" :key="board.id" :board="board" />
    </div>
</template>
