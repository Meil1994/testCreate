<script>
import { onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import { useToastr } from "../toaster.js";
import Nav from "./components/Nav.vue";
import BurgerNav from "./components/BurgerNav.vue";
import PageLoading from "./components/PageLoading.vue";

const toastr = useToastr();

export default {
    components: {
        Nav,
        BurgerNav,
        PageLoading,
    },
    setup() {
        const route = useRoute();
        const responders = ref([]);
        const questions = ref([]);
        const allData = ref({
            responders: [],
            questions: [],
        });
        const pageLoad = ref(false);

        const getRespondertDetails = (id) => {
            pageLoad.value = true;
            axios
                .get(`/api/get/individual/${id}`)
                .then((response) => {
                    responders.value = response.data;

                    const testId = response.data.test_id;
                    getQuestionstDetails(testId);

                    allData.value.responders.push(responders.value.responds);
                    pageLoad.value = false;
                })
                .catch((error) => {
                    console.error("Error fetching responder details:", error);
                    pageLoad.value = false;
                });
        };

        const getQuestionstDetails = (testId) => {
            axios
                .get(`/api/get/tests/${testId}`)
                .then((response) => {
                    questions.value = response.data;
                    //console.log(questions.value);
                    allData.value.questions.push(questions.value);
                })
                .catch((error) => {
                    console.error("Error fetching question details:", error);
                });
        };

        const getLetter = (ansIndex) => {
            return String.fromCharCode(65 + ansIndex);
        };

        const countScore = (allData) => {
            let score = 0;
            for (const responder of allData.responders) {
                for (const answer of responder) {
                    for (const data of allData.questions) {
                        for (const set of data.sets) {
                            for (const question of set.questions) {
                                for (const questionAnswer of question.answers) {
                                    const pairAnswer =
                                        answer.answer === questionAnswer.id;
                                    if (pairAnswer) {
                                        if (questionAnswer.is_correct) {
                                            score += 1;
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

        const status = ref([
            {
                name: "Uncheck",
                value: 0,
            },
            {
                name: "Checked",
                value: 1,
            },
        ]);

        const changeStatus = (responders, status) => {
            axios
                .put(`/api/responder/${responders.id}/change-status`, {
                    status: status,
                })
                .then((response) => {
                    toastr.success(response.data.message);
                });
        };

        onMounted(() => {
            const id = route.params.id;
            getRespondertDetails(id);
        });

        return {
            responders,
            questions,
            getRespondertDetails,
            getLetter,
            getQuestionstDetails,
            allData,
            countScore,
            status,
            changeStatus,
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
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-500">
                            Test Name : {{ questions.test_name }}
                        </p>
                    </div>
                    <div
                        class="bg-green-500 h-10 flex p-2 text-slate-600 rounded-md"
                    >
                        <select
                            class="focus:outline-none bg-green-500 text-white"
                            @change="
                                changeStatus(responders, $event.target.value)
                            "
                        >
                            <option
                                v-for="stat in status"
                                :value="stat.value"
                                :selected="responders.status === stat.value"
                            >
                                {{ stat.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-between">
                    <p class="text-slate-500 mt-4">
                        Total Score : {{ countScore(allData) }}
                    </p>
                    <p class="text-slate-500 mt-4">
                        Passing Score : {{ questions.passing_score }}
                    </p>
                </div>

                <div class="rounded-md mt-6 ring-1 ring-slate-300 shadow-xl">
                    <div
                        class="1018px:flex justify-between p-10 border-b border-slate-300"
                    >
                        <div class="flex items-center text-slate-600">
                            <p class="w-90px">First Name</p>
                            <p
                                class="ring-1 ring-slate-300 text-black ml-2 w-60 p-1 pl-2 pr-2 rounded-md"
                            >
                                {{ responders.first_name }}
                            </p>
                        </div>
                        <div
                            class="flex items-center text-slate-600 mt-4 1018pxmt-0"
                        >
                            <p class="w-90px">Last Name</p>
                            <p
                                class="ring-1 ring-slate-300 text-black ml-2 w-60 p-1 pr-1 pl-2 rounded-md"
                            >
                                {{ responders.last_name }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="border-b border-slate-300 p-10 pt-0"
                        v-for="(set, setIndex) in questions.sets"
                    >
                        <p class="text-slate-800 mt-10 ml-2">
                            Part {{ setIndex + 1 }}
                        </p>
                        <div
                            class="ring-1 ring-slate-300 p-5 rounded-md text-cyan-900"
                        >
                            <p>
                                Instructions :
                                {{ set.instructions }}
                            </p>
                        </div>
                        <div v-for="(question, quesIndex) in set.questions">
                            <div>
                                <div class="text-slate-600 mt-10">
                                    <p class="mb-3 text-cyan-800">
                                        {{ quesIndex + 1 }}.
                                        {{ question.question }}
                                    </p>

                                    <div class="mt-5">
                                        <div
                                            class="ml-4 mt-1"
                                            v-for="(
                                                answer, ansIndex
                                            ) in question.answers"
                                        >
                                            <div
                                                v-for="answerData in allData.responders"
                                            >
                                                <div
                                                    v-for="selectedAnswer in answerData"
                                                >
                                                    <p
                                                        :class="{
                                                            'text-red-500':
                                                                !answer.is_correct,
                                                        }"
                                                        class="text-green-500 text-sm mr-2 mb-1"
                                                        type="checkbox"
                                                        v-if="
                                                            answer.id ==
                                                            selectedAnswer.answer
                                                        "
                                                    >
                                                        selected answer
                                                        <span
                                                            v-if="
                                                                !answer.is_correct
                                                            "
                                                            >( incorrect )</span
                                                        ><span
                                                            v-if="
                                                                answer.is_correct
                                                            "
                                                            >( correct )</span
                                                        >
                                                    </p>
                                                </div>
                                                <div
                                                    class="ring-1 ring-slate-300 rounded-md flex justify-between p-1 pl-2 pr-2 mb-4"
                                                >
                                                    <p>
                                                        {{
                                                            getLetter(ansIndex)
                                                        }}.
                                                        {{ answer.answer }}
                                                    </p>
                                                    <p
                                                        v-if="answer.is_correct"
                                                        class="text-sm text-sky-500"
                                                    >
                                                        (correct answer)
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
