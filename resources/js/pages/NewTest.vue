<script>
import axios from "axios";
import { useToastr } from "../toaster.js";
import Nav from "./components/Nav.vue";
import BurgerNav from "./components/BurgerNav.vue";
import { ref, computed } from "vue";
import { useRouter } from "vue-router";

const toastr = useToastr();

export default {
    components: {
        Nav,
        BurgerNav,
    },
    setup() {
        const test_name = ref("");
        const passing_score = ref("");
        const questionInputs = ref([]);
        const selectedQuestionIndex = ref(null);
        const showConfirmationDialog = ref(false);
        const router = useRouter();
        const loading = ref(false);

        const addSets = () => {
            questionInputs.value.push({ instructions: "", sets: [] });
        };

        const addQuestion = (index) => {
            questionInputs.value[index].sets.push({
                questions: "",
                answers: [],
            });
        };

        const addAnswer = (setIndex, questionIndex) => {
            questionInputs.value[setIndex].sets[questionIndex].answers.push({
                answer: "",
                isSelected: false,
            });
        };

        const selectQuestion = (index) => {
            selectedQuestionIndex.value = index;
        };

        const deleteSet = (index) => {
            questionInputs.value.splice(index, 1);
        };

        const deleteQuestion = (index, questionIndex) => {
            if (index !== null && questionIndex !== null) {
                questionInputs.value[index].sets.splice(questionIndex, 1);
            }
        };

        const removeAnswer = (index, questionIndex, answerIndex) => {
            if (answerIndex !== null) {
                questionInputs.value[index].sets[questionIndex].answers.splice(
                    answerIndex,
                    1
                );
            }
        };

        const setCount = computed(() => {
            return questionInputs.value.length;
        });

        const totalQuestionCount = () => {
            let totalQuestion = 0;
            for (const question of questionInputs.value) {
                for (const set of question.sets) {
                    if (set) {
                        totalQuestion++;
                    }
                }
            }
            return totalQuestion;
        };

        const validateAnswers = () => {
            let passingScore = passing_score.value;
            let questionCount = 0;

            for (const question of questionInputs.value) {
                for (const set of question.sets) {
                    if (set.questions) {
                        questionCount++;
                    }
                }
            }

            for (const questionInput of questionInputs.value) {
                if (questionInput.sets.length === 0) {
                    showConfirmationDialog.value = false;
                    loading.value = false;
                    toastr.error(
                        "Please add at least 1 question to every set of questions."
                    );
                    return false;
                }

                for (const set of questionInput.sets) {
                    const correctAnswers = set.answers.filter(
                        (answer) => answer.isSelected
                    );

                    if (set.length === 0 || correctAnswers.length !== 1) {
                        showConfirmationDialog.value = false;
                        loading.value = false;
                        toastr.error(
                            "Please select exactly one correct answer for every question."
                        );
                        return false;
                    }
                }
            }
            if (passingScore > questionCount) {
                showConfirmationDialog.value = false;
                loading.value = false;
                toastr.error(
                    "Please make sure that the passing score is not more than the number of questions."
                );
                return false;
            }
            if (passingScore < 1) {
                showConfirmationDialog.value = false;
                loading.value = false;
                toastr.error(
                    "Please make sure that the passing score is not set to 0 or empty."
                );
                return false;
            }
            return true;
        };

        const saveTest = async () => {
            loading.value = true;
            const payload = {
                test_name: test_name.value,
                passing_score: passing_score.value,
                sets: questionInputs.value.map((questionInput) => {
                    return {
                        instructions: questionInput.instructions,
                        questions: questionInput.sets.map((question) => {
                            return {
                                question: question.questions,
                                answers: question.answers.map((answer) => {
                                    return {
                                        answer: answer.answer,
                                        is_correct: answer.isSelected,
                                    };
                                }),
                            };
                        }),
                    };
                }),
            };

            if (validateAnswers()) {
                try {
                    const response = await axios.post(
                        "/api/test/create",
                        payload
                    );

                    test_name.value = "";
                    questionInputs.value = [];
                    router.push("/dashboard");
                    toastr.success(response.data.message);
                    loading.value = false;
                } catch (error) {
                    if (error.response.status === 422) {
                        const validationErrors = error.response.data.errors;
                        for (const field in validationErrors) {
                            toastr.error(`${validationErrors[field][0]}\n`);
                        }
                    } else {
                        console.error("Error:", error);
                    }
                    showConfirmationDialog.value = false;
                    loading.value = false;
                }
            }
        };

        return {
            test_name,
            questionInputs,
            selectedQuestionIndex,
            showConfirmationDialog,
            addQuestion,
            addAnswer,
            selectQuestion,
            deleteQuestion,
            removeAnswer,
            validateAnswers,
            saveTest,
            setCount,
            router,
            addSets,
            deleteSet,
            passing_score,
            totalQuestionCount,
            loading,
        };
    },
};
</script>

