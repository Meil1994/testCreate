<script setup>
import { defineProps, ref, watchEffect } from "vue";

const { productDivOpen, productDivClose } = defineProps([
    "productDivOpen",
    "productDivClose",
]);
</script>
<template>
    <div class="overflow-hidden mt-2 mb-5">
        <div
            v-if="productDivOpen"
            class="slideFromTop bg-white p-5 w-full rounded-md"
        >
            <div>
                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200"
                >
                    <i
                        class="fa-solid fa-file-circle-check text-2xl mr-4 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Knowledge Assessment</p>
                        <p class="text-start text-sm text-slate-500">
                            Create online exams, tests, and quizzes.
                        </p>
                    </div>
                </button>
                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-regular fa-user text-2xl mr-6 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Test Takers Responses</p>
                        <p class="text-start text-sm text-slate-500">
                            Detailed exam responses.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-chart-pie text-2xl mr-4 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Visual Chart Report</p>
                        <p class="text-start text-sm text-slate-500">
                            Insights through data visualization.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-square-poll-vertical text-2xl mr-4 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Grade</p>
                        <p class="text-start text-sm text-slate-500">
                            Auto grading system.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-pen-to-square text-2xl mr-3 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Test Edit</p>
                        <p class="text-start text-sm text-slate-500">
                            Dynamic test adjustments and edits.
                        </p>
                    </div>
                </button>
            </div>
        </div>

        <div v-else class="slideExit bg-white p-5 w-full rounded-md">
            <div>
                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200"
                >
                    <i
                        class="fa-solid fa-file-circle-check text-2xl mr-4 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Knowledge Assessment</p>
                        <p class="text-start text-sm text-slate-500">
                            Create online exams, tests, and quizzes.
                        </p>
                    </div>
                </button>
                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-regular fa-user text-2xl mr-6 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Test Takers Responses</p>
                        <p class="text-start text-sm text-slate-500">
                            Detailed exam responses.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-chart-pie text-2xl mr-4 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Visual Chart Report</p>
                        <p class="text-start text-sm text-slate-500">
                            Insights through data visualization.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-square-poll-vertical text-2xl mr-4 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Grade</p>
                        <p class="text-start text-sm text-slate-500">
                            Auto grading system.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-pen-to-square text-2xl mr-3 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Test Edit</p>
                        <p class="text-start text-sm text-slate-500">
                            Dynamic test adjustments and edits.
                        </p>
                    </div>
                </button>
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
