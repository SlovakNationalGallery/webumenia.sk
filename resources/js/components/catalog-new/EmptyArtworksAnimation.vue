<template>
    <LottieAnimation
        ref="empty-artwork"
        :loop="true"
        :animationData="emptyAnimation"
    ></LottieAnimation>
</template>
<script setup>
import emptyAnimation from './empty-animation.json'
</script>
