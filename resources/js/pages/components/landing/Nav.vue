<script setup>
import logo from "../../../../../public/images/logo.png";
import SideMenu from "./SideMenu.vue";
import Product from "./Product.vue";
import WhatFor from "./WhatFor.vue";
import Users from "./Users.vue";
import { useRouter } from "vue-router";
import { ref, watchEffect } from "vue";

const showProductDiv = ref(false);
const exitProductMenu = ref(false);
const showUserDiv = ref(false);
const exitUserMenu = ref(false);
const showWhatForDiv = ref(false);
const exitWhatForMenu = ref(false);
const sideMenu = ref(false);
const exitSideMenu = ref(false);

const appearProductDiv = () => {
    showUserDiv.value = false;
    showWhatForDiv.value = false;
    showProductDiv.value = true;
    exitProductMenu.value = false;
};

const hideProductDiv = () => {
    showProductDiv.value = false;
    exitProductMenu.value = true;
};

const appearUserDiv = () => {
    showProductDiv.value = false;
    showWhatForDiv.value = false;
    showUserDiv.value = true;
    exitUserMenu.value = false;
};

const hideUserDiv = () => {
    showUserDiv.value = false;
    exitUserMenu.value = true;
};

const appearWhatForDiv = () => {
    showProductDiv.value = false;
    showUserDiv.value = false;
    showWhatForDiv.value = true;
    exitWhatForMenu.value = false;
};

const hideWhatForDiv = () => {
    showWhatForDiv.value = false;
    exitWhatForMenu.value = true;
};

const openSideMenu = () => {
    sideMenu.value = true;
    exitSideMenu.value = false;
};

const closeSideMenu = () => {
    sideMenu.value = false;
    exitSideMenu.value = true;
};
</script>
<template>
    <div>
        <div>
            <SideMenu
                :sideMenu="sideMenu"
                :closeSideMenu="closeSideMenu"
                :exitSideMenu="exitSideMenu"
            />
        </div>
        <div
            class="flex justify-between 850px:grid 850px:grid-cols-4 1070px:grid-cols-5 p-2 shadow-md"
        >
            <div class="850px:col-span-3 pl-10 pr-10">
                <ul class="flex items-center justify-between text-slate-700">
                    <li><img class="h-20" :src="logo" /></li>
                    <li class="hidden 850px:block">
                        <Product
                            :showProductDiv="showProductDiv"
                            :appearProductDiv="appearProductDiv"
                            :hideProductDiv="hideProductDiv"
                            :exitProductMenu="exitProductMenu"
                        />
                    </li>
                    <li class="hidden 850px:block">
                        <Users
                            :showUserDiv="showUserDiv"
                            :exitUserMenu="exitUserMenu"
                            :appearUserDiv="appearUserDiv"
                            :hideUserDiv="hideUserDiv"
                        />
                    </li>
                    <li class="hidden 850px:block">
                        <WhatFor
                            :showWhatForDiv="showWhatForDiv"
                            :exitWhatForMenu="exitWhatForMenu"
                            :appearWhatForDiv="appearWhatForDiv"
                            :hideWhatForDiv="hideWhatForDiv"
                        />
                    </li>
                </ul>
            </div>
            <div
                class="850px:col-span-1 1070px:col-span-2 flex 850px:justify-end items-center mr-10 850px:mr-0 900px:mr-5 950px:mr-8 980px:mr-10"
            >
                <div class="585px:mr-4 text-xl 850px:hidden">
                    <button @click.prevent="openSideMenu">
                        <i
                            class="fa-solid fa-bars p-2 rounded-md text-slate-700"
                        ></i>
                    </button>
                </div>
                <router-link
                    to="/user/signin"
                    class="hidden 585px:block ring-1 ring-slate-700 text-slate-700 850px:text-xl rounded-md p-2 hover:bg-slate-200 hover:text-cyan-900 hover:ring-white"
                    >Sign in</router-link
                >
                <router-link
                    to="/user/signup"
                    class="hidden 585px:block bg-green-500 hover:bg-green-600 850px:text-xl p-2 ml-4 text-white rounded-md"
                    >Sign up</router-link
                >
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
        transform: translateY(-110%);
    }
}

.slideFromTop {
    animation: slideEnter 0.5s forwards;
}

.slideFromBottom {
    animation: slideExit 0.5s forwards;
}
</style>
