<template>
    <Card class="mb-4">
        <template #header>
            Filters
        </template>
        <form action="" class="grid grid-cols-4 gap-8">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="name"
                    class="mt-1 block w-full"
                    v-model="filters.name"
                    autofocus
                    autocomplete="name"
                />
            </div>
        </form>
    </Card>
</template>

<script setup>
    import Card from '@/Components/Card/Card.vue'
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import { ref, watch } from 'vue'

    const props = defineProps({
        modelValue: {
            type: Object,
            default: {}
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