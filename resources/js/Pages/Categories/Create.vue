<template>
    <Head :title="props.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{props.title}}</h2>
        </template>

        <Container>
            <Card>
                <form action="" @submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-6">
                        <InputGroup label="Name" v-model="form.name" :error-message="form.errors.name" required></InputGroup>
                        <InputGroup label="Slug" v-model="form.slug" :error-message="form.errors.slug" required></InputGroup>
                        <SelectGroup label="Parent Category" v-model="form.parentId" :items="rootCategories" :error-message="form.errors.parentId"></SelectGroup>
                        <SelectGroup label="Active" v-model="form.active" :items="[{id: 0, name: 'No'}, {id: 1, name: 'Yes'}]" :error-message="form.errors.active" requried></SelectGroup>
                    </div>

                    <PrimaryButton :disabled="form.processing" class="mt-3">
                        {{ form.processing ? 'Saving...' : 'Save' }}
                    </PrimaryButton>
                </form>
            </Card>
        </Container>
    </AuthenticatedLayout>
</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm } from '@inertiajs/vue3';
    import Container from '@/Components/Container.vue';
    import Card from '@/Components/Card/Card.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { onMounted, watch } from 'vue';
    import InputGroup from '@/Components/InputGroup.vue';
    import SelectGroup from '@/Components/SelectGroup.vue';

    import replace from 'lodash/replace'
    import kebabCase from 'lodash/kebabCase'
    
    const props = defineProps({
        edit: {
            type: Boolean,
            default: false
        },
        title: String,
        item: Object,
        routeResourceName:{
            type: String,
            required: true
        },
        rootCategories:{
           type: Array,
           required: true 
        }
    })

    const form = useForm({
        name: '',
        slug: '',
        active: true,
        parentId: '',
    })

    watch(
        () => form.name,
        (name) => {
            if(!props.edit){
                form.slug = kebabCase(replace(name, /&./, "and"))
            }
        }
    )

    const submit = () => {
        props.edit ? form.put(route(`admin.${props.routeResourceName}.update`, {id: props.item.id})) : form.post(route(`admin.${props.routeResourceName}.store`))
    }

    onMounted(() => {
        if (props.edit){
            form.name = props.item.name
            form.slug = props.item.slug
            form.active = props.item.active
            form.parentId = props.item.parent_id
        }
    })

</script>
