<template>
    <Card class="mb-4">
        <template #header>
            Filters
        </template>
        <form action="" class="grid grid-cols-4 gap-8">
            <InputGroup type="text" label="Name" v-model="filters.name"></InputGroup>
            <SelectGroup label="Category" v-model="filters.categoryId" :items="categories"></SelectGroup>
            <SelectGroup v-if="subCategories.length > 0" label="Sub Category" v-model="filters.subCategoryId" :items="subCategories"></SelectGroup>
            <SelectGroup label="Active" v-model="filters.active" :items="[{id: 0, name: 'No'}, {id: 1, name: 'Yes'}]"></SelectGroup>
            <SelectGroup label="Featured" v-model="filters.featured" :items="[{id: 0, name: 'No'}, {id: 1, name: 'Yes'}]"></SelectGroup>
            <SelectGroup label="Show on Slider" v-model="filters.showOnSlider" :items="[{id: 0, name: 'No'}, {id: 1, name: 'Yes'}]"></SelectGroup>
        </form>
    </Card>
</template>

<script setup>
    import Card from '@/Components/Card/Card.vue'
    import InputGroup from '@/Components/InputGroup.vue';
    import SelectGroup from '@/Components/SelectGroup.vue';
    
    
    import { ref, watch, computed } from 'vue'

    const props = defineProps({
        modelValue: {
            type: Object,
            default: {}
        },
        categories: {
            type: Array
        }
    })

    const emits = defineEmits([
        "update:modelValue"
    ])

    const filters = ref({...props.modelValue})

    watch(filters, () => {
        emits("update:modelValue", filters.value)
    },{
        deep: true,
    })

    const subCategories = computed( () => {
        if(!filters.value.categoryId){
            return [];
        }

        const category = props.categories.find(c => c.id == filters.value.categoryId)

        if(!category) return [];

        return category.children;
    })

    watch(() => filters.value.categoryId, () => {
        filters.value.subCategoryId = ""
    })

</script>

<style lang="scss" scoped>

</style>