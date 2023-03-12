<template>
    <div>
        <InputLabel v-if="label" for="name" :value="label" />

        <TextInput
            id="name"
            :type="type"
            class="mt-1 block w-full"
            :model-value="modelValue"
            @update:model-value="$emit('update:modelValue', $event)"
            v-bind="$attrs"
        />

        <InputError v-if="errorMessage" class="mt-2" :message="errorMessage" />
    </div>
</template>

<script setup>
    import InputError from './InputError.vue';
    import InputLabel from './InputLabel.vue';
    import TextInput from './TextInput.vue';

    defineProps({
        modelValue: {},
        type:{
            type: String,
            default: "text",
        },
        label:{
            type: String,
            default: "",
        },
        errorMessage:{
            type: String,
            default: "",
        }
    })
</script>

<style lang="scss" scoped>

</style>