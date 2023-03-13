<template>
    <Head :title="props.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles</h2>
        </template>

        <Container>
            <AddNew :show="isFilled">
                <PrimaryButton v-if="can.create" :href="route(`admin.${props.routeResourceName}.create`)">Create</PrimaryButton>
                <template #filters>
                    <Filters class="mt-4" v-model="filters" :categories="categories"/>
                </template>
            </AddNew>

            <Card class="mt-2" :is-loading="isLoading">
                <Table :headers="headers" :items="items.data">
                    <template v-slot="{ item }">
                        <Td> 
                            <div class="whitespace-pre-wrap w-64">
                                {{ item.name }}
                            </div>
                        </Td>
                        <Td> {{ item.cost_price }} </Td>
                        <Td> {{ item.price }} </Td>
                        <Td>
                            <YesNoLavel :active="item.show_on_slider"></YesNoLavel>
                        </Td>
                        <Td>
                            <YesNoLavel :active="item.featured"></YesNoLavel>
                        </Td>
                        <Td>
                            <YesNoLavel :active="item.active"></YesNoLavel>
                        </Td>
                        <Td> {{ item.created_at_formated }} </Td>
                        <Td> <Actions :show-edit="item.can.edit" :editLink="route(`admin.${props.routeResourceName}.edit`, {id: item.id})" :show-delete="item.can.delete" @deleteClicked="showDeleteModal(item)"></Actions></Td>        
                    </template>    
                </Table>
            </Card>
        </Container>
    </AuthenticatedLayout>

    <Modal v-model="deleteModal"
           :title="`Delete ${itemToDelete.name}`">
        Are you sure you want to delete this item?

        <template #footer>
            <PrimaryButton @click="handleDeleteItem"
                    :disabled="isDeleting">
                <span v-if="isDeleting">Deleting</span>
                <span v-else>Delete</span>
            </PrimaryButton>
        </template>
    </Modal>
</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import Container from '@/Components/Container.vue';
    import Card from '@/Components/Card/Card.vue'
    import Table from '@/Components/Table/Table.vue'
    import Td from '@/Components/Table/Td.vue'
    import Actions from '@/Components/Table/Actions.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import Modal from '@/Components/Admin/Modal.vue';
    import AddNew from '@/Components/AddNew.vue';
    import Filters from './Filters.vue';
    import YesNoLavel from '@/Components/YesNoLavel.vue';

    import useDeleteItem from '@/Composables/useDeleteItem'
    import useFilters from '@/Composables/useFilters'

    const props = defineProps({
        items: Object,
        headers: Array,
        filters: {
            type: Object,
            default: {}
        },
        routeResourceName:{
            type: String,
            required: true,
        },
        title:{
            type: String,
            required: true,
        },
        can: {
            type: Object
        },
        categories:{
            type: Array,
        }
    })

    const {
        deleteModal,
        itemToDelete,
        isDeleting,
        showDeleteModal,
        handleDeleteItem
    } = useDeleteItem({
        routeResourceName: props.routeResourceName
    })

    const {
        filters,
        isFilled,
        isLoading
    } = useFilters({
        filters: props.filters,
        routeResourceName: props.routeResourceName
    })
</script>
