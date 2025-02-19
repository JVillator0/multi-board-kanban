<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '../SecondaryButton.vue';
import DangerButton from '../DangerButton.vue';

const props = defineProps({
    show: Boolean,
    title: String,
    message: String,
});

const emit = defineEmits(['confirm', 'close']);
</script>

<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800">{{ title }}</h3>
            <p class="mt-2 text-sm text-gray-600">{{ message }}</p>

            <div class="flex justify-end gap-3 mt-4">
                <SecondaryButton @click="$emit('close')">
                    Cancel
                </SecondaryButton>
                <DangerButton @click="$emit('confirm')">
                    Confirm
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
