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
                        <InputGroup type="email" label="Email" v-model="form.email" :error-message="form.errors.email" required></InputGroup>
                        <InputGroup type="password" label="Password" v-model="form.password" :error-message="form.errors.password" :required="!edit"></InputGroup>
                        <InputGroup type="password" label="Confirm Password" v-model="form.passwordConfirmation" :error-message="form.errors.passwordConfirmation" :required="!edit"></InputGroup>
                        <SelectGroup label="Role" v-model="form.roleId" :items="roles" :error-message="form.errors.roleId" requried></SelectGroup>
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
    import { onMounted } from 'vue';
    import InputGroup from '@/Components/InputGroup.vue';
    import SelectGroup from '@/Components/SelectGroup.vue';
    
    const props = defineProps({
        edit: {
            type: Boolean,
            default: false
        },
        title: String,
        item: Object,
        routeResourceName:{
            type: String,
            required: true
        },
        roles:{
           type: Array,
           required: true 
        }
    })

    const form = useForm({
        name: '',
        email: '',
        password: '',
        passwordConfirmation: '',
        roleId: '',
    })

    const submit = () => {
        props.edit ? form.put(route(`admin.${props.routeResourceName}.update`, {id: props.item.id})) : form.post(route(`admin.${props.routeResourceName}.store`))
    }

    onMounted(() => {
        if (props.edit){
            form.name = props.item.name
            form.email = props.item.email
            form.roleId = props.item.roles[0]?.id
        }
    })

</script>
