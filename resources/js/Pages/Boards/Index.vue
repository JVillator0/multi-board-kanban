<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import BoardCard from '@/Components/Boards/BoardCard.vue';
import PrimaryLink from '@/Components/PrimaryLink.vue';
import draggable from 'vuedraggable';
import axios from 'axios';
import { ref, watch } from 'vue';

const props = defineProps([
    "boards",
    "shared_boards",
]);

const myBoards = ref([...props.boards]);

async function updateBoardOrder() {
    try {
        const reorderedBoards = myBoards.value.map((board, index) => ({
            id: board.id,
            order: index
        }));
        await axios.post(route('api.boards.reorder'), { boards: reorderedBoards });
    } catch (error) {
        console.error("Error updating board order:", error);
    }
}

watch(() => props.boards, (newBoards) => {
    myBoards.value = [...newBoards];
});
</script>

<template>
    <Head title="Boards Index" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-row items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Boards
                </h2>
                <PrimaryLink :href="route('boards.create')" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                    Create Board
                </PrimaryLink>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h3 class="mb-2 text-lg font-semibold leading-tight text-gray-800">
                    My Boards <span class="text-sm font-normal text-gray-500">({{ myBoards.length }})</span>
                </h3>

                <draggable
                    v-model="myBoards"
                    :group="{ name: 'myBoards', pull: true, put: true }"
                    item-key="id"
                    @end="updateBoardOrder"
                    class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                >
                    <template #item="{ element: board }">
                        <div>
                            <BoardCard :board="board" />
                        </div>
                    </template>
                </draggable>

                <h3 class="mt-8 mb-2 text-lg font-semibold leading-tight text-gray-800">
                    Shared Boards <span class="text-sm font-normal text-gray-500">({{ shared_boards.length }})</span>
                </h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    <BoardCard v-for="board in shared_boards" :key="board.id" :board="board" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
