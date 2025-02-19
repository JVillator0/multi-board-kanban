import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useNotificationStore = defineStore('notification', () => {
    const message = ref(null)
    const type = ref('success') // success | error | info
    const show = ref(false)

    function notify(msg, msgType = 'success') {
        message.value = msg
        type.value = msgType
        show.value = true

        setTimeout(() => {
            show.value = false
        }, 60000) // 3 seconds
    }

    return { message, type, show, notify }
})
