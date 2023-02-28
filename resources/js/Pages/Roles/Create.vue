<template>
    <Head :title="props.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{props.title}}</h2>
        </template>

        <Container>
            <Card>
                <form action="" @submit.prevent="submit">

                    <div>
                        <InputLabel for="name" value="Name" />

                        <TextInput
                            id="name"
                            type="name"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
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
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import { onMounted } from 'vue';

    const props = defineProps({
        edit: {
            type: Boolean,
            default: false
        },
        title: String,
        role: Object
    })

    const form = useForm({
        name: '',
    })

    const submit = () => {
        props.edit ? form.put(route('admin.roles.update', {id: props.role.id})) : form.post(route('admin.roles.store'))
    }

    onMounted(() => {
        if (props.edit){
            form.name = props.role.name
        }
    })

</script>
