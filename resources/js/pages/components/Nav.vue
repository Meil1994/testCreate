<script setup>
import logo from "../../../../public/images/logo.png";
import pencil from "../../../../public/images/pencil.png";
import { useToastr } from "../../toaster.js";
import { useRouter } from "vue-router";
import { ref } from "vue";
import AccountVerified from "./nav/AccountVerified.vue";

const toastr = useToastr();
const router = useRouter();
const showSubmitConfirmation = ref(false);
const showIcon = ref(true);
const slide = ref(true);
const loading = ref(false);

const hideFullIcon = () => {
    slide.value = false;
    showIcon.value = false;
};

const showFullIcon = () => {
    slide.value = true;
    showIcon.value = true;
};

const handleLogout = () => {
    loading.value = true;
    axios
        .post("/api/logout")
        .then((response) => {
            loading.value = false;
            toastr.success(response.data.message);
            router.push("/user/signin");
        })
        .catch((error) => {
            loading.value = false;
            console.error("Error fetching responder details:", error);
        });
};

const showClickConfirmation = () => {
    showSubmitConfirmation.value = true;
};

const hideClickConfirmation = () => {
    showSubmitConfirmation.value = false;
};
</script>

<template>
    <div>
        <div
            v-if="showSubmitConfirmation"
            class="absolute h-screen w-100% overflow-auto z-40"
        >
            <div
                class="bg-slate-500/90 flex justify-center items-center h-screen"
            >
                <div class="bg-white rounded-md h-80 w-80">
                    <div class="flex justify-end bg-red-500 p-4 rounded-t">
                        <button
                            class="text-white text-2xl"
                            @click.prevent="hideClickConfirmation"
                        >
                            <i
                                class="fa-regular fa-rectangle-xmark hover:text-slate-300"
                            ></i>
                        </button>
                    </div>
                    <div class="mt-7 p-6 pt-0">
                        <p class="text-red-500 text-center text-4xl mb-2">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </p>
                        <p class="text-center">
                            Are you sure you want to logout from your account?
                        </p>
                        <div class="flex justify-center mt-10">
                            <button
                                v-if="!loading"
                                @click.prevent="handleLogout"
                                class="bg-red-500 p-2 w-40 text-white rounded-md hover:bg-red-600"
                            >
                                Logout
                            </button>
                            <button
                                type="button"
                                v-if="loading"
                                class="bg-red-500 p-2 w-40 text-white rounded-md"
                            >
                                <i class="fa-solid fa-spinner animate-spin"></i>
                                Loading...
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <AccountVerified :showClickConfirmation="showClickConfirmation" />
        <div class="hidden 700px:flex">
            <div>
                <div class="col-span-1 h-screen overflow-auto p-2">
                    <img v-if="showIcon" class="h-16" :src="logo" />
                    <img v-if="!showIcon" class="h-16" :src="pencil" />
                    <router-link
                        to="/dashboard"
                        active-class="active-page"
                        class="w-100% p-4 flex items-center h-10 mt-2 text-slate-700 hover:text-green-400"
                    >
                        <i class="fa-solid fa-note-sticky mr-2 text-xl"></i>
                        <span class="fadeInIcon" v-if="showIcon">Test</span>
                    </router-link>
                    <router-link
                        to="/test/taker"
                        active-class="active-page"
                        :class="{
                            'w-40 ': showIcon,
                        }"
                        class="w-100% p-4 flex items-center h-10 mt-2 text-slate-700 hover:text-green-400"
                    >
                        <i class="fa-solid fa-user mr-2 text-xl"></i>
                        <span class="fadeInIcon" v-if="showIcon"
                            >Test Taker</span
                        >
                    </router-link>
                    <router-link
                        to="/chart"
                        active-class="active-page"
                        class="w-100% p-4 flex items-center h-10 mt-2 text-slate-700 hover:text-green-400"
                    >
                        <i
                            class="fa-solid fa-square-poll-vertical mr-2 text-xl"
                        ></i>
                        <span class="fadeInIcon" v-if="showIcon">Chart</span>
                    </router-link>
                    <router-link
                        to="/my/account"
                        active-class="active-page"
                        class="w-100% p-4 flex items-center h-10 mt-2 text-slate-700 hover:text-green-400"
                    >
                        <i class="fa-solid fa-gear mr-2 text-xl"></i>
                        <span class="fadeInIcon" v-if="showIcon">Account</span>
                    </router-link>

                    <div class="mt-28 p-4 h-10">
                        <button
                            class="w-100% flex items-center text-slate-700 hover:text-green-400"
                            @click.prevent="showClickConfirmation"
                        >
                            <i
                                class="fa-solid fa-arrow-right-from-bracket mr-2 text-xl"
                            ></i
                            ><span class="fadeInIcon" v-if="showIcon"
                                >Logout</span
                            >
                        </button>
                    </div>

                    <div class="p-4 pt-0 h-10 mt-2">
                        <button
                            class="pt-0 text-slate-500 hover:text-green-400"
                            v-if="showIcon"
                            @click.prevent="hideFullIcon"
                        >
                            <i class="fa-solid fa-angles-left mr-3"></i>
                            <span class="fadeInIcon">Hide</span>
                        </button>
                        <button
                            class="pt-0 text-slate-500 hover:text-green-400"
                            v-if="!showIcon"
                            @click.prevent="showFullIcon"
                        >
                            <i class="fa-solid fa-angles-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div>
                <div v-if="showIcon" class="h-screen w-2px bg-slate-300"></div>
                <div v-if="!showIcon" class="h-screen w-2px bg-slate-300"></div>
            </div>
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

.fadeInIcon {
    animation: fadeIn 1.5s forwards;
}
.active-link {
    background-color: red;
}
router-link.active-link {
    background-color: red;
}
.active-page {
    color: #68d391;
}
</style>
