/**
 * Modal utilities for Alpine.js
 */

export const Modal = {
    open: (id) => {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: id }))
    },

    close: (id) => {
        window.dispatchEvent(new CustomEvent('close-modal', { detail: id }))
    },

    closeAll: () => {
        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'all' }))
    },
}

/**
 * Register a global Alpine.js data function for modals
 */
export function registerAlpineModal() {
    window.Alpine.data('modal', () => ({
        show: false,

        init() {
            this.$watch('show', (value) => {
                if (value) {
                    document.body.classList.add('overflow-hidden')
                } else {
                    document.body.classList.remove('overflow-hidden')
                    this.$dispatch('modal-closed', this.$el.id)
                }
            })
        },

        open() {
            this.show = true
        },

        close() {
            this.show = false
        },

        toggle() {
            this.show = !this.show
        },
    }))
}

/**
 * Initialize the modal system
 */
export function initModals() {
    // Check if Alpine is available
    if (window.Alpine) {
        registerAlpineModal()

        // Add global Alpine utility
        window.Alpine.magic('modal', () => {
            return {
                open: Modal.open,
                close: Modal.close,
                closeAll: Modal.closeAll,
            }
        })
    } else {
        console.error('Alpine.js is required for modals to work')
    }
}

// Auto-initialize when this module is loaded
document.addEventListener('DOMContentLoaded', () => {
    initModals()
})

// Export the modal utilities
export default Modal
