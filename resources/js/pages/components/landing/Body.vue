<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";
import DashboardLogo from "../../../../../public/images/dashboard.png";
import SectionTwo from "./bodyComponents/SectionTwo.vue";

const slides = ["tests", "exams", "quizzes"];
const currentSlide = ref("tests");
let intervalId = null;
const autoSlideInterval = 5000;

const nextSlide = () => {
    const currentIndex = slides.indexOf(currentSlide.value);
    currentSlide.value = slides[(currentIndex + 1) % slides.length];
};

const startAutoSlide = () => {
    intervalId = setInterval(nextSlide, autoSlideInterval);
};

const stopAutoSlide = () => {
    clearInterval(intervalId);
};

onMounted(() => {
    startAutoSlide();
});

onBeforeUnmount(() => {
    stopAutoSlide();
});
</script>

<template>
    <div>
        <div
            class="1018px:grid grid-cols-2 bg-gradient-to-r from-teal-800 to-teal-600 p-20 pt-28 pb-28"
        >
            <div class="col-span-1">
                <p
                    class="text-center 1018px:text-start text-3xl text-white font-bold"
                >
                    Turn your
                </p>
                <div>
                    <div class="slider overflow-hidden">
                        <div
                            :key="currentSlide"
                            class="slideFromTop text-yellow-400 text-center 1018px:text-start text-3xl"
                            :class="{ active: currentSlide === 'tests' }"
                        >
                            {{ currentSlide }}
                        </div>
                    </div>
                </div>

                <p
                    class="text-center 1018px:text-start text-white text-3xl font-bold"
                >
                    into wonderful stories
                </p>

                <p
                    class="text-center 1018px:text-start text-white text-lg mt-10"
                >
                    The top skills and knowledge assessment software, serving
                </p>
                <p class="text-center 1018px:text-start text-white text-lg">
                    business and educational users worldwide.
                </p>

                <div class="flex justify-center 1018px:justify-start mt-10">
                    <router-link
                        class="p-2 pl-4 pr-4 rounded-md bg-yellow-200 hover:bg-yellow-300 text-lg font-bold text-teal-800"
                        to="/user/signup"
                        >Sign up - It's free</router-link
                    >
                </div>
                <div>
                    <p
                        class="text-center 1018px:text-start mt-2 text-slate-300"
                    >
                        <i class="fa-solid fa-check"></i> no credit card
                        required
                    </p>
                </div>
            </div>
            <div
                class="col-span-1 hidden 548px:flex mt-20 1018px:mt-0 justify-center items-center 1018px:ml-20"
            >
                <img class="rounded-md" :src="DashboardLogo" />
            </div>
        </div>
        <SectionTwo />
    </div>
</template>

<style>
@keyframes slideEnter {
    from {
        transform: translateY(-100%);
    }
    to {
        transform: translateY(0);
    }
}

.slideFromTop {
    animation: slideEnter 0.5s forwards;
}
</style>
