<template>
    <div class="flex items-center space-x-2">
        <button v-if="showEdit">
            <Link :href="editLink">
                <Edit class="w-4 h-4"></Edit>
            </Link>
        </button>
        <button v-if="showDelete">
            <Link :href="deleteLink" @click="$emit('deleteClicked', $event)">
                <Trash class="w-4 h-4"></Trash>
            </Link>
        </button>
    </div>
</template>

<script setup>
    import Edit from '../Icons/Edit.vue';
    import Trash from '../Icons/Trash.vue'
    import { Link } from '@inertiajs/vue3';
    defineProps({
        editLink: String,
        showEdit: {
            type: Boolean,
            default: true
        },
        deleteLink: String,
        showDelete: {
            type: Boolean,
            default: true
        }
    })
</script>

<style lang="scss" scoped>

</style>