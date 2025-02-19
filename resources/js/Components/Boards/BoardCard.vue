<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { EllipsisVerticalIcon, PencilIcon, TrashIcon, UserPlusIcon } from '@heroicons/vue/24/solid'
import DropdownLink from '../DropdownLink.vue'
import ConfirmModal from '@/Components/UI/ConfirmModal.vue'
import { useDropdownBoardStore } from '@/Stores/useDropdownBoardStore'
import DropdownButton from '../DropdownButton.vue'
import { useNotificationStore } from '@/Stores/useNotificationStore'

const props = defineProps({ board: Object })
const notificationStore = useNotificationStore()
const dropdownBoardStore = useDropdownBoardStore()
const dropdownRef = ref(null)

const showModal = ref(false)
const boardToDelete = ref(null)

const isOpen = computed(() => dropdownBoardStore.openDropdown === props.board.id)

const avatarColors = [
    'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500',
    'bg-pink-500', 'bg-indigo-500', 'bg-teal-500', 'bg-orange-500', 'bg-gray-500'
]

const getColor = (name) => {
    const hash = name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0)
    return avatarColors[hash % avatarColors.length]
}

function toggleMenu(event) {
    event.stopPropagation()
    if (isOpen.value) {
        dropdownBoardStore.closeDropdown()
    } else {
        dropdownBoardStore.setDropdown(props.board.id)
    }
}

function handleClickOutside(event) {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        dropdownBoardStore.closeDropdown()
    }
}

function deleteBoard(boardId) {
    boardToDelete.value = boardId
    dropdownBoardStore.closeDropdown()
    showModal.value = true
}

function confirmDelete() {
    if (boardToDelete.value) {
        router.delete(route('boards.destroy', boardToDelete.value))
    }
    showModal.value = false
    boardToDelete.value = null
    notificationStore.notify('Board deleted successfully', 'success')
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>

<template>
    <div @click="router.visit(route('boards.show', board.id))"
         class="relative p-6 transition-shadow bg-white rounded-lg shadow-md cursor-pointer hover:shadow-lg">
        <div v-if="$page.props.auth.user.id == board.user_id"
             class="absolute top-3 right-3"
             ref="dropdownRef">
            <button @click="toggleMenu"
                    class="p-2 rounded hover:bg-gray-200"
                    data-testid="board-options">
                <EllipsisVerticalIcon class="w-5 h-5 text-gray-500" />
            </button>

            <!-- Dropdown Menu -->
            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <div v-if="isOpen"
                     class="absolute right-0 z-10 w-40 mt-1 bg-white border rounded-lg shadow-lg">
                    <DropdownLink :href="route('boards.edit', board.id)" @click.stop>
                        Edit Board
                    </DropdownLink>
                    <DropdownLink :href="route('boards.invitations.index', board.id)" @click.stop>
                        Manage Members
                    </DropdownLink>
                    <DropdownButton @click.stop="deleteBoard(board.id)">
                        Delete Board
                    </DropdownButton>
                </div>
            </transition>
        </div>

        <!-- Resto del contenido... -->
        <h3 class="text-lg font-semibold text-gray-800">{{ board.title }}</h3>
        <p class="mt-2 text-sm text-gray-600">{{ board.description || 'No description' }}</p>

        <div class="mt-4">
            <p class="text-sm font-semibold text-gray-700">
                Members:
            </p>
            <div class="flex items-center mt-2">
                <div class="flex -space-x-2">
                    <template v-for="(member, index) in board.members.slice(0, 3)" :key="member.id">
                        <div :class="['relative flex items-center justify-center w-8 h-8 text-white rounded-full', getColor(member.name)]" :title="member.name">
                            {{ member.name.charAt(0).toUpperCase() }}
                        </div>
                    </template>
                </div>

                <div v-if="board.members.length > 3" class="relative ml-2 group">
                    <span class="flex items-center justify-center w-8 h-8 text-sm font-semibold text-gray-700 bg-gray-300 rounded-full cursor-pointer">
                        +{{ board.members.length - 3 }}
                    </span>
                    <div class="absolute left-0 z-10 hidden p-2 mt-1 text-sm text-gray-700 bg-white border rounded-lg shadow-lg group-hover:block">
                        <p v-for="member in board.members.slice(3)" :key="member.id" class="px-2 py-1">{{ member.name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ConfirmModal
        :show="showModal"
        title="Delete Board"
        message="Are you sure you want to delete this board?"
        @confirm="confirmDelete"
        @close="showModal = false"
    />
</template>
