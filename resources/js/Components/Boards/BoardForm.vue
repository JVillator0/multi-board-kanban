<script setup>
import { router, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon, CheckIcon } from '@heroicons/vue/24/solid'
import { computed } from 'vue'
import PrimaryButton from '../PrimaryButton.vue'
import SecondaryButton from '../SecondaryButton.vue'
import InputLabel from '../InputLabel.vue'
import TextInput from '../TextInput.vue'
import InputError from '../InputError.vue'
import TextAreaInput from '../TextAreaInput.vue'
import { useNotificationStore } from '@/Stores/useNotificationStore'

const props = defineProps({
    board: {
        type: Object,
        default: () => ({ title: '', description: '' })
    }
})

const notificationStore = useNotificationStore()

const form = useForm({
    title: props.board.title,
    description: props.board.description
})

const isEditing = computed(() => !!props.board.id)

function submit() {
    if (isEditing.value) {
        form.put(route('boards.update', props.board.id))
    } else {
        form.post(route('boards.store'))
    }
    notificationStore.notify('Board saved successfully', 'success')
}

function cancel() {
    return router.visit(route('boards.index'))
}
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col w-full mx-auto space-y-4 xl:w-1/2">
        <div>
            <InputLabel value="Title" for="title" />
            <TextInput v-model="form.title" id="title" required />
            <InputError :message="form.errors.title" />
        </div>

        <div>
            <InputLabel value="Description" for="description" />
            <TextAreaInput v-model="form.description" id="description" />
            <InputError :message="form.errors.description" />
        </div>

        <div class="flex justify-between">
            <PrimaryButton type="submit">
                <CheckIcon class="w-5 h-5 mr-2" />
                {{ isEditing ? 'Update' : 'Create' }}
            </PrimaryButton>

            <SecondaryButton @click="cancel" type="button">
                <ArrowLeftIcon class="w-5 h-5 mr-2" />
                Cancel
            </SecondaryButton>
        </div>
    </form>
</template>
