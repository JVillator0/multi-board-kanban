<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    required: true,
});

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
});

const select = ref(null);

onMounted(() => {
    if (select.value.hasAttribute('autofocus')) {
        select.value.focus();
    }
});

defineExpose({ focus: () => select.value.focus() });
</script>

<template>
    <select
        ref="select"
        v-model="model"
        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100"
    >
        <option value="0" default>Select an option</option>
        <option v-for="option in options" :key="option.value" :value="`${option.value}`">
            {{ option.label }}
        </option>
    </select>
</template>
