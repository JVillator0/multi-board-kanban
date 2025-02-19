import { defineStore } from 'pinia';

export const useTaskStatusStore = defineStore('taskStatus', {
    state: () => ({
        statuses: [
            { value: 'backlog', label: 'Backlog', color: 'border-gray-500 bg-gray-200 text-gray-700' },
            { value: 'todo', label: 'To Do', color: 'border-blue-500 bg-blue-100 text-blue-700' },
            { value: 'in_progress', label: 'In Progress', color: 'border-yellow-500 bg-yellow-100 text-yellow-700' },
            { value: 'done', label: 'Done', color: 'border-green-500 bg-green-100 text-green-700' }
        ]
    }),
    getters: {
        getStatusLabel: (state) => (status) => {
            return state.statuses.find(s => s.value === status)?.label || 'Unknown';
        },
        getStatusColor: (state) => (status) => {
            return state.statuses.find(s => s.value === status)?.color || 'border-gray-500 bg-gray-200 text-gray-700';
        },
        getValues: (state) => {
            return state.statuses.map(s => s.value);
        },
        getLabels: (state) => {
            return state.statuses.map(s => s.label);
        },
    }
});
