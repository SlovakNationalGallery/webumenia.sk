<template>
  <div class="tw-relative">
    <div class="tw-invisible">
      <slot />
    </div>
    <transition enter-class="tw-opacity-0" enter-to-class="tw-opacity-100"
      enter-active-class="tw-transition-opacity tw-duration-300" leave-class="tw-opacity-100"
      leave-to-class="tw-opacity-0" leave-active-class="tw-transition-opacity tw-duration-300">
      <div :key="transitionKey" class="tw-absolute tw-inset-0">
        <slot />
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  props: ['transitionKey']
}
</script>