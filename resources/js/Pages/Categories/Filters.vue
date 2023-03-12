<template>
    <Card class="mb-4">
        <template #header>
            Filters
        </template>
        <form action="" class="grid grid-cols-4 gap-8">
            <InputGroup type="text" label="Name" v-model="filters.name"></InputGroup>
            <SelectGroup label="Parent Category" v-model="filters.parentId" :items="categories"></SelectGroup>
            <SelectGroup label="Active" v-model="filters.active" :items="[{id: 0, name: 'No'}, {id: 1, name: 'Yes'}]"></SelectGroup>
        </form>
    </Card>
</template>

<script setup>
    import Card from '@/Components/Card/Card.vue'
    import InputGroup from '@/Components/InputGroup.vue';
    import SelectGroup from '@/Components/SelectGroup.vue';
    
    
    import { ref, watch } from 'vue'

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

</script>

<style lang="scss" scoped>

</style>