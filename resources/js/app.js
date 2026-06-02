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

    init()
    {
        this.visibleCount =
            this.$root.querySelectorAll('[data-material-row]').length;
    },

}));

const SUCCESS_TOAST_DURATION = 4;

const UNDO_TOAST_DURATION = 5;

const ERROR_TOAST_DURATION = 5;

const TOAST_REMOVE_DELAY = 350;

const SESSION_TOAST_DELAY = 100;

Alpine.store('toastManager', {

    toasts: [],

    startToastTimer(toastId)
    {

        const timer = setInterval(() => {

            this.toasts = this.toasts.map(item => {

                if (item.id === toastId) {

                    return {

                        ...item,

                        seconds: item.seconds - 1,
                    };
                }

                return item;
            });

            const currentToast =
                this.toasts.find(
                    item => item.id === toastId
                );
            
            if (!currentToast || currentToast.seconds <= 0) {

                clearInterval(timer);

                currentToast.show = false;

                setTimeout(() => {

                    this.toasts =
                        this.toasts.filter(
                            item => item.id !== toastId
                        );

                }, TOAST_REMOVE_DELAY);
            }

        }, 1000);
    },

    addUndoToast(materialId, materialName, action, restore)
    {
        const toastId = Date.now() + Math.random();

        const toast = {

            id: toastId,

            show: false,

            processing: false,

            materialId,

            materialName,

            action,

            type: 'undo',

            title: null,

            message: null,

            seconds: UNDO_TOAST_DURATION,

            restore,
        };

        this.toasts.push(toast);

        requestAnimationFrame(() => {
            toast.show = true;
        });
        

        this.startToastTimer(toastId);
    },

    addErrorToast(message)
    {
        const toastId = Date.now() + Math.random();

        const toast = {

            id: toastId,

            show: false,

            type: 'error',

            title: 'Action Failed',

            message,

            seconds: ERROR_TOAST_DURATION,
        };

        this.toasts.push(toast);

        this.startToastTimer(toastId);

    },

    addSuccessToast(title, message)
    {

        const toastId = Date.now() + Math.random();

        const toast = {

            id: toastId,

            show: false,

            type: 'success-message',

            title,

            message,

            seconds: SUCCESS_TOAST_DURATION,
        };

        this.toasts.push(toast);

        requestAnimationFrame(() => {
            toast.show = true;
        });

        this.startToastTimer(toastId);

    },

    async undoToast(toast)
    {
        if (toast.processing) return;

        toast.processing = true;

        try {

            await toast.restore();

            toast.show = false;

            setTimeout(() => {

                this.toasts =
                    this.toasts.filter(
                        item => item.id !== toast.id
                    );

            }, TOAST_REMOVE_DELAY);

        } catch (error) {

            toast.processing = false;

            this.addErrorToast(
                'Unable to restore material.'
            );
        }
    }


    
});

Alpine.start();

/*
|--------------------------------------------------------------------------
| SESSION TOAST
|--------------------------------------------------------------------------
*/

if (window.appToast) {

    setTimeout(() => {

        if (
            window.appToast.type ===
            'success'
        ) {
            
            Alpine.store('toastManager')
                .addSuccessToast(

                    window.appToast.title,

                    window.appToast.message
                );
        }

    }, SESSION_TOAST_DELAY);
}

/*
|--------------------------------------------------------------------------
| ADJUSTMENT TOAST
|--------------------------------------------------------------------------
*/

const reloadStart =
    sessionStorage.getItem(
        'reload-start'
    );

const adjustmentToast =
    sessionStorage.getItem(
        'adjustment-toast'
    );

if (adjustmentToast) {

    const toast =
        JSON.parse(
            adjustmentToast
        );

    setTimeout(() => {
        
        Alpine.store('toastManager')
            .addSuccessToast(
                toast.title,
                toast.message
            );

        sessionStorage.removeItem(
            'adjustment-toast'
        );

    }, SESSION_TOAST_DELAY);
}

const restockToast =
    sessionStorage.getItem(
        'restock-toast'
    );

if (restockToast) {

    const toast =
        JSON.parse(restockToast);

    setTimeout(() => {
        sessionStorage.removeItem(
            'restock-toast'
        );

        Alpine.store('toastManager')
            .addSuccessToast(
                toast.title,
                toast.message
            );

    }, SESSION_TOAST_DELAY);    
}

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });
});