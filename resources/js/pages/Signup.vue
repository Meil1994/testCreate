<script setup>
import { ref } from "vue";
import axios from "axios";
import logo from "../../../public/images/logo.png";
import loginLogo from "../../../public/images/login.png";
import { useToastr } from "../toaster.js";
import { useRouter } from "vue-router";

const toastr = useToastr();
const email = ref("");
const password = ref("");
const confirmPassword = ref("");
const router = useRouter();
const loading = ref(false);

const handleSubmit = async () => {
    loading.value = true;
    if (password.value !== confirmPassword.value) {
        toastr.error("Passwords do not match!");
        loading.value = false;
        return;
    }
    axios
        .post("/api/create/user", {
            email: email.value,
            password: password.value,
        })
        .then((response) => {
            loading.value = false;
            toastr.success(response.data.message);
            router.push("/dashboard");
        })
        .catch((error) => {
            loading.value = false;
            if (error.response.status === 422) {
                toastr.error(error.response.data.message);
            }
        });
};
</script>

<template>
    <div
        class="ring-2 h-screen 1200px:p-20 1200px:pl-60 1200px:pr-60 bg-zinc-500 overflow-auto"
    >
        <div class="700px:grid 700px:grid-cols-2 h-full bg-white">
            <div class="700px:col-span-1 bg-white pt-10 pb-10">
                <div class="flex justify-center">
                    <img class="h-20" :src="logo" />
                </div>

                <div class="flex justify-center">
                    <form>
                        <p class="text-center mt-10 text-lg text-slate-700">
                            Welcome to Create Test
                        </p>
                        <div class="mt-10">
                            <div>
                                <label class="text-slate-700 text-sm"
                                    >Email address</label
                                >
                                <input
                                    v-model="email"
                                    type="email"
                                    class="ring-1 ring-slate-300 w-full p-1 focus:outline-none mt-1"
                                    placeholder="email@email.com"
                                />
                                <p
                                    v-if="email === ''"
                                    class="text-xs mt-1 text-red-500"
                                >
                                    required
                                </p>
                            </div>
                            <div class="mt-4">
                                <label class="text-slate-700 text-sm"
                                    >Password</label
                                >
                                <input
                                    class="ring-1 ring-slate-300 w-full p-1 focus:outline-none mt-1"
                                    type="password"
                                    v-model="password"
                                    placeholder="Example123!"
                                />
                                <p
                                    v-if="password === ''"
                                    class="text-xs mt-1 text-red-500"
                                >
                                    required
                                </p>
                            </div>
                            <div class="mt-4">
                                <label class="text-slate-700 text-sm"
                                    >Validate Password</label
                                >
                                <input
                                    class="ring-1 ring-slate-300 w-full p-1 focus:outline-none mt-1"
                                    type="password"
                                    v-model="confirmPassword"
                                    placeholder="Example123!"
                                />
                                <p
                                    v-if="confirmPassword === ''"
                                    class="text-xs mt-1 text-red-500"
                                >
                                    required
                                </p>
                            </div>
                            <div class="flex justify-center mt-4">
                                <button
                                    v-if="!loading"
                                    @click.prevent="handleSubmit"
                                    class="bg-slate-500 p-10 pt-2 pb-2 rounded-3xl text-white mt-10"
                                >
                                    Sign up
                                </button>
                                <button
                                    v-if="loading"
                                    class="bg-slate-500 p-10 pt-2 pb-2 rounded-3xl text-white mt-10"
                                >
                                    <i
                                        class="fa-solid fa-spinner animate-spin"
                                    ></i>
                                    Creating account...
                                </button>
                            </div>
                            <div
                                class="border-t flex items-center justify-center mt-10"
                            >
                                <p
                                    class="text-center mt-5 text-sm text-slate-900"
                                >
                                    Already have an account?
                                </p>
                                <router-link
                                    :to="'/user/signin'"
                                    class="mt-5 text-sm ml-1 text-blue-500 hover:text-blue-600"
                                    >Sign in</router-link
                                >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="hidden 700px:block 700px:col-span-1 bg-green-300">
                <div class="flex justify-center h-500px">
                    <img :src="loginLogo" class="object-contain" />
                </div>
                <div class="p-10 -mt-40">
                    <p class="text-center mt-2 text-slate-700">
                        Unlock the power of seamless test paper creation!
                        Whether you're an educator, a student, or a professional
                        looking to assess knowledge, Create Test is your
                        ultimate solution.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
