import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

import { createIcons, icons } from 'lucide';

import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.createIcons = createIcons;
window.icons = icons;
window.Swal = Swal;

Alpine.plugin(collapse);

Alpine.data('rawMaterialsTable', () => ({

    visibleCount: 0,

    undoQueue: [],

    init()
    {
        this.visibleCount =
            this.$root.querySelectorAll('[data-material-row]').length;
    },

    addToast(materialId, materialName, action, restore)
    {
        const toastId = Date.now() + Math.random();

        const toast = {

            id: toastId,

            processing: false,

            materialId,

            materialName,

            action,

            seconds: 5,

            restore,
        };

        this.undoQueue.push(toast);

        const timer = setInterval(() => {

            this.undoQueue = this.undoQueue.map(item => {

                if (item.id === toastId) {

                    return {

                        ...item,

                        seconds: item.seconds - 1,
                    };
                }

                return item;
            });

            const currentToast = this.undoQueue.find(
                item => item.id === toastId
            );

            if (!currentToast || currentToast.seconds <= 0) {

                clearInterval(timer);

                this.undoQueue = this.undoQueue.filter(
                    item => item.id !== toastId
                );
            }

        }, 1000);
    },

    async undoToast(toast)
    {
        if (toast.processing) return;

        toast.processing = true;

        await toast.restore();

        this.undoQueue = this.undoQueue
            .filter(item => item.id !== toast.id);
    }
}));

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});