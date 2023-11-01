<script setup>
import { onMounted, ref, watch } from "vue";
import logo from "../../../public/images/logo.png";
import { useToastr } from "../toaster.js";
import { useRouter } from "vue-router";

const token = ref(document.getElementById("app").getAttribute("data-token"));
const loading = ref(false);
const password = ref("");
const confirmPassword = ref("");
const toastr = useToastr();
const router = useRouter();

const updatePassword = () => {
    loading.value = true;
    if (password.value !== confirmPassword.value) {
        toastr.error("Passwords do not match!");
        loading.value = false;
        return;
    }
    axios
        .post(`/api/update/password/${token.value}`, {
            password: password.value,
        })
        .then((response) => {
            loading.value = false;
            toastr.success(response.data.message);
            router.push("/user/signin");
        })
        .catch((error) => {
            loading.value = false;
            toastr.error("Please check the password!");
        });
};
</script>
<template>
    <div>
        <div class="flex justify-center">
            <img class="h-16" :src="logo" />
        </div>
        <form class="flex justify-center">
            <div class="ring-1 ring-slate-300 shadow-xl rounded-md w-80 mt-10">
                <div
                    class="bg-green-500 rounded-t-md p-5 text-center text-white"
                >
                    <p>Update Password Form</p>
                </div>
                <div class="p-10">
                    <p class="text-slate-600 mb-1">Password</p>
                    <input
                        type="password"
                        v-model="password"
                        class="ring-1 ring-slate-300 w-full p-2 rounded-md focus:outline-none"
                    />
                    <p class="text-slate-600 mt-5 mb-1">Confirm Password</p>
                    <input
                        type="password"
                        v-model="confirmPassword"
                        class="ring-1 ring-slate-300 w-full p-2 rounded-md focus:outline-none"
                    />
                    <div class="flex justify-center mt-10">
                        <button
                            v-if="!loading"
                            @click.prevent="updatePassword"
                            class="bg-blue-500 p-2 rounded-md text-white hover:bg-blue-600"
                        >
                            Update Password
                        </button>
                        <button
                            v-if="loading"
                            class="bg-blue-500 p-2 pl-4 pr-4 rounded-md text-white"
                        >
                            Loading...
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
