<script setup>
import { defineProps, ref } from "vue";
import { useToastr } from "../../../toaster";

const toastr = useToastr();
const loading = ref(false);
const { newSetForm, hideNewSetForm, testId, getTestDetails } = defineProps([
    "newSetForm",
    "hideNewSetForm",
    "testId",
    "getTestDetails",
]);

const setQuestions = ref([]);
const instructions = ref("");

const addQuestion = () => {
    setQuestions.value.push({ questions: "", answers: [] });
};

const addAnswer = (index) => {
    setQuestions.value[index].answers.push({ answer: "", isSelected: false });
};

const deleteQuestion = (questionIndex) => {
    setQuestions.value.splice(questionIndex, 1);
};

const deleteAnswer = (questionIndex, ansIndex) => {
    setQuestions.value[questionIndex].answers.splice(ansIndex, 1);
};

const validateAnswer = () => {
    const error = "Please select exactly one correct answer to every question.";
    for (const question of setQuestions.value) {
        if (question.answers.length < 1) {
            toastr.error(error);
            return false;
            loading.value = false;
        }

        let correctAnswersCount = 0;

        for (const answer of question.answers) {
            if (answer.isSelected) {
                correctAnswersCount++;
            }
        }
        if (correctAnswersCount !== 1) {
            toastr.error(error);
            return false;
            loading.value = false;
        }
    }
    return true;
};

const saveNewSet = (testID) => {
    loading.value = true;
    const payload = {
        test_id: testID,
        instructions: instructions.value,
        questions: setQuestions.value.map((quest) => {
            return {
                question: quest.questions,
                answers: quest.answers.map((ans) => {
                    return {
                        answer: ans.answer,
                        is_correct: ans.isSelected,
                    };
                }),
            };
        }),
    };

    if (validateAnswer()) {
        axios
            .post(`/api/create/new/editset/${testID}`, payload)
            .then((response) => {
                loading.value = false;
                toastr.success(response.data.message);
                setQuestions.value = [];
                instructions.value = "";
                hideNewSetForm();
                getTestDetails(testID);
            })
            .catch((error) => {
                loading.value = false;
                toastr.error(error.response.data.message);
            });
    }
};
</script>
<template>
    <div v-if="newSetForm" class="absolute h-screen w-100%">
        <div class="bg-slate-500/90 h-screen overflow-auto p-5">
            <form
                class="600px:p-20 600px:pt-10 600px:pb-10 850px:p-40 850px:pb-10 850px:pt-10 1122px:p-60 1122px:pt-10 1122px:pb-10 w-full pt-10 pb-10"
            >
                <div class="flex justify-between items-center p-2">
                    <p class="text-white">Add new set</p>
                    <button
                        class="text-white text-2xl"
                        @click.prevent="hideNewSetForm"
                    >
                        <i class="fa-regular fa-rectangle-xmark"></i>
                    </button>
                </div>
                <div
                    v-if="setQuestions.length > 0"
                    class="border border-slate-300 rounded-md bg-white"
                >
                    <div class="col-span-4">
                        <div class="p-10 pb-4">
                            <input
                                v-model="instructions"
                                class="ml-2 w-full focus:outline-none border-b border-slate-300"
                                placeholder="Type instructions here.."
                            />
                            <div class="ml-2 mt-1 text-xs text-red-500">
                                <p>required</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border border-slate-300 rounded-md bg-white mt-4">
                    <div
                        v-for="(question, questionIndex) in setQuestions"
                        class="col-span-4 border-b border-slate-300"
                    >
                        <div class="p-10 pb-0">
                            <div class="flex items-center">
                                <p>{{ questionIndex + 1 }}.</p>
                                <input
                                    v-model="question.questions"
                                    class="ml-2 w-full focus:outline-none border-b border-slate-300"
                                    placeholder="Type question here.."
                                />
                            </div>
                            <div class="text-xs mt-1 ml-5 text-red-500">
                                <p>required</p>
                            </div>
                            <div class="p-5 pt-0 pb-0 mt-4 mb-4">
                                <div
                                    class="mb-2"
                                    v-for="(
                                        answer, ansIndex
                                    ) in question.answers"
                                >
                                    <div
                                        class="flex items-center ring-1 ring-slate-300 p-1 rounded-md"
                                    >
                                        <input
                                            v-model="answer.answer"
                                            class="ml-2 w-full focus:outline-none"
                                            placeholder="Type answer here.."
                                        />
                                        <div class="flex items-center">
                                            <div
                                                class="flex items-center border-r border-slate-300 pr-2"
                                            >
                                                <p
                                                    class="text-sm text-blue-500 mr-1"
                                                >
                                                    Answer
                                                </p>
                                                <input
                                                    v-model="answer.isSelected"
                                                    type="checkbox"
                                                />
                                            </div>
                                            <button
                                                @click.prevent="
                                                    deleteAnswer(
                                                        questionIndex,
                                                        ansIndex
                                                    )
                                                "
                                                class="ml-2 text-red-500 hover:text-red-600"
                                            >
                                                <i
                                                    class="fa-regular fa-trash-can"
                                                ></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="text-xs mt-1 text-red-500">
                                        <p>required</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-4 flex justify-between items-center">
                                <button
                                    @click.prevent="addAnswer(questionIndex)"
                                    class="text-sm text-blue-500 hover:text-blue-600"
                                >
                                    <i class="fa-solid fa-plus"></i> Add answer
                                </button>
                                <button
                                    class="text-red-500 text-sm hover:text-red-600"
                                    @click.prevent="
                                        deleteQuestion(questionIndex)
                                    "
                                >
                                    <i class="fa-regular fa-trash-can"></i>
                                    Delete question
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-10 pt-5 pb-5">
                        <button
                            v-if="setQuestions.length > 0 && !loading"
                            @click.prevent="saveNewSet(testId)"
                            class="bg-blue-500 p-2 text-white rounded-md hover:bg-blue-600"
                        >
                            <i class="fa-solid fa-download"></i> Save new set
                        </button>
                        <button
                            type="button"
                            v-if="loading"
                            class="bg-blue-500 p-2 text-white rounded-md"
                        >
                            <i class="fa-solid fa-spinner animate-spin"></i>
                            Loading...
                        </button>
                        <button
                            @click.prevent="addQuestion"
                            class="bg-green-500 hover:bg-green-600 text-white rounded-md ml-4 p-2"
                        >
                            <span v-if="setQuestions.length > 0"
                                ><i class="fa-solid fa-plus"></i> Add
                                question</span
                            ><span v-else
                                ><i class="fa-solid fa-plus"></i> Add new
                                set</span
                            >
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
