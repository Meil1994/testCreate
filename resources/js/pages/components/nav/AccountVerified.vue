<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import logo from "../../../../../public/images/logo.png";
import { useToastr } from "../../../toaster.js";
import { useRouter } from "vue-router";

const user = ref([]);
const toastr = useToastr();
const loading = ref(false);
const logoutLoading = ref(false);
const router = useRouter();

const getUser = () => {
    axios.get("/api/get/user").then((response) => {
        user.value = response.data;
    });
};

const verifyEmail = () => {
    loading.value = true;
    axios
        .post("/api/verify/email/address")
        .then((response) => {
            toastr.success(response.data.message);
            loading.value = false;
        })
        .catch((error) => {
            console.log(error);
            loading.value = false;
        });
};

const logout = () => {
    logoutLoading.value = true;
    axios
        .post("/api/logout")
        .then((response) => {
            logoutLoading.value = false;
            toastr.success(response.data.message);
            router.push("/user/signin");
        })
        .catch((error) => {
            console.error("Error fetching responder details:", error);
            logoutLoading.value = false;
        });
};
onMounted(() => {
    getUser();
});
</script>
<template>
    <div v-if="user.verified === 0">
        <div class="w-full h-screen absolute overflow-auto bg-white z-40">
            <div class="flex justify-end p-5 text-sm">
                <button
                    v-if="!logoutLoading"
                    @click.prevent="logout"
                    class="bg-red-500 p-4 pt-1 pb-1 rounded-md text-white hover:bg-red-600"
                >
                    Logout
                </button>
                <button
                    v-if="logoutLoading"
                    type="button"
                    class="bg-red-500 p-4 pt-1 pb-1 rounded-md text-white"
                >
                    <i class="fa-solid fa-spinner animate-spin"></i> Loading...
                </button>
            </div>
            <div class="flex justify-center">
                <img class="h-16" :src="logo" />
            </div>
            <div class="flex justify-center">
                <div class="w-500px">
                    <div class="text-center mt-10 text-2xl">
                        <p>Welcome to Test Create!</p>
                    </div>
                    <div class="text-center mt-10 text-sm">
                        <p>
                            In order to start using your Test Create account,
                            please check your email address for the validation
                            link.
                        </p>
                    </div>
                    <div class="text-center mt-10 text-sm">
                        <p>
                            If you haven't received the email, click the button
                            below.
                        </p>
                    </div>
                    <div class="flex justify-center mt-4">
                        <button
                            v-if="!loading"
                            @click.prevent="verifyEmail"
                            class="bg-blue-500 p-2 pl-5 pr-5 text-white rounded-md hover:bg-blue-600"
                        >
                            Verify Email Address
                        </button>
                        <button
                            type="button"
                            v-if="loading"
                            class="bg-blue-500 p-2 pl-5 pr-5 text-white rounded-md"
                        >
                            <i class="fa-solid fa-spinner animate-spin"></i>
                            Loading...
                        </button>
                    </div>
                    <div class="mt-10 p-5">
                        <div
                            class="flex items-center justify-center border-b border-slate-300 pb-5"
                        >
                            <div class="flex items-center w-350px">
                                <i
                                    class="fa-regular fa-pen-to-square text-5xl text-green-400"
                                ></i>
                                <div class="ml-5">
                                    <p class="text-xl text-slate-700">Create</p>
                                    <p class="text-sm mt-1 text-slate-700">
                                        Experience hassle-free test creation in
                                        just a few clicks!
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-center border-b border-slate-300 pb-5 mt-10"
                        >
                            <div class="flex items-center w-350px">
                                <i
                                    class="fa-regular fa-share-from-square text-5xl text-green-400"
                                ></i>
                                <div class="ml-5">
                                    <p class="text-xl text-slate-700">Share</p>
                                    <p class="text-sm mt-1 text-slate-700">
                                        Share your test link with a single
                                        click!
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-center border-b border-slate-300 pb-5 mt-10"
                        >
                            <div class="flex items-center w-350px">
                                <i
                                    class="fa-solid fa-list-check text-5xl text-green-400"
                                ></i>
                                <div class="ml-5">
                                    <p class="text-xl text-slate-700">Check</p>
                                    <p class="text-sm mt-1 text-slate-700">
                                        Automatically checks test responses!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
