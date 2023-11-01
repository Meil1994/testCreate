<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import logo from "../../../public/images/logo.png";
import confetti from "../../../public/images/confetti.png";
import { useToastr } from "../toaster.js";
import { useRouter } from "vue-router";

const user = ref([]);
const toastr = useToastr();
const loading = ref(false);

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

onMounted(() => {
    getUser();
});
</script>
<template>
    <div>
        <div class="w-full h-screen absolute overflow-auto bg-white z-40">
            <div class="flex justify-center">
                <img class="h-28" :src="logo" />
            </div>
            <div class="flex justify-center mt-28">
                <div class="w-500px">
                    <div class="flex justify-center">
                        <img class="h-28" :src="confetti" />
                    </div>
                    <div class="text-center mt-5 text-2xl">
                        <p>
                            Hooray! Your email address has been successfully
                            validated.
                        </p>
                    </div>
                    <div class="text-center mt-10 text-sm">
                        <p>
                            Welcome aboard! You're ready to embark on your
                            journey with us. Begin by creating and sharing your
                            very first test.
                        </p>
                    </div>

                    <div class="flex justify-center mt-10">
                        <router-link
                            to="/dashboard"
                            class="bg-green-500 p-2 pl-5 pr-5 text-white rounded-md hover:bg-green-600"
                        >
                            <i class="fa-solid fa-house"></i> Go to Home Page
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
