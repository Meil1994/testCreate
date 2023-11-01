<script setup>
import { defineProps, ref, watchEffect } from "vue";
import logo from "../../../../../public/images/logo.png";
import sideMenuProducts from "./landingComponents/sideMenuProducts.vue";
import sideMenuUsers from "./landingComponents/sideMenuUsers.vue";
import sideMenuWhatFor from "./landingComponents/sideMenuWhatFor.vue";

const { sideMenu, closeSideMenu, exitSideMenu } = defineProps([
    "sideMenu",
    "closeSideMenu",
    "exitSideMenu",
]);

const productButton = ref(true);
const userButton = ref(true);
const whatForButton = ref(true);

const product = ref(false);
const productDivOpen = ref(false);
const productDivClose = ref(false);

const openProduct = () => {
    productDivOpen.value = true;
    product.value = true;
    userButton.value = false;
    whatForButton.value = false;

    if (usersDivOpen) {
        usersDivOpen.value = false;
        users.value = false;
    }

    if (whatFor) {
        openWhatForDiv.value = false;
        whatFor.value = false;
    }

    setTimeout(() => {
        userButton.value = true;
        whatForButton.value = true;
    }, 500);
};

const closeProduct = () => {
    productDivOpen.value = false;
    userButton.value = false;
    whatForButton.value = false;
    productDivClose.value = true;

    setTimeout(() => {
        userButton.value = true;
        whatForButton.value = true;
        productDivClose.value = false;
        product.value = false;
    }, 500);
};

const users = ref(false);
const usersDivOpen = ref(false);
const usersDivClose = ref(false);

const openUser = () => {
    usersDivOpen.value = true;
    users.value = true;
    whatForButton.value = false;

    if (productDivOpen) {
        productDivOpen.value = false;
        product.value = false;
    }

    if (whatFor) {
        openWhatForDiv.value = false;
        whatFor.value = false;
    }

    setTimeout(() => {
        whatForButton.value = true;
    }, 500);
};

const closeUser = () => {
    usersDivOpen.value = false;
    usersDivClose.value = true;
    whatForButton.value = false;
    setTimeout(() => {
        usersDivClose.value = false;
        users.value = false;
        whatForButton.value = true;
    }, 500);
};

const whatFor = ref(false);
const openWhatForDiv = ref(false);
const closeWhatForDiv = ref(false);

const openWhatFor = () => {
    whatFor.value = true;
    openWhatForDiv.value = true;

    if (productDivOpen) {
        productDivOpen.value = false;
        product.value = false;
    }

    if (usersDivOpen) {
        usersDivOpen.value = false;
        users.value = false;
    }
};

const closeWhatFor = () => {
    openWhatForDiv.value = false;
    closeWhatForDiv.value = true;

    setTimeout(() => {
        whatFor.value = false;
        closeWhatForDiv.value = false;
    }, 500);
};
</script>
<template>
    <div>
        <div v-if="sideMenu" class="slide-enter absolute h-screen w-full flex">
            <div class="hidden 450px:block h-screen bg-slate-500/80 w-20%">
                <div class="flex justify-center pt-10">
                    <button @click.prevent="closeSideMenu">
                        <i
                            class="fa-regular fa-rectangle-xmark text-2xl text-white"
                        ></i>
                    </button>
                </div>
            </div>
            <div
                class="w-full 450px:w-80% p-5 pt-10 overflow-auto bg-slate-500"
            >
                <div class="pb-5 text-white 450px:hidden">
                    <button @click.prevent="closeSideMenu">
                        <i class="fa-regular fa-rectangle-xmark text-2xl"></i>
                    </button>
                </div>
                <div>
                    <img class="h-20" :src="logo" />
                </div>

                <div class="p-5 pt-0">
                    <div>
                        <div v-if="productButton">
                            <button
                                class="text-white"
                                v-if="!product"
                                @click.prevent="openProduct"
                            >
                                <i
                                    class="fa-regular fa-square-caret-down text-lg"
                                ></i>
                                Product
                            </button>
                            <button
                                class="text-green-500"
                                v-if="product"
                                @click.prevent="closeProduct"
                            >
                                <i
                                    class="fa-regular fa-square-caret-up text-lg"
                                ></i>
                                Product
                            </button>
                        </div>

                        <div v-if="product">
                            <sideMenuProducts
                                :productDivOpen="productDivOpen"
                                :productDivClose="productDivClose"
                            />
                        </div>
                    </div>
                    <div class="mt-2">
                        <div>
                            <div v-if="userButton">
                                <button
                                    class="text-white"
                                    v-if="!users"
                                    @click.prevent="openUser"
                                >
                                    <i
                                        class="fa-regular fa-square-caret-down text-lg"
                                    ></i>
                                    Our users
                                </button>
                                <button
                                    v-if="users"
                                    @click.prevent="closeUser"
                                    class="text-green-500"
                                >
                                    <i
                                        class="fa-regular fa-square-caret-up text-lg"
                                    ></i>
                                    Our Users
                                </button>
                            </div>
                            <div v-if="users">
                                <sideMenuUsers
                                    :usersDivOpen="usersDivOpen"
                                    :usersDivClose="usersDivClose"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div>
                            <div v-if="whatForButton">
                                <button
                                    class="text-white"
                                    v-if="!whatFor"
                                    @click.prevent="openWhatFor"
                                >
                                    <i
                                        class="fa-regular fa-square-caret-down text-lg"
                                    ></i>
                                    What's it for?
                                </button>
                                <button
                                    v-if="whatFor"
                                    @click.prevent="closeWhatFor"
                                    class="text-green-500"
                                >
                                    <i
                                        class="fa-regular fa-square-caret-up text-lg"
                                    ></i>
                                    What's it for?
                                </button>
                            </div>
                            <div>
                                <sideMenuWhatFor
                                    :openWhatForDiv="openWhatForDiv"
                                    :closeWhatForDiv="closeWhatForDiv"
                                />
                            </div>
                        </div>
                        <div class="mb-5 585px:hidden mt-10 flex items-center">
                            <div>
                                <a
                                    href="/user/signin"
                                    class="ring-2 ring-white text-white rounded-md p-10 pt-2 pb-2 hover:text-green-300 hover:ring-green-300"
                                    >Sign in</a
                                >
                            </div>
                            <div>
                                <a
                                    href="/user/signup"
                                    class="ml-5 p-9 pt-2 pb-2 ring-2 ring-white rounded-md text-white hover:text-green-300 hover:ring-green-300"
                                    >Sign up</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="exitSideMenu"
            class="slide-exit absolute h-screen w-full bg-slate-500"
        >
            <div class="h-screen bg-white w-20%">
                <div class="flex justify-center pt-10">
                    <button @click.prevent="closeSideMenu">
                        <i class="fa-regular fa-rectangle-xmark text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
@keyframes slideIn {
    from {
        right: -100%;
    }
    to {
        right: 0%;
    }
}

@keyframes slideOut {
    from {
        right: -20%;
    }
    to {
        right: -100%;
    }
}

.slide-enter {
    animation: slideIn 0.5s forwards;
}

.slide-exit {
    animation: slideOut 0.5s forwards;
}
</style>
