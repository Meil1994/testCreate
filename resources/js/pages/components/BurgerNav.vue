<script setup>
import logo from "../../../../public/images/logo.png";
import { ref } from "vue";
import { useToastr } from "../../toaster.js";
import { useRouter } from "vue-router";

const showMenuConfirmation = ref(false);
const showLogoutConfirmation = ref(false);
const exitMenu = ref(false);
const toastr = useToastr();
const router = useRouter();
const loading = ref(false);

const showMenu = () => {
    showMenuConfirmation.value = true;
};

const hideMenu = () => {
    showMenuConfirmation.value = false;
    exitMenu.value = true;
    setTimeout(() => {
        exitMenu.value = false;
    }, 500);
};

const showLogout = () => {
    showLogoutConfirmation.value = true;
};

const hideLogout = () => {
    showLogoutConfirmation.value = false;
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
</script>
<template>
    <div>
        <div
            v-if="showMenuConfirmation"
            class="flex 700px:hidden slide-enter absolute h-screen w-full bg-slate-500/80"
        >
            <div
                v-if="showLogoutConfirmation"
                class="absolute h-screen w-100% overflow-auto"
            >
                <div
                    class="bg-slate-500/90 flex justify-center items-center h-screen"
                >
                    <div class="bg-white rounded-md h-80 w-80">
                        <div class="flex justify-end bg-red-500 p-4 rounded-t">
                            <button
                                class="text-white text-2xl"
                                @click.prevent="hideLogout"
                            >
                                <i
                                    class="fa-regular fa-rectangle-xmark hover:text-slate-300"
                                ></i>
                            </button>
                        </div>
                        <div class="mt-7 p-6 pt-0">
                            <p class="text-red-500 text-center text-4xl mb-2">
                                <i
                                    class="fa-solid fa-arrow-right-from-bracket"
                                ></i>
                            </p>
                            <p class="text-center">
                                Are you sure you want to logout from your
                                account?
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
                                    v-if="loading"
                                    type="button"
                                    class="bg-red-500 p-2 w-40 text-white rounded-md"
                                >
                                    <i
                                        class="fa-solid fa-spinner animate-spin"
                                    ></i>
                                    Loading...
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-20%">
                <div class="flex justify-center pr-2 pt-10 text-white text-2xl">
                    <button @click.prevent="hideMenu">
                        <i class="fa-regular fa-rectangle-xmark"></i>
                    </button>
                </div>
            </div>
            <div class="bg-slate-500 w-80% overflow-auto">
                <div class="p-10 text-white">
                    <div>
                        <img class="h-14" :src="logo" />
                    </div>
                    <div class="pl-5 mt-5">
                        <div>
                            <a href="/">
                                <i class="fa-solid fa-note-sticky mr-2"></i
                                >Test</a
                            >
                        </div>
                        <div class="mt-4">
                            <a href="/test/taker"
                                ><i class="fa-solid fa-user mr-1"></i> Test
                                Taker</a
                            >
                        </div>
                        <div class="mt-4">
                            <a href="/chart"
                                ><i
                                    class="fa-solid fa-square-poll-vertical mr-1"
                                ></i>
                                Chart</a
                            >
                        </div>
                        <div class="mt-4">
                            <a href="/my/account"
                                ><i class="fa-solid fa-gear mr-1"></i>
                                Account</a
                            >
                        </div>
                        <div class="mt-10">
                            <button @click.prevent="showLogout">
                                <i
                                    class="fa-solid fa-arrow-right-from-bracket mr-2"
                                ></i
                                >Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-else
            v-if="exitMenu"
            class="slide-exit absolute w-full h-screen bg-slate-500"
        ></div>

        <div
            class="700px:hidden flex justify-between items-center p-5 600px:p-10 600px:pl-8 600px:pb-5 600px:pt-5 border-b"
        >
            <img class="h-14" :src="logo" />
            <div>
                <button @click.prevent="showMenu">
                    <i class="fa-solid fa-bars text-xl text-slate-500"></i>
                </button>
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
