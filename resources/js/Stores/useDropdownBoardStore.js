import { defineStore } from 'pinia'

export const useDropdownBoardStore = defineStore('dropdownBoard', {
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
