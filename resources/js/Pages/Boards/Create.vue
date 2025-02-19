<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import BoardForm from '@/Components/Boards/BoardForm.vue'
</script>

<template>
    <Head title="Create Board" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create Board
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <BoardForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
