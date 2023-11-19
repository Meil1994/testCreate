<script setup>
import Nav from "./components/landing/Nav.vue";
import Body from "./components/landing/Body.vue";
import Footer from "./components/landing/Footer.vue";
</script>
<template>
    <div class="overflow-hidden h-screen">
        <Nav />
        <div class="h-screen overflow-auto pb-20">
            <Body />
            <Footer />
        </div>
    </div>
</template>
