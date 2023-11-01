<script setup>
import { defineProps, ref, watchEffect } from "vue";

const { usersDivOpen, usersDivClose } = defineProps([
    "usersDivOpen",
    "usersDivClose",
]);
</script>
<template>
    <div class="overflow-hidden mt-2 mb-5">
        <div
            v-if="usersDivOpen"
            class="slideFromTop bg-white p-5 w-full rounded-md"
        >
            <div>
                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200"
                >
                    <i
                        class="fa-solid fa-building text-4xl mr-6 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Human Resources</p>
                        <p class="text-start text-sm text-slate-500">
                            Upgrade hiring process.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-person text-4xl mr-7 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Teachers</p>
                        <p class="text-start text-sm text-slate-500">
                            Positive students learning experience.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-school text-3xl mr-4 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Schools</p>
                        <p class="text-start text-sm text-slate-500">
                            Good for online and onsite learners.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-person-breastfeeding text-4xl mr-5 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Parents</p>
                        <p class="text-start text-sm text-slate-500">
                            See what our children are capable of.
                        </p>
                    </div>
                </button>
            </div>
        </div>

        <div
            v-if="usersDivClose"
            class="slideExit bg-white p-5 w-full rounded-md"
        >
            <div>
                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200"
                >
                    <i
                        class="fa-solid fa-building text-4xl mr-6 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Human Resources</p>
                        <p class="text-start text-sm text-slate-500">
                            Upgrade hiring process.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-person text-4xl mr-7 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Teachers</p>
                        <p class="text-start text-sm text-slate-500">
                            Positive students learning experience.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-school text-3xl mr-4 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Schools</p>
                        <p class="text-start text-sm text-slate-500">
                            Good for online and onsite learners.
                        </p>
                    </div>
                </button>

                <button
                    class="w-full bg-slate-100 p-2 text-slate-700 rounded-md flex items-center hover:bg-slate-200 mt-5"
                >
                    <i
                        class="fa-solid fa-person-breastfeeding text-4xl mr-5 text-green-400"
                    ></i>
                    <div>
                        <p class="text-start">Parents</p>
                        <p class="text-start text-sm text-slate-500">
                            See what our children are capable of.
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
