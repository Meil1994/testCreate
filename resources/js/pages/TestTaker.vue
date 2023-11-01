<script>
import axios from "axios";
import { ref, onMounted, computed, watch } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import Nav from "./components/Nav.vue";
import BurgerNav from "./components/BurgerNav.vue";
import PageLoading from "./components/PageLoading.vue";

export default {
    components: {
        TailwindPagination,
        Nav,
        BurgerNav,
        PageLoading,
    },
    setup() {
        const testTakers = ref({ data: [] });
        const tests = ref([]);
        const pageLoad = ref(false);

        const getTestTakers = (page = 1) => {
            pageLoad.value = true;
            axios
                .get(`/api/get/test/takers?page=${page}`)
                .then((response) => {
                    testTakers.value = response.data;
                    pageLoad.value = false;
                })
                .catch((error) => {
                    console.error("Error fetching test details:", error);
                    pageLoad.value = false;
                });
        };

        const getTests = () => {
            axios
                .get(`/api/get/test`)
                .then((response) => {
                    tests.value = response.data;
                })
                .catch((error) => {
                    console.error("Error fetching test details:", error);
                });
        };

        const getTestName = (takerTestId) => {
            for (const test of tests.value) {
                if (test.id === takerTestId) {
                    return test.test_name;
                }
            }
            return "";
        };

        const getTotalScore = (testTakerId) => {
            let score = 0;
            for (const taker of testTakers.value.data) {
                for (const takerAnswer of taker.responds) {
                    for (const test of tests.value) {
                        for (const set of test.sets) {
                            for (const question of set.questions) {
                                for (const answer of question.answers) {
                                    if (taker.id === testTakerId) {
                                        if (answer.id === takerAnswer.answer) {
                                            if (answer.is_correct) {
                                                score += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return score;
        };

        const getRemarks = (testTakerId) => {
            let remarks = "";
            let score = 0;
            for (const taker of testTakers.value.data) {
                for (const takerAnswer of taker.responds) {
                    for (const test of tests.value) {
                        for (const set of test.sets) {
                            for (const question of set.questions) {
                                for (const answer of question.answers) {
                                    if (taker.id === testTakerId) {
                                        if (answer.id === takerAnswer.answer) {
                                            if (answer.is_correct) {
                                                score++;
                                                if (
                                                    score >= test.passing_score
                                                ) {
                                                    remarks = "Pass";
                                                }
                                            }

                                            if (!answer.is_correct) {
                                                score++;
                                                if (
                                                    score < test.passing_score
                                                ) {
                                                    remarks = "Fail";
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return remarks;
        };

        const searchQuery = ref(null);
        const search = () => {
            axios
                .get("/api/test/takers/search", {
                    params: {
                        query: searchQuery.value,
                    },
                })
                .then((response) => {
                    testTakers.value = [];
                    testTakers.value = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        };

        watch(searchQuery, () => {
            search();
        });

        onMounted(() => {
            getTestTakers();
            getTests();
        });

        return {
            testTakers,
            tests,
            getTestTakers,
            getTestName,
            getTotalScore,
            searchQuery,
            search,
            getRemarks,
            pageLoad,
        };
    },
};
</script>

<template>
    <div class="700px:flex">
        <Nav />
        <div class="flex-grow h-screen overflow-auto">
            <BurgerNav />
            <div v-if="pageLoad">
                <PageLoading />
            </div>
            <div v-if="!pageLoad" class="p-10 pt-8">
                <div>
                    <h1>
                        Test Takers
                        <span class="text-slate-500"
                            >({{ testTakers.data.length }})</span
                        >
                    </h1>
                </div>
                <div>
                    <input
                        v-model="searchQuery"
                        placeholder="Search test taker here..."
                        class="border border-slate-300 rounded-md p-2 focus:outline-none w-full mt-10"
                    />
                </div>
                <div
                    v-if="testTakers.data.length > 0"
                    class="overflow-auto 1122px:overflow-visible"
                >
                    <table
                        class="w-900px 1076px:w-850px 1122px:w-full shadow-md ring-1 ring-slate-300 rounded-md bg-slate-500 mt-6"
                    >
                        <thead>
                            <tr class="border-b border-slate-300 text-white">
                                <th class="text-start p-3">First Name</th>
                                <th class="text-start p-3">Last Name</th>
                                <th class="text-start p-3">Test Name</th>
                                <th class="text-center p-3">Score</th>
                                <th
                                    class="text-center p-3 border-l border-r border-slate-300"
                                >
                                    Remarks
                                </th>
                                <th class="text-start p-3 pl-5">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody
                            v-for="testTaker in testTakers.data"
                            class="bg-white"
                        >
                            <tr class="border-b border-slate-300">
                                <td class="text-slate-600 p-3">
                                    {{ testTaker.first_name }}
                                </td>
                                <td class="text-slate-600 p-3">
                                    {{ testTaker.last_name }}
                                </td>
                                <td class="p-3 text-start">
                                    <a
                                        class="text-slate-600 hover:text-blue-500"
                                        >{{ getTestName(testTaker.test_id) }}</a
                                    >
                                </td>
                                <td class="text-center text-slate-600">
                                    {{ getTotalScore(testTaker.id) }}
                                </td>
                                <td
                                    v-if="getRemarks(testTaker.id) === 'Pass'"
                                    class="text-center bg-green-500 text-white border-l border-r border-slate-300"
                                >
                                    {{ getRemarks(testTaker.id) }}
                                </td>
                                <td
                                    v-if="getRemarks(testTaker.id) === 'Fail'"
                                    class="text-center bg-red-500 text-white border-l border-r border-slate-300"
                                >
                                    {{ getRemarks(testTaker.id) }}
                                </td>
                                <th class="pl-5 text-start">
                                    <p
                                        v-if="testTaker.status === 0"
                                        class="text-red-500"
                                    >
                                        Uncheck
                                    </p>
                                    <p
                                        v-if="testTaker.status === 1"
                                        class="text-slate-500"
                                    >
                                        Checked
                                    </p>
                                </th>
                                <td class="p-3">
                                    <router-link
                                        :to="'/api/reponse/' + testTaker.id"
                                        class="text-slate-600 hover:text-blue-600"
                                        ><i class="fa-solid fa-eye"></i
                                    ></router-link>
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
                    :data="testTakers"
                    @pagination-change-page="getTestTakers"
                />
            </div>
        </div>
    </div>
</template>
