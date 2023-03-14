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
                        <div v-if="edit" class="col-span-2">
                            <div v-if="item.images.length > 0">
                                <div>Images:</div>
                                <div class="grid grid-cols-3 gap-6">
                                    <div v-for="image in item.images" :key="image.id" class="bg-gray-50 p-4 rounded-md relative">
                                        <button class="absolute right-4 top-4 rounded-full p-2 transition-colors duration-200 hover:bg-red-500 hover:text-white" @click.prevent="deleteImage(image.id)">
                                            <Trash></Trash>
                                        </button>
                                        <div v-html="image.html" class="[&_img]:h-64 [&_img]:w-full [&_img]:object-contain"></div>
                                    </div>
                                </div>
                            </div>
                            <ImageUpload model-type="product" :model-id="item.id"></ImageUpload>
                        </div>
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
    import { Head, useForm, router } from '@inertiajs/vue3';
    import Container from '@/Components/Container.vue';
    import Card from '@/Components/Card/Card.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { watch, computed } from 'vue';
    import InputGroup from '@/Components/InputGroup.vue';
    import SelectGroup from '@/Components/SelectGroup.vue';
    import EditorGroup from '@/Components/EditorGroup.vue';
    import ImageUpload from '@/Components/ImageUpload.vue';
    import Trash from '@/Components/Icons/Trash.vue';

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

    const deleteImage = (imageId) => {
        if(!confirm("Are you shure you want to delete this image?")) return;

        router.post(route('admin.images.destroy', {id: imageId}))
    }

</script>
