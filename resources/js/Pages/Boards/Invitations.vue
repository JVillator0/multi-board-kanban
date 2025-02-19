<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import BoardInvitations from '@/Components/Boards/BoardInvitations.vue'

defineProps({
    board: Object
})
</script>

<template>
    <Head :title="`Board: ${board.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ board.title }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <BoardInvitations :board="board" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
