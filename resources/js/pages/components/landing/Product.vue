<script setup>
import { defineProps, ref, watchEffect } from "vue";
import { useClickOutside } from "../../../useOutsideClick.js";

const { showProductDiv, appearProductDiv, hideProductDiv, exitProductMenu } =
    defineProps([
        "showProductDiv",
        "appearProductDiv",
        "hideProductDiv",
        "exitProductMenu",
    ]);
const innerProductDiv = ref(null);
const hide = ref(false);

const openDiv = () => {
    appearProductDiv();
    hide.value = true;
};

useClickOutside(innerProductDiv, () => {
    hideProductDiv();
});
</script>
<template>
    <div>
        <div ref="innerProductDiv">
            <button
                v-if="!showProductDiv"
                @click.prevent="openDiv"
                class="hover:text-slate-900 flex items-center"
            >
                <span class="pr-2 hover:underline underline-offset-4"
                    >Product</span
                >
                <i class="fa-regular fa-square-caret-down text-lg"></i>
            </button>
            <button
                v-if="showProductDiv"
                @click.prevent="hideProductDiv"
                class="text-green-500 flex items-center"
            >
                <span class="underline underline-offset-4">Product</span>
                <i class="fa-regular fa-square-caret-up ml-2 text-lg"></i>
            </button>
            <div class="absolute mt-4 overflow-hidden -ml-2 z-40">
                <div
                    v-if="showProductDiv"
                    class="p-8 slideFromTop w-450px rounded-md ring-1 m-2 ml-1 ring-slate-300 shadow-lg bg-white"
                >
                    <div
                        class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center"
                    >
                        <i
                            class="fa-solid fa-file-circle-check text-3xl mr-4 text-green-400"
                        ></i>
                        <div>
                            <p class="text-start">Knowledge Assessment</p>
                            <p class="text-start text-sm text-slate-500">
                                Create online exams, tests, and quizzes.
                            </p>
                        </div>
                    </div>

                    <div
                        class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center mt-5"
                    >
                        <i
                            class="fa-regular fa-user text-3xl mr-6 text-green-400"
                        ></i>
                        <div>
                            <p class="text-start">Test Takers Responses</p>
                            <p class="text-start text-sm text-slate-500">
                                Detailed exam responses.
                            </p>
                        </div>
                    </div>

                    <div
                        class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center mt-5"
                    >
                        <i
                            class="fa-solid fa-chart-pie text-3xl mr-4 text-green-400"
                        ></i>
                        <div>
                            <p class="text-start">Visual Chart Report</p>
                            <p class="text-start text-sm text-slate-500">
                                Insights through data visualization.
                            </p>
                        </div>
                    </div>

                    <div
                        class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center mt-5"
                    >
                        <i
                            class="fa-solid fa-square-poll-vertical text-4xl mr-4 text-green-400"
                        ></i>
                        <div>
                            <p class="text-start">Grade</p>
                            <p class="text-start text-sm text-slate-500">
                                Auto grading system.
                            </p>
                        </div>
                    </div>

                    <div
                        class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center mt-5"
                    >
                        <i
                            class="fa-solid fa-pen-to-square text-4xl mr-3 text-green-400"
                        ></i>
                        <div>
                            <p class="text-start">Test Edit</p>
                            <p class="text-start text-sm text-slate-500">
                                Dynamic test adjustments and edits.
                            </p>
                        </div>
                    </div>
                </div>
                <div v-if="hide">
                    <div
                        v-if="exitProductMenu"
                        class="slideFromBottom h-80 w-450px ring-1 m-2 -mt-5 ml-1 ring-slate-300 shadow-lg bg-white"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>
