import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useBoardStore = defineStore('board', () => {
    const board = ref(null)
    const tasks = ref([])
    const members = ref([])

    function setBoard(data) {
        board.value = data
        members.value = data.members || []
        tasks.value = data.tasks || []
    }

    function clearBoard() {
        board.value = null
        members.value = []
        tasks.value = []
    }

    return { board, tasks, members, setBoard, clearBoard }
})
