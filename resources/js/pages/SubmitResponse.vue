<script setup>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import logo from "../../../public/images/logo.png";
import { useToastr } from "../toaster.js";
import Loading from "./components/Loading.vue";

const toastr = useToastr();

const route = useRoute();
const test = ref([]);
const first_name = ref("");
const last_name = ref("");
const selectedAnswers = ref([]);
const showSubmitConfirmation = ref(false);
const loading = ref(false);
const pageLoad = ref(false);

const getTestDetails = (id) => {
    pageLoad.value = true;
    axios
        .get(`/api/edit/${id}`)
        .then((response) => {
            pageLoad.value = false;
            test.value = response.data;
        })
        .catch((error) => {
            pageLoad.value = false;
            console.error("Error fetching test details:", error);
        });
};

const addSelectedAnswer = (questionId, answerId, isChecked) => {
    if (isChecked) {
        // Checkbox is checked, add the entry
        selectedAnswers.value.push({
            question: questionId,
            answer: answerId,
        });
    } else {
        // Checkbox is unchecked, remove the entry
        const indexToRemove = selectedAnswers.value.findIndex(
            (entry) =>
                entry.question === questionId && entry.answer === answerId
        );
        if (indexToRemove !== -1) {
            selectedAnswers.value.splice(indexToRemove, 1);
        }
    }

    //console.log(selectedAnswers.value);
};

const validateAnswers = () => {
    //console.log("Selected answer", selectedAnswers.value);

    const unansweredQuestions = [];

    for (const data of test.value.sets) {
        for (const question of data.questions) {
            // Check if the question ID is in the selectedAnswers
            const hasAnswer = selectedAnswers.value.some(
                (answer) => answer.question === question.id
            );

            // If the question doesn't have an answer, add it to the array
            if (!hasAnswer) {
                unansweredQuestions.push(question.id);
            }
        }
    }

    if (unansweredQuestions.length > 0) {
        // There are questions without answers, show an error toast
        toastr.error("Some questions do not have answers.");
        closeSubmitForm();
        loading.value = false;
        return false;
    }

    const questionIds = selectedAnswers.value.map((answer) => answer.question);

    // Use a Set to check for duplicate question IDs
    const uniqueQuestionIds = new Set(questionIds);

    if (uniqueQuestionIds.size !== questionIds.length) {
        // There are duplicate question IDs
        toastr.error("Each question should have only one answer.");
        closeSubmitForm();
        loading.value = false;
        return false;
    }

    return true;
};

const submitAnswer = (testId) => {
    loading.value = true;
    const payload = computed(() => {
        return {
            first_name: first_name.value,
            last_name: last_name.value,
            test_id: testId,
            responds: selectedAnswers.value.map((question) => {
                return {
                    question: question.question,
                    answer: question.answer,
                };
            }),
        };
    });

    if (validateAnswers()) {
        axios
            .post(`/api/new/answer`, payload.value)
            .then((response) => {
                toastr.success(response.data.message);
                loading.value = false;

                first_name.value = "";
                last_name.value = "";
                const checkboxes = document.querySelectorAll(
                    'input[type="checkbox"]'
                );
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });

                selectedAnswers.value = [];
                closeSubmitForm();
            })
            .catch((error) => {
                loading.value = false;
                toastr.error(error.response.data.message);
                closeSubmitForm();
            });
    }
};

const showSubmitForm = () => {
    showSubmitConfirmation.value = true;
};

const closeSubmitForm = () => {
    showSubmitConfirmation.value = false;
};

const alphabeticIndex = (index) => {
    const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; // You can extend this if needed
    if (index < alphabet.length) {
        return alphabet[index];
    } else {
        // Handle cases where you have more options than letters
        return String(index + 1);
    }
};

