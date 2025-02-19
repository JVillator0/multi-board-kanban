<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import BoardForm from '@/Components/Boards/BoardForm.vue'

defineProps({
    board: Object
})
</script>

<template>
    <Head title="Edit Board" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Board
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <BoardForm :board="board"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
