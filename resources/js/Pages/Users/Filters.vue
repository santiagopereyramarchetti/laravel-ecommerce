<template>
    <Card class="mb-4">
        <template #header>
            Filters
        </template>
        <form action="" class="grid grid-cols-4 gap-8">
            <InputGroup type="text" label="Name" v-model="filters.name"></InputGroup>
            <InputGroup type="text" label="Email" v-model="filters.email"></InputGroup>
            <SelectGroup label="Role" v-model="filters.roleId" :items="roles"></SelectGroup>
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
        roles: {
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