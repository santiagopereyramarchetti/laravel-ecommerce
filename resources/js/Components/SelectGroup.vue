<script setup>
    import InputError from './InputError.vue';
    import InputLabel from './InputLabel.vue';
    import Select from "@/Components/Select.vue";
defineProps({
    modelValue: {},
    items: Array,
    itemText: {
        type: String,
        default: "name",
    },
    itemValue: {
        type: String,
        default: "id",
    },
    withoutSelect: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: "",
    },
    errorMessage: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <div>
        <InputLabel v-if="label"
               :value="label" />

        <Select class="mt-1"
                :model-value="modelValue"
                @update:model-value="$emit('update:modelValue', $event)"
                :items="items"
                :item-text="itemText"
                :item-value="itemValue"
                :without-select="withoutSelect"
                v-bind="$attrs" />

        <InputError v-if="errorMessage"
                    class="mt-1"
                    :message="errorMessage" />
    </div>
</template>