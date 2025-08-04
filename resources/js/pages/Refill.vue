<script lang="ts" setup>
    import Input from '@/components/ui/input/Input.vue';
    import {
        Link,
        useForm
    } from '@inertiajs/vue3'

    const form = useForm({
        name: '',
    });

    const handleSubmit = () => {
        form.post(route('refill.store'));
    }
</script>

<template>
    <Link :href="route('home')">Назад</Link>
    <br>
    Заправка
    <br>
    <form @submit.prevent="handleSubmit">
        <Input
            placeholder="Имя"
            variant="bottom-line"
            label="Имя"
            name="name"
            v-model="form.name"
            :required="true"
        />
        <button type="submit">Отправить</button>
    </form>
</template>
