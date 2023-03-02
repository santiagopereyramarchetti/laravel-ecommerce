import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export default (params) => {

    const { routeResourceName } = params

    const deleteModal = ref(false)
    const itemToDelete = ref({})
    const isDeleting = ref(false)

    const showDeleteModal  = (item) => {
        deleteModal.value = true
        itemToDelete.value = item
    }

    const handleDeleteItem = () => {
        router.delete(route(`admin.${routeResourceName}.destroy`, {id: itemToDelete.value.id}), {
                preserveScroll: true,
                preserveState: false,
                onBefore: () => {
                    isDeleting.value = true;
                },
                onSuccess: () => {
                    deleteModal.value = false;
                    itemToDelete.value = {};
                },
                onFinish: () => {
                    isDeleting.value = false;
                },
            }
        )
    }

    return {
        deleteModal,
        itemToDelete,
        isDeleting,
        showDeleteModal,
        handleDeleteItem
    }

}