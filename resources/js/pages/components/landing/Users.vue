<script setup>
import { defineProps, ref } from "vue";
import { useClickOutside } from "../../../useOutsideClick.js";

const { showUserDiv, appearUserDiv, hideUserDiv, exitUserMenu } = defineProps([
    "showUserDiv",
    "appearUserDiv",
    "hideUserDiv",
    "exitUserMenu",
]);

const innerUserDiv = ref(null);
const hide = ref();

const openDiv = () => {
    appearUserDiv();
    hide.value = true;
};

useClickOutside(innerUserDiv, () => {
    hideUserDiv();
});
</script>
<template>
    <div>
        <div ref="innerUserDiv">
            <button
                v-if="!showUserDiv"
                @click.prevent="openDiv"
                class="hover:text-slate-900 flex items-center"
            >
                <span class="pr-2 hover:underline underline-offset-4"
                    >Our Users</span
                >
                <i class="fa-regular fa-square-caret-down text-lg"></i>
            </button>
            <button
                v-if="showUserDiv"
                @click.prevent="hideUserDiv"
                class="text-green-500 flex items-center"
            >
                <span class="underline underline-offset-4">Our Users</span>
                <i class="fa-regular fa-square-caret-up ml-2 text-lg"></i>
            </button>
            <div class="absolute mt-4 overflow-hidden -ml-2 z-40">
                <div
                    v-if="showUserDiv"
                    class="p-8 slideFromTop w-450px rounded-md ring-1 m-2 ml-1 ring-slate-300 shadow-lg bg-white"
                >
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
                <div v-if="hide">
                    <div
                        v-if="exitUserMenu"
                        class="slideFromBottom h-80 w-450px ring-1 m-2 -mt-4 ml-1 ring-slate-300 shadow-lg bg-white"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>
