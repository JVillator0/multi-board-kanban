import { defineStore } from 'pinia';

export const useTaskPriorityStore = defineStore('taskPriority', {
    state: () => ({
        priorities: [
            { value: 'low', label: 'Low', color: 'bg-green-500' },
            { value: 'medium', label: 'Medium', color: 'bg-yellow-500' },
            { value: 'high', label: 'High', color: 'bg-red-500' }
        ]
    }),
    getters: {
        getPriorityLabel: (state) => (priority) => {
            return (state.priorities.find(p => p.value === priority)?.label || 'Unknown').toUpperCase();
        },
        getPriorityColor: (state) => (priority) => {
            return state.priorities.find(p => p.value === priority)?.color || 'bg-gray-500';
        }
    }
});