onMounted(() => {
    const id = route.params.id;
    getTestDetails(id);
});
</script>
<template>
    <div>
        <div v-if="pageLoad">
            <Loading />
        </div>
        <form class="h-screen overflow-auto">
            <div
                v-if="showSubmitConfirmation"
                class="h-screen w-full absolute bg-slate-400/90"
            >
                <div class="flex justify-center items-center h-screen">
                    <div class="w-80">
                        <div
                            class="bg-blue-500 p-5 rounded-t-md flex justify-end"
                        >
                            <button
                                @click="closeSubmitForm"
                                class="hover:text-slate-200"
                                type="button"
                                id="close"
                            >
                                <i
                                    class="fa-regular fa-rectangle-xmark text-2xl text-white"
                                ></i>
                            </button>
                        </div>
                        <div class="bg-white rounded-b-md p-10 pt-5 pb-10">
                            <div class="flex justify-center">
                                <i
                                    class="fa-solid fa-file-export text-3xl mb-4 text-blue-500"
                                ></i>
                            </div>
                            <p class="text-center">
                                Are you sure you want to submit your answer?
                            </p>
                            <div class="flex justify-center">
                                <button
                                    v-if="!loading"
                                    @click.prevent="submitAnswer(test.id)"
                                    class="text-white bg-blue-500 p-2 pl-5 pr-5 rounded-md mt-5 hover:bg-blue-600"
                                    type="submit"
                                >
                                    <i class="fa-solid fa-download"></i> Submit
                                </button>
                                <button
                                    v-if="loading"
                                    type="button"
                                    class="text-white bg-blue-500 p-2 pl-5 pr-5 rounded-md mt-5"
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

            <div class="flex justify-center">
                <img class="h-20" :src="logo" />
            </div>

            <div
                v-if="test.status === 1 && !pageLoad"
                class="p-5 pt-0 548px:p-10 548px:pt-0 900px:p-40 900px:pt-0 900px:pb-10 1200px:p-80 1200px:pt-0 1200px:pb-10"
            >
                <div class="shadow-lg p-10 rounded-md ring-2 ring-slate-500">
                    <div class="flex justify-center">
                        <p class="underline underline-offset-4 text-xl">
                            {{ test.test_name }}
                        </p>
                    </div>
                    <div class="mt-10 mb-10">
                        <input
                            v-model="first_name"
                            class="focus:outline-none w-full p-2 rounded-md ring-1 ring-slate-500"
                            placeholder="First Name"
                        />
                        <input
                            v-model="last_name"
                            class="focus:outline-none w-full p-2 rounded-md mt-4 ring-1 ring-slate-500"
                            placeholder="Last Name"
                        />
                    </div>

                    <div
                        class="ring-1 ring-white rounded-md p-4 mb-5"
                        v-for="(set, setIndex) in test.sets"
                    >
                        <div>
                            <p class="text-slate-500 ml-1 mb-1">
                                Part {{ setIndex + 1 }} intructions :
                            </p>
                            <div class="ring-1 ring-slate-500 p-4 rounded-md">
                                <p class="text-cyan-900">
                                    {{ set.instructions }}
                                </p>
                            </div>

                            <div
                                class="p-5 pl-2 pr-2 pb-0"
                                v-for="(
                                    question, questionIndex
                                ) in set.questions"
                            >
                                <div class="flex mb-5 text-cyan-800">
                                    <p class="mr-2">{{ questionIndex + 1 }}.</p>
                                    <p class="ml-1">
                                        {{ question.question }}
                                    </p>
                                </div>

                                <div
                                    v-for="(
                                        answer, ansIndex
                                    ) in question.answers"
                                    class="mb-2"
                                >
                                    <div class="flex items-start ml-2">
                                        <input
                                            class="mr-2 mt-1"
                                            type="checkbox"
                                            :value="answer.id"
                                            @change="
                                                addSelectedAnswer(
                                                    question.id,
                                                    answer.id,
                                                    $event.target.checked
                                                )
                                            "
                                        />
                                        <p>
                                            {{ alphabeticIndex(ansIndex) }}.
                                            {{ answer.answer }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10">
                        <button
                            @click.prevent="showSubmitForm"
                            class="bg-blue-500 text-white hover:bg-blue-600 p-2 rounded-md"
                        >
                            <i class="fa-solid fa-download"></i> Submit Answer
                        </button>
                    </div>
                </div>
            </div>
            <div v-if="test.status === 0" class="p-10">
                <div class="flex justify-center">
                    <div
                        class="w-80 text-center mt-10 p-10 bg-cyan-900 text-lg rounded-md text-white"
                    >
                        <p>
                            It seems that the test's status has already been set
                            to complete.
                        </p>
                        <p class="mt-10">
                            Please contact the test administrator if you believe
                            this is in error or if you have any further
                            questions.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
