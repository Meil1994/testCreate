<script setup>
import Nav from "./components/Nav.vue";
import BurgerNav from "./components/BurgerNav.vue";
import { ref, onMounted } from "vue";
import { useToastr } from "../toaster.js";
import Notes from "./components/account/Notes.vue";
import PageLoading from "./components/PageLoading.vue";

const toastr = useToastr();
const loading = ref(false);
const pageLoad = ref(false);

const user = ref({
    email: "",
    password: "",
});

const getUser = () => {
    pageLoad.value = true;
    axios.get("/api/get/user").then((response) => {
        user.value = response.data;
        pageLoad.value = false;
    });
};

const updateUser = () => {
    loading.value = true;
    axios
        .put("/api/update/target/user", user.value)
        .then((response) => {
            loading.value = false;
            toastr.success(response.data.message);
        })
        .catch((error) => {
            loading.value = false;
            toastr.error(error.response.data.message);
        });
};

onMounted(() => {
    getUser();
});
</script>
<template>
    <div class="700px:flex">
        <Nav />
        <div class="flex-grow h-screen">
            <BurgerNav />
            <div v-if="pageLoad">
                <PageLoading />
            </div>
            <div
                v-if="!pageLoad"
                class="874px:grid 874px:grid-cols-2 h-screen overflow-auto"
            >
                <div
                    class="col-span-1 p-10 pt-8 874px:h-screen 874px:overflow-auto"
                >
                    <div>
                        <p>My Account</p>
                    </div>
                    <div class="mt-10">
                        <form
                            class="h-80 p-10 bg-white ring-1 ring-slate-300 rounded-md"
                        >
                            <div>
                                <label class="text-slate-700 text-sm"
                                    >Email address</label
                                >
                                <input
                                    v-model="user.email"
                                    type="email"
                                    class="ring-1 ring-slate-300 w-full p-1 rounded-md mt-1 focus:outline-none pl-2 pr-2"
                                />
                            </div>

                            <div class="mt-5">
                                <label class="text-slate-700 text-sm"
                                    >Password</label
                                >
                                <input
                                    v-model="user.password"
                                    type="password"
                                    class="ring-1 ring-slate-300 w-full p-1 rounded-md mt-1 focus:outline-none pl-2 pr-2"
                                />
                            </div>

                            <div class="mt-10">
                                <button
                                    v-if="!loading"
                                    @click.prevent="updateUser"
                                    class="bg-blue-500 p-10 pt-2 pb-2 text-white rounded-md hover:bg-blue-600"
                                >
                                    Update
                                </button>
                                <button
                                    type="button"
                                    v-if="loading"
                                    class="bg-blue-500 p-4 pt-2 pb-2 text-white rounded-md"
                                >
                                    <i
                                        class="fa-solid fa-spinner animate-spin"
                                    ></i>
                                    Loading...
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-span-1">
                    <Notes />
                </div>
            </div>
        </div>
    </div>
</template>
