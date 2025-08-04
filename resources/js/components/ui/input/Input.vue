<script setup lang="ts">
    import {
        onMounted,
        ref,
        type HTMLAttributes
    } from 'vue'

    const {
        type = 'text',
        placeholder,
        variant = 'default',
        isError = false,
        label,
        name,
        required = false
    } = defineProps<{
        type?: 'text' | 'tel' | 'number' | 'mail',
        placeholder?: string,
        readonly?: boolean,
        variant?: 'default' | 'bottom-line',
        label?: string,
        isError?: boolean,
        name: string,
        required?: boolean
    }>()

    const emits = defineEmits()

    const input = ref<HTMLInputElement>()

    const keydown = (event: KeyboardEvent) => {
        if (type == 'number' && ['e', '+', '-'].includes(event.key)) event.preventDefault()
    }

    const MASK = '+7(###)###-##-##'

    const FIRST_SYMBOLS = [
        '7',
        '8',
    ]

    const inputChar = (key: string, inEnd: boolean) => {
        let cursor = ((input.value as HTMLInputElement).selectionStart as number)
        if (inEnd)
            cursor = (modelValue.value as string).length

        if (FIRST_SYMBOLS.includes(key) && !modelValue.value){
            modelValue.value = MASK.slice(0, MASK.indexOf('#'))
            return
        }

        if (isNaN(parseInt(key))) return
        let endAddedIndex = MASK.slice(cursor).indexOf('#') + cursor + 1
        let added = MASK.slice(cursor, endAddedIndex).replace('#', key)

        modelValue.value = (modelValue.value as string).slice(0, cursor) + added + (modelValue.value as string).slice(added.length + cursor);
    }

    onMounted(() => {
        if (input.value && type == 'tel'){
            input.value.addEventListener('click', () => {
                let maxLen = (input.value as HTMLInputElement).value.length;
                (input.value as HTMLInputElement).setSelectionRange(maxLen, maxLen)
            })
            input.value.addEventListener('paste', (event:any) => {
                event.preventDefault()
                let data = event.clipboardData.getData('text')
                data.replace('+7', '7')
                for (let i = 0; i < data.length; i++) {
                    inputChar(event.clipboardData.getData('text')[i], true)
                }
            })
            input.value.addEventListener('keydown', (event:KeyboardEvent) => {
                if (event.key.slice(0, 5) == 'Arrow') event.preventDefault()
            });
            input.value.addEventListener('beforeinput', (event:InputEvent) => {
                event.preventDefault()
                let key = event.data
                if (key) inputChar(key, false)

                let cursor = ((input.value as HTMLInputElement).selectionStart as number)
                if (event.inputType == 'deleteContentBackward'){

                    let removeIndex = MASK.slice(0, cursor).lastIndexOf('#')
                    if (removeIndex == -1)
                        modelValue.value = ''
                    else
                        modelValue.value = (modelValue.value as string).slice(0, removeIndex) + (modelValue.value as string).slice(cursor);
                }
            })
        }
    })

    const modelValue = defineModel<string|number>()
</script>

<template>
    <input
        @keydown="keydown"
        class="ui-input__value"
        :type="type"
        v-model="modelValue"
        :placeholder="placeholder"
        ref="input"
        :readonly="readonly"
        :name="name"
        :required="required"
    >
    <!-- <label
        v-if="variant == 'bottom-line'"
        class="ui-input ui-input--bottom-line"
        :class="{
            'ui-input--not-valid': isError,
        }"
    >
        <span v-if="label" class="ui-input__label">{{ label }}</span>
        <input
            @keydown="keydown"
            class="ui-input__value"
            :type="type"
            v-model="modelValue"
            :placeholder="placeholder"
            ref="input"
            :readonly="readonly"
        >
    </label>
    <label
        v-else
        class="ui-input ui-input--default"
        :class="{
            'ui-input--not-valid': isError,
        }"
    >
        <input
            @keydown="keydown"
            class="ui-input__value"
            :type="type"
            v-model="modelValue"
            placeholder=" "
            ref="input"
            :readonly="readonly"
        >
        <span v-if="placeholder" class="ui-input__placeholder">{{ placeholder }}</span>
    </label> -->
</template>

<style lang="scss" scoped>
    .ui-input {
        $this: &;
        position: relative;
        cursor: text;
        display: flex;
        align-items: center;
        width: 100%;

        &--bottom-line {
            flex-direction: column;
            align-items: flex-start;
            gap: clampFluid(15);
            // border-bottom: 2px solid $gray;
            padding-bottom: 6px;
            // @include p1;

            &:has(#{$this}__value:focus-visible) {
                // border-color: $black-70;
            }

            #{$this}__label {
                // color: $white;
                opacity: .5;
            }

            #{$this}__value {
                // color: $white;

                &::placeholder{
                    // color: $white;
                    opacity: .8;
                }
            }
        }

        &--default {
            // @include p2;

            #{$this}__value {
                // border: 2px solid $black-70;
                padding: clampFluid(14) clampFluid(31) clampFluid(17);
            }
        }

        &--not-valid {
            border-color: #f73b3b;
            #{$this}__value {
                border-color: #f73b3b;
            }
        }

        &:not(:has(#{$this}__value:placeholder-shown)),
        &:has(#{$this}__value:focus-visible){
            #{$this}__placeholder{
                // opacity: 0;
                translate: 0 -1.2em;
                font-size: clampFluid(12, 15);
            }
        }

        &__label {
            // @include p2;
        }

        &__value{
            width: 100%;
            height: 100%;
            border: none;
            font-size: inherit;
            line-height: inherit;
        }

        &__placeholder {
            position: absolute;
            left: clampFluid(31);
            transition: all .3s ease;
            font-size: inherit;
            line-height: inherit;
            color: #a3a3a3;
            white-space: nowrap;
            pointer-events: none;
            width: auto;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
</style>
