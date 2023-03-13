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
                        <InputGroup label="Cost price" type="number" v-model="form.costPrice" :error-message="form.errors.costPrice" required></InputGroup>
                        <InputGroup label="Selling price" type="number" v-model="form.price" :error-message="form.errors.price" required></InputGroup>
                        <div class="col-span-2">
                            <EditorGroup label="Description" v-model="form.description" :error-message="form.errors.description"></EditorGroup>
                        </div>
                        <div class="col-span-2">
                            <div class="grid grid-cols-2 gap-6">
                                <SelectGroup label="Category" v-model="form.categoryId" :items="categories" :error-message="form.errors.categoryId"></SelectGroup>
                                <SelectGroup v-if="subCategories.length > 0" label="Sub Category" v-model="form.subCategoryId" :items="subCategories" :error-message="form.errors.subCategoryId"></SelectGroup>
                            </div>
                        </div>
                        <SelectGroup label="Active" v-model="form.active" :items="[{id: 0, name: 'No'}, {id: 1, name: 'Yes'}]" :error-message="form.errors.active" requried></SelectGroup>
                        <SelectGroup label="Featured" v-model="form.featured" :items="[{id: 0, name: 'No'}, {id: 1, name: 'Yes'}]" :error-message="form.errors.featured" requried></SelectGroup>
                        <SelectGroup label="Show on Slider" v-model="form.showOnSlider" :items="[{id: 0, name: 'No'}, {id: 1, name: 'Yes'}]" :error-message="form.errors.showOnSlider" requried></SelectGroup>
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
    import { watch, computed } from 'vue';
    import InputGroup from '@/Components/InputGroup.vue';
    import SelectGroup from '@/Components/SelectGroup.vue';
    import EditorGroup from '@/Components/EditorGroup.vue';

    import replace from 'lodash/replace'
    import kebabCase from 'lodash/kebabCase'
    
    const props = defineProps({
        edit: {
            type: Boolean,
            default: false
        },
        title: String,
        item: {
            type: Object,
            default: {}
        },
        routeResourceName:{
            type: String,
            required: true
        },
        categories:{
           type: Array,
           required: true 
        },
        subCategories:{
            type: Array,
        }
    })

    const form = useForm({
        name: props.item.name ?? "",
        slug: props.item.slug ?? "",
        description: props.item.description ?? "",
        costPrice: props.item.cost_price ?? "",
        price: props.item.price ?? "",
        active: props.item.active ?? false,
        featured: props.item.featured ?? false,
        showOnSlider: props.item.show_on_slider ?? false,
        categoryId: props.item.category_id ?? "",
        subCategoryId: props.item.sub_category_id ?? "",
    })

    const subCategories = computed( () => {
        if(!form.categoryId) return []

        let category = props.categories.find((c) => c.id == form.categoryId)
        
        if(!category) return []

        return category.children || []
    })

    watch(
        () => form.name,
        (name) => {
            if(!props.edit){
                form.slug = kebabCase(replace(name, /&./, "and"))
            }
        }
    )

    watch(
        () => form.categoryId,
        () => {
            form.subCategoryId = ""
        }
    )

    const submit = () => {
        props.edit ? form.put(route(`admin.${props.routeResourceName}.update`, {id: props.item.id})) : form.post(route(`admin.${props.routeResourceName}.store`))
    }

</script>
