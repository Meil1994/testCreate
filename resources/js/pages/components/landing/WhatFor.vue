<script setup>
import { defineProps, ref } from "vue";
import { useClickOutside } from "../../../useOutsideClick.js";

const { showWhatForDiv, appearWhatForDiv, hideWhatForDiv, exitWhatForMenu } =
    defineProps([
        "showWhatForDiv",
        "appearWhatForDiv",
        "hideWhatForDiv",
        "exitWhatForMenu",
    ]);

const innerWhatsFor = ref(null);
const hide = ref(false);

const openDiv = () => {
    appearWhatForDiv();
    hide.value = true;
};

useClickOutside(innerWhatsFor, () => {
    hideWhatForDiv();
});
</script>
<template>
    <div>
        <div ref="innerWhatsFor">
            <button
                v-if="!showWhatForDiv"
                @click.prevent="openDiv"
                class="hover:text-slate-900 flex items-center"
            >
                <span class="pr-2 hover:underline underline-offset-4"
                    >What's it for?</span
                >
                <i class="fa-regular fa-square-caret-down text-lg"></i>
            </button>
            <button
                v-if="showWhatForDiv"
                @click.prevent="hideWhatForDiv"
                class="text-green-500 flex items-center"
            >
                <span class="underline underline-offset-4">What's it for?</span>
                <i class="fa-regular fa-square-caret-up ml-2 text-lg"></i>
            </button>
            <div
                id="showWhatForDiv"
                class="absolute z-40 mt-4 overflow-hidden -ml-20 1070px:-ml-2"
            >
                <div
                    v-if="showWhatForDiv"
                    class="p-8 slideFromTop w-450px rounded-md ring-1 m-2 ml-1 ring-slate-300 shadow-lg bg-white"
                >
                    <div class="w-full p-2 text-slate-700 rounded-md flex">
                        <i
                            class="fa-solid fa-user-graduate text-3xl mr-6 text-green-400"
                        ></i>
                        <div>
                            <p class="text-start text-2xl text-slate-800">
                                Education
                            </p>
                        </div>
                    </div>

                    <div class="bg-slate-100 p-2 rounded-md mt-2">
                        <p class="text-start text-slate-800">
                            Classroom Assessments
                        </p>
                        <p class="text-start text-xs text-slate-500">
                            Create quizzes and tests to assess students.
                        </p>
                    </div>

                    <div class="bg-slate-100 p-2 rounded-md mt-2">
                        <p class="text-start text-slate-800">
                            Standardized Testing
                        </p>
                        <p class="text-start text-xs text-slate-500">
                            Educational institutions can design standardized
                            tests.
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

                    <div class="w-full p-2 text-slate-700 rounded-md flex mt-5">
                        <i
                            class="fa-solid fa-building text-3xl mr-6 text-green-400"
                        ></i>
                        <div>
                            <p class="text-start text-2xl text-slate-800">
                                Business
                            </p>
                        </div>
                    </div>

                    <div class="bg-slate-100 p-2 rounded-md mt-2">
                        <p class="text-start text-slate-800">
                            Employee Training
                        </p>
                        <p class="text-start text-xs text-slate-500">
                            Businesses can design tests and quizzes to train
                            employees.
                        </p>
                    </div>

                    <div class="bg-slate-100 p-2 rounded-md mt-2">
                        <p class="text-start text-slate-800">
                            Pre-Employment Screening
                        </p>
                        <p class="text-start text-xs text-slate-500">
                            Evaluate job applicants' qualifications and
                            competencies.
                        </p>
                    </div>

                    <div class="bg-slate-100 p-2 rounded-md mt-2">
                        <p class="text-start text-slate-800">
                            Product Knowledge
                        </p>
                        <p class="text-start text-xs text-slate-500">
                            Access tests that evaluate employees knowledge of
                            products and services.
                        </p>
                    </div>
                </div>
                <div v-if="hide">
                    <div
                        v-if="exitWhatForMenu"
                        class="slideFromBottom h-80 w-450px ring-1 m-2 -mt-10 ml-1 ring-slate-300 shadow-lg bg-white"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>
