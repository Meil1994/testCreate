<script setup>
import { onMounted, ref, watch } from "vue";
import axios from "axios";
import { useToastr } from "../toaster.js";
import { TailwindPagination } from "laravel-vue-pagination";
import Nav from "./components/Nav.vue";
import BurgerNav from "./components/BurgerNav.vue";
import PageLoading from "./components/PageLoading.vue";

const toastr = useToastr();
const tests = ref({ data: [] });
const showDeleteConfirmation = ref(false);
const selectedDeleteIndex = ref(null);
const takers = ref([]);
const searchQuery = ref(null);
const totalRespondents = ref([]);
const loading = ref(false);
const pageLoad = ref(false);

const getTest = (page = 1) => {
    pageLoad.value = true;
    axios.get(`/api/tests?page=${page}`).then((response) => {
        tests.value = response.data;
        pageLoad.value = false;
    });
};

const copyTestLink = (id) => {
    const testLink = `http://127.0.0.1:8000/api/test/number/${id}`;
    navigator.clipboard
        .writeText("")
        .then(() => {
            const textArea = document.createElement("textarea");
            textArea.value = testLink;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);
            toastr.success("Test link copied to clipboard");
        })
        .catch((error) => {
            console.error("Error clearing clipboard:", error);
        });
};

const formatDate = (dateString) => {
    const date = new Date(dateString);

    return date.toLocaleDateString("en-US", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
    });
};

const status = ref([
    {
        name: "Ongoing",
        value: 1,
    },
    {
        name: "Completed",
        value: 0,
    },
]);

const changeStatus = (tests, status) => {
    axios
        .put(`/api/tests/${tests.id}/change-status`, {
            status: status,
        })
        .then((response) => {
            toastr.success(response.data.message);
        });
};

const search = () => {
    axios
        .get("/api/users/search", {
            params: {
                query: searchQuery.value,
            },
        })
        .then((response) => {
            tests.value = [];
            tests.value = response.data;
        })
        .catch((error) => {
            console.log(error);
        });
};

const deleteTest = () => {
    const testId = selectedDeleteIndex.value;
    loading.value = true;

    axios
        .post(`/api/delete/test/${testId}`)
        .then((response) => {
            loading.value = false;
            toastr.success(response.data.message);
            hideDeleteForm();
            const index = tests.value.data.findIndex(
                (test) => test.id === testId
            );

            if (index !== -1) {
                tests.value.data.splice(index, 1);
            }
        })
        .catch((error) => {
            loading.value = false;
            console.error("Error deleting test:", error);
        });
};

const showDeleteForm = (index) => {
    selectedDeleteIndex.value = index;
    showDeleteConfirmation.value = true;
};

const hideDeleteForm = () => {
    showDeleteConfirmation.value = false;
};

const getTestTaker = () => {
    axios.get("/api/get/results/taker").then((response) => {
        takers.value = response.data;
    });
};

const getTotalTestTaker = (testID) => {
    let takerCount = 0;

    for (const taker of takers.value) {
        if (taker.test_id === testID) {
            takerCount++;
        }
    }

    return takerCount;
};

watch(searchQuery, () => {
    search();
});

onMounted(() => {
    getTest();
    getTestTaker();
});
</script>

