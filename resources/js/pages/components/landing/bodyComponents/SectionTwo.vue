<script setup>
import Business from "./Business.vue";
import Education from "./Education.vue";
import { ref } from "vue";

const businessDiv = ref(true);
const educationDiv = ref(false);

const openBusiness = () => {
    educationDiv.value = false;
    businessDiv.value = true;
};
const openEducation = () => {
    businessDiv.value = false;
    educationDiv.value = true;
};
</script>

<template>
    <div>
        <div class="p-10">
            <p class="text-center text-green-600 text-lg">
                Knowledge and skills assessment made easy
            </p>
            <p class="text-center font-bold text-3xl text-green-900">
                One platform, creates multiple solutions
            </p>
        </div>
        <div v-if="businessDiv" class="flex justify-center">
            <div class="flex justify-center rounded-md bg-slate-300 w-480 p-1">
                <button
                    @click="openBusiness"
                    class="p-5 pt-1 pb-1 text-green-900 text-lg bg-white rounded-md"
                >
                    Business
                </button>
                <button
                    @click="openEducation"
                    class="p-5 pt-1 pb-1 text-lg rounded-md text-white ml-1 hover:bg-white hover:text-green-900"
                >
                    Education
                </button>
            </div>
        </div>
        <div v-if="educationDiv" class="flex justify-center">
            <div class="flex justify-center rounded-md bg-slate-300 w-480 p-1">
                <button
                    @click="openBusiness"
                    class="p-5 pt-1 pb-1 text-lg rounded-md text-white mr-1 hover:bg-white hover:text-green-900"
                >
                    Business
                </button>
                <button
                    @click="openEducation"
                    class="p-5 pt-1 pb-1 text-green-900 text-lg bg-white rounded-md"
                >
                    Education
                </button>
            </div>
        </div>
        <div class="slider" v-if="businessDiv">
            <Business />
        </div>
        <div class="slider" v-if="educationDiv">
            <Education />
        </div>
    </div>
</template>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.slider {
    animation: fadeIn 1.6s forwards;
}
</style>
