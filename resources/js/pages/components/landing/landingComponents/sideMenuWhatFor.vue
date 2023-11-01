<script setup>
import { defineProps, ref, watchEffect } from "vue";

const { openWhatForDiv, closeWhatForDiv } = defineProps([
    "openWhatForDiv",
    "closeWhatForDiv",
]);
</script>
<template>
    <div class="overflow-hidden mt-2 mb-5">
        <div
            v-if="openWhatForDiv"
            class="slideFromTop bg-white p-5 w-full rounded-md"
        >
            <div class="w-full p-2 text-slate-700 rounded-md flex items-center">
                <i
                    class="fa-solid fa-user-graduate text-2xl mr-6 text-green-400"
                ></i>
                <div>
                    <p class="text-start text-2xl text-slate-800">Education</p>
                </div>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">Classroom Assessments</p>
                <p class="text-start text-xs text-slate-500">
                    Create quizzes and tests to assess students.
                </p>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">Standardized Testing</p>
                <p class="text-start text-xs text-slate-500">
                    Educational institutions can design standardized tests.
                </p>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">
                    Self-assessment and Study Aids
                </p>
                <p class="text-start text-xs text-slate-500">
                    Students can create their own practice tests for
                    self-assessment.
                </p>
            </div>

            <div
                class="w-full p-2 text-slate-700 rounded-md flex items-center mt-5"
            >
                <i
                    class="fa-solid fa-building text-2xl mr-6 text-green-400"
                ></i>
                <div>
                    <p class="text-start text-2xl text-slate-800">Business</p>
                </div>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">Employee Training</p>
                <p class="text-start text-xs text-slate-500">
                    Businesses can design tests and quizzes to train employees.
                </p>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">
                    Pre-Employment Screening
                </p>
                <p class="text-start text-xs text-slate-500">
                    Evaluate job applicants' qualifications and competencies.
                </p>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">Product Knowledge</p>
                <p class="text-start text-xs text-slate-500">
                    Access tests that evaluate employees knowledge of products
                    and services.
                </p>
            </div>
        </div>

        <div
            v-if="closeWhatForDiv"
            class="slideExit bg-white p-5 w-full rounded-md"
        >
            <div class="w-full p-2 text-slate-700 rounded-md flex items-center">
                <i
                    class="fa-solid fa-user-graduate text-2xl mr-6 text-green-400"
                ></i>
                <div>
                    <p class="text-start text-2xl text-slate-800">Education</p>
                </div>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">Classroom Assessments</p>
                <p class="text-start text-xs text-slate-500">
                    Create quizzes and tests to assess students.
                </p>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">Standardized Testing</p>
                <p class="text-start text-xs text-slate-500">
                    Educational institutions can design standardized tests.
                </p>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">
                    Self-assessment and Study Aids
                </p>
                <p class="text-start text-xs text-slate-500">
                    Students can create their own practice tests for
                    self-assessment.
                </p>
            </div>

            <div
                class="w-full p-2 text-slate-700 rounded-md flex items-center mt-5"
            >
                <i
                    class="fa-solid fa-building text-2xl mr-6 text-green-400"
                ></i>
                <div>
                    <p class="text-start text-2xl text-slate-800">Business</p>
                </div>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">Employee Training</p>
                <p class="text-start text-xs text-slate-500">
                    Businesses can design tests and quizzes to train employees.
                </p>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">
                    Pre-Employment Screening
                </p>
                <p class="text-start text-xs text-slate-500">
                    Evaluate job applicants' qualifications and competencies.
                </p>
            </div>

            <div class="bg-slate-100 p-2 rounded-md mt-2">
                <p class="text-start text-slate-800">Product Knowledge</p>
                <p class="text-start text-xs text-slate-500">
                    Access tests that evaluate employees knowledge of products
                    and services.
                </p>
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

.slideFromTop {
    animation: slideEnter 0.5s forwards;
}

.slideExit {
    animation: slideExit 0.5s forwards;
}
</style>
