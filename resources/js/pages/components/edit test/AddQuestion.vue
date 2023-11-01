<script setup>
import { defineProps, computed, ref } from "vue";
import { useToastr } from "../../../toaster";

const toastr = useToastr();
const loading = ref(false);
const {
    newQuestionForm,
    questionInputs,
    closeNewQuestion,
    setID,
    getTestDetails,
    testId,
} = defineProps([
    "newQuestionForm",
    "questionInputs",
    "closeNewQuestion",
    "setID",
    "getTestDetails",
    "testId",
]);

const addAnswer = (index) => {
    questionInputs[index].answers.push({ answer: "", isSelected: false });
};

const removeAnswer = (questionIndex, answerIndex) => {
    if (questionIndex !== null && answerIndex !== null) {
        questionInputs[questionIndex].answers.splice(answerIndex, 1);
    }
};

const validateNewQuestionAnswers = () => {
    for (const questionInput of questionInputs) {
        const isAtLeastOneSelected = questionInput.answers.some(
            (answer) => answer.isSelected
        );
        if (!isAtLeastOneSelected) {
            toastr.error("Please add at least one correct answer.");
            loading.value = false;
            return false;
        }
    }
    return true;
};

const submitNewQuestion = (setID, testId) => {
    loading.value = true;
    const newQuestionPayload = computed(() => {
        return {
            questions: questionInputs.map((questionInput) => {
                return {
                    set_id: setID.toString(),
                    question: questionInput.question,
                    answers: questionInput.answers.map((answerInput) => {
                        return {
                            answer: answerInput.answer,
                            is_correct: answerInput.isSelected,
                        };
                    }),
                };
            }),
        };
    });

    if (validateNewQuestionAnswers()) {
        axios
            .post(`/api/edit/new/question`, newQuestionPayload.value)
            .then((response) => {
                loading.value = false;
                toastr.success(response.data.message);
                closeNewQuestion();
                getTestDetails(testId);
            })
            .catch((error) => {
                loading.value = false;
                toastr.error(error.response.data.message);
            });
    }
};
</script>
<template>
    <div
        v-if="newQuestionForm"
        class="absolute h-screen w-100% bg-slate-500/90 overflow-auto"
    >
        <div class="flex justify-center pb-10 pt-10">
            <form class="900px:w-50% 600px:w-70% w-90%">
                <div class="flex justify-between items-center p-2">
                    <p class="text-white">Add new question</p>
                    <button
                        class="text-white text-2xl"
                        @click.prevent="closeNewQuestion(index)"
                    >
                        <i class="fa-regular fa-rectangle-xmark"></i>
                    </button>
                </div>
                <div class="border border-slate-300 rounded-md bg-white">
                    <div
                        class="border-b border-slate-300"
                        v-for="(questionInputs, index) in questionInputs"
                        :key="index"
                    >
                        <div class="col-span-4">
                            <div class="flex items-center p-10 pb-4">
                                <p>{{ index + 1 }}.</p>
                                <input
                                    class="ml-2 w-full focus:outline-none border-b border-slate-300"
                                    v-model="questionInputs.question"
                                    placeholder="Question here.."
                                />
                            </div>
                            <div
                                class="p-10 -mt-3 pt-0 pb-4 ml-5 text-xs text-red-500"
                            >
                                <p v-if="!questionInputs.question">required</p>
                            </div>
                            <div class="pb-10">
                                <div
                                    v-for="(
                                        answerInput, ansIndex
                                    ) in questionInputs.answers"
                                    :key="ansIndex"
                                    class="p-16 pt-0 pb-0"
                                >
                                    <p
                                        v-if="answerInput.isSelected"
                                        class="-mb-1 text-cyan-500 text-sm mt-1"
                                    >
                                        Correct answer
                                    </p>
                                    <div
                                        :class="{
                                            'ring-4 ring-cyan-500 rounded-md':
                                                answerInput.isSelected,
                                        }"
                                    >
                                        <div
                                            class="grid 438px:grid-cols-4 border-b p-2 mt-2 ring-1 bo ring-slate-300 rounded-md"
                                        >
                                            <div
                                                class="col-span-4 438px:col-span-3"
                                            >
                                                <input
                                                    v-model="answerInput.answer"
                                                    :placeholder="
                                                        'Answer number ' +
                                                        (ansIndex + 1)
                                                    "
                                                    class="w-full focus:outline-none mr-10"
                                                />
                                            </div>
                                            <div
                                                class="438px:col-span-1 flex 438px:justify-end"
                                            >
                                                <button
                                                    @click.prevent="
                                                        removeAnswer(
                                                            index,
                                                            ansIndex
                                                        )
                                                    "
                                                    class="text-red-500 underline underline-offset-4 text-sm"
                                                >
                                                    Remove
                                                </button>
                                                <div
                                                    class="flex items-center ml-2"
                                                >
                                                    <p
                                                        class="text-blue-500 underline underline-offset-4 text-sm"
                                                    >
                                                        Answer
                                                    </p>
                                                    <input
                                                        v-model="
                                                            answerInput.isSelected
                                                        "
                                                        class="ml-1"
                                                        type="checkbox"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p
                                        class="text-xs text-red-500 mt-1"
                                        v-if="!answerInput.answer"
                                    >
                                        required
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="p-16 pt-0 pb-4 text-sm text-blue-500">
                            <button
                                type="button"
                                @click.prevent="addAnswer(index)"
                            >
                                <i class="fa-solid fa-plus"></i> Add answer
                            </button>
                        </div>
                    </div>
                    <div class="p-10 flex justify-center">
                        <button
                            v-if="!loading"
                            class="bg-blue-500 p-2 text-white rounded-md hover:bg-blue-600"
                            @click.prevent="submitNewQuestion(setID, testId)"
                        >
                            <i class="fa-solid fa-download"></i> Save question
                        </button>
                        <button
                            type="button"
                            v-if="loading"
                            class="bg-blue-500 p-2 text-white rounded-md"
                        >
                            <i class="fa-solid fa-spinner animate-spin"></i>
                            Loading...
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