<template>
    <div class="700px:flex">
        <div
            v-if="showDeleteConfirmation"
            class="absolute h-screen w-100% overflow-auto z-40"
        >
            <div
                class="bg-slate-500/90 flex justify-center items-center h-screen"
            >
                <div class="bg-white rounded-md w-80 pb-5">
                    <div class="flex justify-end bg-red-500 p-4 rounded-t">
                        <button
                            class="text-white text-2xl"
                            @click.prevent="hideDeleteForm"
                        >
                            <i
                                class="fa-regular fa-rectangle-xmark hover:text-slate-300"
                            ></i>
                        </button>
                    </div>
                    <div class="mt-7 p-6 pt-0">
                        <p class="text-red-500 text-center text-4xl mb-2">
                            <i class="fa-regular fa-trash-can"></i>
                        </p>
                        <p class="text-center mt-5">
                            All details related to this test will be deleted.
                            Are you sure you want to delete the selected test?
                        </p>
                        <div class="flex justify-center mt-10">
                            <button
                                v-if="!loading"
                                @click.prevent="deleteTest()"
                                class="bg-red-500 p-2 w-40 text-white rounded-md hover:bg-red-600"
                            >
                                Delete
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

        <Nav />
        <div class="flex-grow h-screen overflow-auto">
            <BurgerNav />
            <div v-if="pageLoad">
                <PageLoading />
            </div>
            <div v-if="!pageLoad" class="p-10 pt-6">
                <div class="flex justify-between items-center">
                    <h4>
                        My Test
                        <span class="text-slate-500"
                            >({{ tests.data.length }})</span
                        >
                    </h4>
                    <router-link
                        to="/new/test"
                        class="bg-green-500 hover:bg-green-600 p-8 rounded-md pt-2 pb-2 text-white"
                        ><i class="fa-solid fa-square-plus mr-2"></i> Add
                        Test</router-link
                    >
                </div>
                <div>
                    <input
                        v-model="searchQuery"
                        placeholder="Search..."
                        class="border border-slate-300 rounded-md p-2 focus:outline-none w-full mt-10"
                    />
                </div>

                <div
                    v-if="tests.data.length > 0"
                    class="mt-6 overflow-auto 1122px:overflow-visible"
                >
                    <table
                        class="m-1 w-900px 1076px:w-850px 1122px:w-full shadow-md ring-1 ring-slate-300 rounded-md bg-slate-500"
                    >
                        <thead>
                            <tr class="border-b border-slate-300 text-white">
                                <th class="text-start p-3">#</th>
                                <th class="text-start p-3">Test Link</th>
                                <th class="text-start p-3">Test Name</th>
                                <th
                                    class="text-center p-3 ring-1 ring-slate-300"
                                >
                                    Respondents
                                </th>
                                <th class="text-start p-3 pl-6">Created at</th>
                                <th class="text-start p-3">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody
                            v-for="(test, index) in tests.data"
                            :key="test.id"
                            class="bg-white"
                        >
                            <tr class="border-b border-slate-300">
                                <td class="text-start p-3 text-slate-600">
                                    {{ index + 1 }}
                                </td>
                                <td class="p-3">
                                    <button
                                        @click.prevent="copyTestLink(test.id)"
                                        class="text-blue-600 text-sm"
                                    >
                                        Copy
                                    </button>
                                </td>
                                <td class="text-slate-600 p-3">
                                    <p class="overflow-auto">
                                        {{ test.test_name }}
                                    </p>
                                </td>
                                <td
                                    class="text-center text-slate-600 ring-1 ring-slate-300"
                                >
                                    {{ getTotalTestTaker(test.id) }}
                                </td>
                                <td class="text-slate-600 p-3 pl-6">
                                    {{ formatDate(test.created_at) }}
                                </td>
                                <td class="flex p-3 text-slate-600">
                                    <div
                                        class="ring-1 ring-slate-300 rounded-md p-1"
                                    >
                                        <select
                                            @change="
                                                changeStatus(
                                                    test,
                                                    $event.target.value
                                                )
                                            "
                                            class="focus:outline-none"
                                        >
                                            <option
                                                v-for="stat in status"
                                                :value="stat.value"
                                                :selected="
                                                    test.status === stat.value
                                                "
                                            >
                                                {{ stat.name }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        <router-link
                                            :to="'/edit/' + test.id"
                                            class="text-blue-700"
                                            ><i
                                                class="fa-regular fa-pen-to-square"
                                            ></i
                                        ></router-link>
                                        <router-link
                                            :to="
                                                '/individual/test/responders/' +
                                                test.id
                                            "
                                            class="text-slate-600 hover:text-slate-700 ml-2"
                                            ><i class="fa-solid fa-eye"></i
                                        ></router-link>
                                        <button
                                            @click.prevent="
                                                showDeleteForm(test.id)
                                            "
                                            class="ml-2 text-red-700"
                                        >
                                            <i
                                                class="fa-regular fa-trash-can"
                                            ></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else>
                    <p class="text-red-500 ml-4 mt-6">No data available</p>
                </div>

                <TailwindPagination
                    class="flex items-center paginate mt-10"
                    :data="tests"
                    @pagination-change-page="getTest"
                />
            </div>
        </div>
    </div>
</template>
