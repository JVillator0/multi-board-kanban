<script setup>
import { computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import TaskList from '@/Components/Tasks/TaskList.vue'
import { useBoardStore } from '@/Stores/boardStore'

const props = defineProps({
    board: Object
})

const boardStore = useBoardStore()
boardStore.setBoard(props.board)

const avatarColors = [
    'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500',
    'bg-pink-500', 'bg-indigo-500', 'bg-teal-500', 'bg-orange-500', 'bg-gray-500'
]

const getColor = (name) => {
    const hash = name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0)
    return avatarColors[hash % avatarColors.length]
}
</script>

<template>
    <Head :title="`Board: ${board.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        {{ board.title }}
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ board.description || 'No description' }}
                    </p>
                    <div v-if="board.members.length > 0" class="mt-3">
                        <span class="text-sm font-semibold text-gray-700">Team Members:</span>
                        <div class="flex flex-wrap gap-2 mt-2 space-x-4">
                            <template v-for="(member, index) in board.members" :key="member.id">
                                <div class="flex items-center space-x-2">
                                    <div
                                        :class="['relative flex items-center justify-center w-8 h-8 text-white rounded-full cursor-pointer', getColor(member.name)]"
                                        :title="member.email"
                                    >
                                        {{ member.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-700" :title="member.email">
                                            {{ member.name }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ member.email }}
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <Link :href="route('boards.index')" class="px-4 py-2 font-bold text-white bg-gray-500 rounded text-nowrap hover:bg-gray-700">
                        Back to Boards
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <TaskList :tasks="board.tasks" :boardId="board.id" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
