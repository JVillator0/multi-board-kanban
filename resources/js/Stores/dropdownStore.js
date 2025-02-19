import { defineStore } from 'pinia'

export const useDropdownStore = defineStore('dropdown', {
    state: () => ({
        openDropdown: null
    }),
    actions: {
        setDropdown(id) {
            this.openDropdown = this.openDropdown === id ? null : id;
        },
        closeDropdown() {
            this.openDropdown = null;
        }
    }
});