<template>
    <form class="700px:flex">
        <div
            v-if="showConfirmationDialog"
            class="absolute w-100% h-screen overflow-auto"
        >
            <div
                class="bg-slate-500/70 flex justify-center items-center h-screen"
            >
                <div class="bg-white rounded-md h-80 w-80">
                    <div class="flex justify-end bg-blue-500 p-4 rounded-t">
                        <button
                            class="text-white text-2xl"
                            @click.prevent="showConfirmationDialog = false"
                        >
                            <i
                                class="fa-regular fa-rectangle-xmark hover:text-slate-300"
                            ></i>
                        </button>
                    </div>
                    <div class="mt-7 p-6 pt-0">
                        <p class="text-blue-500 text-center text-4xl mb-2">
                            <i class="fa-regular fa-note-sticky"></i>
                        </p>
                        <p class="text-center">
                            Are you sure you want to save the
                            {{ setCount }}
                            <span v-if="setCount == 1">set</span
                            ><span v-if="setCount > 1">sets</span>
                            test?
                        </p>
                        <div class="flex justify-center mt-10">
                            <button
                                v-if="!loading"
                                @click.prevent="saveTest"
                                class="bg-blue-500 p-2 w-40 text-white rounded-md hover:bg-blue-600"
                            >
                                Save
                            </button>
                            <button
                                type="button"
                                v-if="loading"
                                class="bg-blue-500 p-2 w-40 text-white rounded-md"
                            >
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
            <div>
                <div>
                    <div class="p-10 pt-8">
                        <div class="bg-slate-300 p-4 rounded-md w-full">
                            <h1 class="text-xl text-center text-slate-900">
                                Add New Test
                            </h1>
                        </div>
                        <div class="overflow-auto">
                            <div class="mt-6 w-775px 850px:w-full">
                                <div
                                    v-if="questionInputs.length > 0"
                                    class="flex justify-between m-1 items-center"
                                >
                                    <div>
                                        <div class="flex">
                                            <p class="text-slate-600">
                                                Test Name :
                                            </p>
                                            <div>
                                                <input
                                                    v-model="test_name"
                                                    class="border-b border-slate-300 pl-2 focus:outline-none"
                                                />
                                                <p
                                                    v-if="!test_name"
                                                    class="text-red-500 text-xs"
                                                >
                                                    required
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        v-if="questionInputs.length > 0"
                                        class="flex"
                                    >
                                        <p class="text-slate-600">
                                            Passing Score :
                                        </p>
                                        <div>
                                            <input
                                                v-model="passing_score"
                                                type="number"
                                                class="border-b border-slate-300 ml-1 pl-2 pr-2 w-168px focus:outline-none"
                                            />
                                            <p
                                                v-if="!passing_score"
                                                class="text-red-500 text-xs ml-1"
                                            >
                                                required
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    v-if="questionInputs.length > 0"
                                    class="flex m-1 mr-2 items-center mt-6 justify-end text-slate-600"
                                >
                                    <p>
                                        ({{ setCount }})
                                        <span v-if="questionInputs.length === 1"
                                            >part</span
                                        ><span v-if="questionInputs.length > 1"
                                            >parts</span
                                        >
                                    </p>

                                    <p
                                        v-if="totalQuestionCount() > 0"
                                        class="ml-2"
                                    >
                                        ({{ totalQuestionCount() }})
                                        <span v-if="totalQuestionCount() === 1"
                                            >question</span
                                        ><span v-if="totalQuestionCount() > 1"
                                            >questions</span
                                        >
                                    </p>
                                </div>
                                <div
                                    v-for="(
                                        questionInput, index
                                    ) in questionInputs"
                                    class="ring-1 ring-slate-300 p-10 rounded-md m-1"
                                >
                                    <div class="mb-2">
                                        <p class="text-slate-700 ml-1">
                                            Part {{ index + 1 }} instructions :
                                        </p>
                                        <textarea
                                            v-model="questionInput.instructions"
                                            class="ring-1 ring-slate-300 rounded-md w-full focus:outline-none p-2"
                                            placeholder="Test Instructions..."
                                        ></textarea>
                                        <p
                                            v-if="!questionInput.instructions"
                                            class="text-red-500 text-xs -mt-1 ml-2"
                                        >
                                            required
                                        </p>
                                    </div>
                                    <div>
                                        <div
                                            v-for="(
                                                question, questionIndex
                                            ) in questionInput.sets"
                                            :key="index"
                                            class="grid grid-cols-5 border-b border-slate-300 ring-1 ring-slate-300 rounded-md mb-2"
                                        >
                                            <div class="col-span-4 p-4">
                                                <div class="p-4">
                                                    <div class="flex">
                                                        <p>
                                                            {{
                                                                questionIndex +
                                                                1
                                                            }}.
                                                        </p>
                                                        <div class="w-full">
                                                            <input
                                                                class="ml-2 w-full focus:outline-none border-b border-slate-300"
                                                                v-model="
                                                                    question.questions
                                                                "
                                                                :placeholder="`Part ${
                                                                    index + 1
                                                                } question ${
                                                                    questionIndex +
                                                                    1
                                                                } here..`"
                                                            />
                                                            <p
                                                                v-if="
                                                                    !question.questions
                                                                "
                                                                class="text-red-500 text-xs mt-1 ml-2"
                                                            >
                                                                required
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="p-9 pt-0 pb-0"
                                                    v-for="(
                                                        answerInput, ansIndex
                                                    ) in question.answers"
                                                    :key="ansIndex"
                                                >
                                                    <div
                                                        v-if="
                                                            answerInput.isSelected
                                                        "
                                                        class="-mb-1 mt-2"
                                                    >
                                                        <p
                                                            class="text-sm text-green-500"
                                                        >
                                                            Correct answer
                                                        </p>
                                                    </div>
                                                    <div
                                                        :class="{
                                                            'ring-4 ring-green-300 rounded-md mb-2':
                                                                answerInput.isSelected,
                                                        }"
                                                    >
                                                        <div
                                                            class="grid grid-cols-4 border-b p-2 mt-2 ring-1 ring-slate-300 rounded-md"
                                                        >
                                                            <div
                                                                class="col-span-3"
                                                            >
                                                                <input
                                                                    class="w-full focus:outline-none mr-10"
                                                                    v-model="
                                                                        answerInput.answer
                                                                    "
                                                                    :placeholder="
                                                                        'Answer number ' +
                                                                        (ansIndex +
                                                                            1)
                                                                    "
                                                                />
                                                            </div>
                                                            <div
                                                                class="col-span-1 flex justify-end"
                                                            >
                                                                <button
                                                                    type="button"
                                                                    @click="
                                                                        removeAnswer(
                                                                            index,
                                                                            questionIndex,
                                                                            ansIndex
                                                                        )
                                                                    "
                                                                    class="ml-4 text-sm underline underline-offset-4 text-red-500"
                                                                >
                                                                    Remove
                                                                </button>
                                                                <div
                                                                    class="flex items-center ml-2"
                                                                >
                                                                    <p
                                                                        type="button"
                                                                        class="text-blue-500 underline underline-offset-4"
                                                                    >
                                                                        Answer
                                                                    </p>
                                                                    <input
                                                                        v-model="
                                                                            answerInput.isSelected
                                                                        "
                                                                        type="checkbox"
                                                                        class="ml-2 hover:pointer0"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p
                                                        v-if="
                                                            !answerInput.answer
                                                        "
                                                        class="text-red-500 text-xs mt-1 ml-2"
                                                    >
                                                        required
                                                    </p>
                                                </div>
                                            </div>
                                            <div
                                                class="col-span-1 ring-1 ring-slate-300 rounded-r-md p-5"
                                            >
                                                <button
                                                    class="bg-blue-500 p-2 text-white hover:bg-blue-600 w-full rounded-md text-sm"
                                                    type="button"
                                                    @click="
                                                        addAnswer(
                                                            index,
                                                            questionIndex
                                                        )
                                                    "
                                                >
                                                    <i
                                                        class="fa-solid fa-plus"
                                                    ></i>
                                                    Add answer
                                                </button>
                                                <button
                                                    @click="
                                                        deleteQuestion(
                                                            index,
                                                            questionIndex
                                                        )
                                                    "
                                                    class="bg-red-500 p-2 text-white hover:bg-red-600 mt-5 w-full rounded-md text-sm"
                                                    type="button"
                                                >
                                                    <i
                                                        class="fa-regular fa-trash-can"
                                                    ></i>
                                                    Delete question
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <button
                                            type="button"
                                            @click.prevent="addQuestion(index)"
                                            class="text-blue-500"
                                        >
                                            <i
                                                class="fa-regular fa-square-plus"
                                            ></i>
                                            Add question
                                        </button>
                                        <button
                                            @click.prevent="deleteSet(index)"
                                            class="text-red-500 ml-5"
                                        >
                                            <i
                                                class="fa-regular fa-trash-can"
                                            ></i>
                                            Delete set
                                        </button>
                                    </div>
                                </div>
                                <div
                                    class="ring-1 ring-slate-300 p-6 m-1 mt-4 rounded-md"
                                >
                                    <button
                                        @click.prevent="addSets"
                                        class="p-2 rounded-md bg-green-500 text-white hover:bg-green-600 mr-5"
                                    >
                                        <i
                                            class="fa-regular fa-square-plus mr-2"
                                        ></i
                                        >Add new set of questions
                                    </button>
                                    <button
                                        @click.prevent="
                                            showConfirmationDialog = true
                                        "
                                        v-if="questionInputs.length !== 0"
                                        class="p-2 rounded-md bg-blue-600 text-white hover:bg-blue-700"
                                    >
                                        Create test
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
