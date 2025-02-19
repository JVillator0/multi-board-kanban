<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3'

defineProps(["task"])
</script>

<template>
    <Head title="Boards Tasks Show" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Boards Tasks Show
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                Work in progress...
            </div>
        </div>
    </AuthenticatedLayout>
</template>
