<script setup>
import { ref, onMounted } from "vue";
import dashboard from "../../../../../public/images/dashboard.png";

const tests = ref(false);
const quiz = ref(false);
const exam = ref(false);

const dynamicWord = () => {
    tests.value = true;
    setTimeout(() => {
        tests.value = false;
        setTimeout(() => {
            quiz.value = true;
        }, 3000);
    }, 3000);
};

onMounted(() => {
    dynamicWord();
});
</script>

<template>
    <div>
        <div
            class="1018px:flex bg-gradient-to-r from-teal-800 to-teal-600 p-20 pt-28 pb-28"
        >
            <div>
                <p
                    class="text-center 1018px:text-start text-4xl text-white font-bold"
                >
                    Turn your
                </p>
                <div class="overflow-hidden">
                    <p
                        v-if="tests"
                        class="slideEnterAnimation text-center 1018px:text-start text-green-300 text-4xl font-bold"
                    >
                        tests
                    </p>

                    <p
                        v-if="quiz"
                        class="slideEnterAnimation text-center 1018px:text-start text-green-300 text-4xl font-bold"
                    >
                        quizzes
                    </p>

                    <p
                        v-if="exam"
                        class="slideEnterAnimation text-center 1018px:text-start text-green-300 text-4xl font-bold"
                    >
                        examanations
                    </p>
                </div>

                <p
                    class="text-center 1018px:text-start text-white text-4xl font-bold"
                >
                    into wonderfull stories
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
            </div>
            <div
                class="flex mt-20 1018px:mt-0 justify-center items-center ml-20"
            >
                <img class="h-80 rounded-md" :src="dashboard" />
            </div>
        </div>
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

@keyframes slideExit {
    from {
        transform: translateY(0);
    }
    to {
        transform: translateY(-100%);
    }
}

.slideEnterAnimation {
    animation: slideEnter 0.5s forwards;
}

.slideExitAnimation {
    animation: slideExit 0.5s forwards;
}
</style>
