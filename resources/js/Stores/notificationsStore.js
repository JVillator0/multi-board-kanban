import { defineStore } from 'pinia';

export const useNotificationsStore = defineStore('notifications', {
    state: () => ({
        notifications: []
    }),

    actions: {
        addNotification(notification) {
            this.notifications.unshift(notification);
        },
        removeNotification(notificationId) {
            this.notifications = this.notifications.filter(n => n.id !== notificationId);
        },
        setNotifications(notifications) {
            this.notifications = notifications;
        }
    }
});
