<template>
    <Head title="Role" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles</h2>
        </template>

        <Container>
            <PrimaryButton :href="route('admin.roles.create')">Create</PrimaryButton>
            <Card class="mt-2">
                <Table :headers="headers" :items="roles.data">
                    <template v-slot="{ item }">
                        <Td> {{ item.name }} </Td>
                        <Td> {{ item.created_at_formated }} </Td>
                        <Td> <Actions :editLink="route('admin.roles.edit', {id: item.id})" @deleteClicked="showDeleteModal(item)"></Actions></Td>        
                    </template>    
                </Table>
            </Card>
        </Container>
    </AuthenticatedLayout>

    <Modal v-model="deleteModal" size="sm" :title="`Delete ${itemToDelete.name}`">
        Are you sure want to delete this item?
        <template #footer>
            <PrimaryButton>Delete</PrimaryButton>
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
    import { ref } from 'vue';
    import Modal from '@/Components/Admin/Modal.vue';

    defineProps({
        roles: Object,
        headers: Array
    })

    const deleteModal = ref(false)
    const itemToDelete = ref({})

    const showDeleteModal  = (item) => {
        deleteModal.value = true
        itemToDelete.value = item
    }
</script>
