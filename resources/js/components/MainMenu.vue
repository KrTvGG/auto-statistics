<script lang="ts" setup>
    import {
        Link
    } from '@inertiajs/vue3'
</script>

<template>
    <div class="container">
        <Link :href="route('home')">Главная</Link>
        <Link :href="route('refill.index')">Заправка</Link>
        <div class="text">
            test
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .text {
        font-size: clampFluid(200);
    }
</style>
