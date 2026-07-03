<template>
  <input ref="inputRef" type="text" />
</template>

<script setup>
import { watch } from 'vue'
import { useCurrencyInput } from 'vue-currency-input'

const props = defineProps({
  modelValue: {
    type: [Number, String],
    default: null
  },
  options: {
    type: Object,
    default: () => ({
      currency: 'BRL',
      currencyDisplay: 'symbol',
      locale: 'pt-BR',
      hideCurrencySymbolOnFocus: false,
      hideGroupingSeparatorOnFocus: false,
      hideNegligibleDecimalDigitsOnFocus: false,
      autoDecimalDigits: true,
      useGrouping: true,
      accountingSign: false
    })
  }
})

const { inputRef, setValue } = useCurrencyInput(props.options)

watch(
  () => props.modelValue,
  (value) => {
    // If value is string (from DB), parse it or let the component handle it
    setValue(Number(value) || 0)
  },
  { immediate: true }
)
</script>
