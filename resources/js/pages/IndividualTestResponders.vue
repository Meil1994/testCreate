<script setup>
import Nav from "./components/Nav.vue";
import BurgerNav from "./components/BurgerNav.vue";
import { ref, onMounted, computed, watch } from "vue";
import { useRoute } from "vue-router";
import { useToastr } from "../toaster.js";
import PageLoading from "./components/PageLoading.vue";

const tests = ref([]);
const responderCount = ref([]);
const route = useRoute();
const toastr = useToastr();
const searchQuery = ref(null);
const pageLoad = ref(false);

const getTestDetails = (id) => {
    pageLoad.value = true;
    axios
        .get(`/api/individual/test/${id}`)
        .then((response) => {
            tests.value = response.data;
            responderCount.value = tests.value.responders;
            pageLoad.value = false;
        })
        .catch((error) => {
            console.error("Error fetching test details:", error);
            pageLoad.value = false;
        });
};

const getScore = (responderId) => {
    let score = 0;
    for (const set of tests.value.sets) {
        for (const question of set.questions) {
            for (const answer of question.answers) {
                for (const responder of responderCount.value) {
                    for (const respond of responder.responds) {
                        if (respond.answer === answer.id) {
                            if (answer.is_correct) {
                                if (responderId === responder.id) {
                                    score++;
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

const finalRemarks = (responderId) => {
    let score = 0;
    let remarks = "";
    const passingScore = tests.value.passing_score;

    for (const set of tests.value.sets) {
        for (const question of set.questions) {
            for (const answer of question.answers) {
                for (const responder of responderCount.value) {
                    for (const respond of responder.responds) {
                        if (respond.answer === answer.id) {
                            if (answer.is_correct) {
                                if (responderId === responder.id) {
                                    score++;
                                    if (score >= passingScore) {
                                        remarks = "Pass";
                                    }
                                }
                            }
                            if (!answer.is_correct) {
                                if (responderId === responder.id) {
                                    score++;
                                    if (score < passingScore) {
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
    return remarks;
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

onMounted(() => {
    const id = route.params.id;
    getTestDetails(id);
});
</script>

<template>
    <div class="700px:flex">
        <Nav />
        <div class="flex-grow h-screen overflow-auto">
            <BurgerNav />
            <div v-if="pageLoad">
                <PageLoading />
            </div>
            <div v-if="!pageLoad">
                <div class="flex justify-between items-center p-10 pb-0 pt-8">
                    <div>
                        <p>
                            <span class="text-slate-500">Test Name</span> :
                            {{ tests.test_name }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <p class="text-slate-500">Status :</p>
                        <select
                            @change="changeStatus(tests, $event.target.value)"
                            class="focus:outline-none ring-1 ring-slate-300 rounded-md p-1 ml-2"
                        >
                            <option
                                v-for="stat in status"
                                :value="stat.value"
                                :selected="tests.status === stat.value"
                            >
                                {{ stat.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="p-10 pb-0 pt-2">
                    <p>
                        <span class="text-slate-500">Passing Score : </span>
                        {{ tests.passing_score }}
                    </p>
                </div>

                <div class="p-10 pt-5">
                    <div class="overflow-auto 1122px:overflow-visible">
                        <table
                            v-if="responderCount.length > 0"
                            class="w-900px 1076px:w-850px 1122px:w-full shadow-md ring-1 ring-slate-300 rounded-md bg-slate-500"
                        >
                            <thead>
                                <tr
                                    class="border-b border-slate-300 text-white"
                                >
                                    <th class="text-start p-3">First Name</th>
                                    <th class="text-start p-3">Last Name</th>
                                    <th class="text-start p-3">Status</th>
                                    <th class="text-center p-3">Score</th>
                                    <th
                                        class="text-center p-3 border-l border-r border-slate-300"
                                    >
                                        Remarks
                                    </th>
                                    <th class="text-start p-3"></th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white"
                                v-for="responder in tests.responders"
                            >
                                <tr class="border-b border-slate-300">
                                    <td class="text-start p-3 text-slate-600">
                                        {{ responder.first_name }}
                                    </td>
                                    <td class="text-start p-3 text-slate-600">
                                        {{ responder.last_name }}
                                    </td>
                                    <td class="text-start p-3 text-slate-600">
                                        <p v-if="responder.status === 1">
                                            Checked
                                        </p>
                                        <p v-if="responder.status === 0">
                                            Uncheck
                                        </p>
                                    </td>
                                    <td class="text-center text-slate-600">
                                        {{ getScore(responder.id) }}
                                    </td>
                                    <td
                                        v-if="
                                            finalRemarks(responder.id) ===
                                            'Pass'
                                        "
                                        class="text-center bg-green-500 border-l border-r border-slate-300 text-white"
                                    >
                                        {{ finalRemarks(responder.id) }}
                                    </td>
                                    <td
                                        v-if="
                                            finalRemarks(responder.id) ===
                                            'Fail'
                                        "
                                        class="text-center bg-red-500 border-l border-r border-slate-300 text-white"
                                    >
                                        {{ finalRemarks(responder.id) }}
                                    </td>
                                    <td class="p-3 text-center">
                                        <router-link
                                            :to="'/api/reponse/' + responder.id"
                                            class="text-slate-600 hover:text-blue-600"
                                            ><i class="fa-solid fa-eye"></i
                                        ></router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div
                            v-else
                            class="ring-1 ring-slate-300 m-1 p-3 rounded-md"
                        >
                            <p class="text-red-500">
                                No responder for this test yet.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
