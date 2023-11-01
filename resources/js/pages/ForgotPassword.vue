<script setup>
import logo from "../../../public/images/logo.png";
import { ref } from "vue";
import axios from "axios";
import { useToastr } from "../toaster.js";
import { useRouter } from "vue-router";

const toastr = useToastr();
const email = ref("");
const loading = ref(false);
const router = useRouter();

const submitReset = () => {
    loading.value = true;
    axios
        .post("/api/reset/password", {
            email: email.value,
        })
        .then((response) => {
            loading.value = false;
            toastr.success(response.data.message);
            router.push("/user/signin");
        })
        .catch((error) => {
            loading.value = false;
            toastr.error("Email not found!");
        });
};
</script>
<template>
    <div>
        <div class="flex justify-center">
            <img class="h-28" :src="logo" />
        </div>
        <form class="flex justify-center mt-20">
            <div class="ring-1 ring-slate-300 shadow-xl rounded-md w-80 mt-10">
                <div
                    class="bg-green-500 rounded-t-md p-5 text-center text-white"
                >
                    <p>Password Reset Form</p>
                </div>
                <div class="p-10">
                    <p class="text-slate-600">Email Address</p>
                    <input
                        v-model="email"
                        type="email"
                        class="ring-1 ring-slate-300 w-full p-2 rounded-md focus:outline-none"
                    />

                    <div class="flex justify-center">
                        <button
                            v-if="!loading"
                            @click.prevent="submitReset"
                            class="bg-blue-500 p-2 rounded-md text-white hover:bg-blue-600 mt-7"
                        >
                            Reset Password
                        </button>
                        <p
                            v-if="loading"
                            class="bg-blue-500 p-2 pl-4 pr-4 rounded-md text-white mt-7"
                        >
                            <i class="fa-solid fa-spinner animate-spin"></i>
                            Loading...
                        </p>
                    </div>
                    <div
                        class="flex justify-center mt-2 text-sm underline-offset-4 underline text-slate-600 hover:text-blue-500"
                    >
                        <a href="/user/signin">Login</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
