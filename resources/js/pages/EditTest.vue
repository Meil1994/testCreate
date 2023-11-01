<script>
import { ref, onMounted, computed } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import { useToastr } from "../toaster.js";
import Nav from "./components/Nav.vue";
import EditTestNewSet from "./components/edit test/EditTestNewSet.vue";
import DeleteQuestion from "./components/edit test/DeleteQuestion.vue";
import AddQuestion from "./components/edit test/AddQuestion.vue";
import AddAnswer from "./components/edit test/AddAnswer.vue";
import DeleteSet from "./components/edit test/DeleteSet.vue";
import BurgerNav from "./components/BurgerNav.vue";
import PageLoading from "./components/PageLoading.vue";

const toastr = useToastr();

export default {
    components: {
        Nav,
        EditTestNewSet,
        DeleteQuestion,
        AddQuestion,
        AddAnswer,
        DeleteSet,
        BurgerNav,
        PageLoading,
    },
    setup() {
        const newQuestionForm = ref(false);
        const newAnswerForm = ref(false);
        const selectedQuestion = ref([]);
        const selectedDeleteItem = ref(null);
        const questionInputs = ref([]);
        const setID = ref([]);
        const route = useRoute();
        const showDeleteConfirmation = ref(false);
        const newSetForm = ref(false);
        const deleteSetForm = ref(false);
        const setLength = ref("");
        const loading = ref(false);
        const pageLoad = ref(false);

        const test = ref({
            test_name: "",
            passing_score: "",
            questions: [],
        });

        const totalQuestions = ref([]);

        const updateTestData = ref({
            id: null,
            test_name: "",
            questions: [],
        });

        const getTestDetails = (id) => {
            pageLoad.value = true;
            axios
                .get(`/api/edit/${id}`)
                .then((response) => {
                    test.value = response.data;
                    setLength.value = test.value.sets.length;

                    totalQuestions.value = [];
                    for (const set of test.value.sets) {
                        for (const question of set.questions) {
                            totalQuestions.value.push(question);
                        }
                    }
                    pageLoad.value = false;
                })
                .catch((error) => {
                    console.error("Error fetching test details:", error);
                    pageLoad.value = false;
                });
        };

        const copyTestLink = () => {
            if (test.value.id) {
                const testLink = `http://127.0.0.1:8000/api/test/number/${test.value.id}`;
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
            } else {
                toastr.error("Test ID is not available");
            }
        };

        const validateAnswers = () => {
            let multipleCorrectAnswersFound = false;
            let passingScore = test.value.passing_score;
            const questionCount = totalQuestions.value.length;

            for (const set of test.value.sets) {
                for (const question of set.questions) {
                    const correctAnswers = question.answers.filter(
                        (answer) => answer.is_correct
                    );

                    if (correctAnswers.length !== 1) {
                        multipleCorrectAnswersFound = true;
                        loading.value = false;
                        break;
                    }
                }

                if (multipleCorrectAnswersFound) {
                    loading.value = false;
                    break;
                }
            }

            if (multipleCorrectAnswersFound) {
                toastr.error(
                    "Please select exactly one correct answer for every question."
                );
                loading.value = false;
                return false;
            }

            if (passingScore > questionCount) {
                toastr.error(
                    "Please make sure that the passing score is not more than the number of questions."
                );
                loading.value = false;
                return false;
            }
            if (passingScore < 1) {
                toastr.error(
                    "Please make sure that the passing score is not set to 0 or empty."
                );
                loading.value = false;
                return false;
            }
            return true;
        };

        const updateSet = () => {
            loading.value = true;
            const payload = computed(() => {
                return {
                    test_name: test.value.test_name,
                    passing_score: test.value.passing_score,
                    sets: test.value.sets.map((set) => ({
                        id: set.id,
                        test_id: test.value.id,
                        instructions: set.instructions,
                        questions: set.questions.map((question) => ({
                            id: question.id,
                            set_id: set.id,
                            question: question.question,
                            answers: question.answers.map((answer) => ({
                                id: answer.id,
                                answer_id: question.id,
                                answer: answer.answer,
                                is_correct: answer.is_correct,
                            })),
                        })),
                    })),
                };
            });
            if (validateAnswers()) {
                axios
                    .put(`/api/update/${test.value.id}`, payload.value)
                    .then((response) => {
                        loading.value = false;
                        toastr.success(response.data.message);
                    })
                    .catch((error) => {
                        loading.value = false;
                        if (error.response.status === 422) {
                            toastr.error("Please fill all required details!");
                        } else {
                            console.error("Error:", error);
                        }
                    });
            }
        };

        const getQuestionCount = () => {
            let questionCount = 0;
            if (totalQuestions.value.id) {
                questionCount++;
            }

            return questionCount;
        };

        const showNewQuestion = (setId) => {
            setID.value.push(setId);
            newQuestionForm.value = true;
            questionInputs.value.push({ question: "", answers: [] });
        };

        const closeNewQuestion = (index) => {
            newQuestionForm.value = false;
            questionInputs.value.splice(index, 1);
            setID.value = [];
        };

        const showAnswer = (questionId) => {
            selectedQuestion.value.push(questionId);
            newAnswerForm.value = true;
        };

        const closeAnswer = () => {
            newAnswerForm.value = false;
            selectedQuestion.value = [];
        };

        const showDeleteQuestion = (questionId) => {
            selectedDeleteItem.value = questionId;
            showDeleteConfirmation.value = true;
        };

        const hideDeleteQuestion = () => {
            showDeleteConfirmation.value = false;
        };

        const showNewSetForm = () => {
            newSetForm.value = true;
        };

        const hideNewSetForm = () => {
            newSetForm.value = false;
        };

        const showDeleteSetForm = (setId, setLength) => {
            setID.value.push(setId);
            deleteSetForm.value = true;
        };

        const hideDeleteSetForm = () => {
            deleteSetForm.value = false;
            setID.value = [];
        };

        onMounted(() => {
            const id = route.params.id;
            getTestDetails(id);
        });

        return {
            test,
            showDeleteConfirmation,
            selectedDeleteItem,
            showDeleteQuestion,
            hideDeleteQuestion,
            updateTestData,
            updateSet,
            validateAnswers,
            questionInputs,
            newQuestionForm,
            showNewQuestion,
            closeNewQuestion,
            copyTestLink,
            setID,
            newAnswerForm,
            showAnswer,
            closeAnswer,
            selectedQuestion,
            showNewSetForm,
            newSetForm,
            hideNewSetForm,
            getTestDetails,
            deleteSetForm,
            showDeleteSetForm,
            hideDeleteSetForm,
            setLength,
            getQuestionCount,
            totalQuestions,
            loading,
            pageLoad,
        };
    },
};
</script>

<template>
    <div class="700px:flex">
        <EditTestNewSet
            :newSetForm="newSetForm"
            :hideNewSetForm="hideNewSetForm"
            :testId="test.id"
            :getTestDetails="getTestDetails"
        />
        <DeleteQuestion
            :showDeleteConfirmation="showDeleteConfirmation"
            :hideDeleteQuestion="hideDeleteQuestion"
            :selectedDeleteItem="selectedDeleteItem"
            :testId="test.id"
            :getTestDetails="getTestDetails"
        />
        <AddQuestion
            :newQuestionForm="newQuestionForm"
            :questionInputs="questionInputs"
            :closeNewQuestion="closeNewQuestion"
            :setID="setID"
            :getTestDetails="getTestDetails"
            :testId="test.id"
        />
        <AddAnswer
            :newAnswerForm="newAnswerForm"
            :selectedQuestion="selectedQuestion"
            :closeAnswer="closeAnswer"
            :getTestDetails="getTestDetails"
            :testId="test.id"
        />
        <DeleteSet
            :deleteSetForm="deleteSetForm"
            :hideDeleteSetForm="hideDeleteSetForm"
            :setID="setID"
            :getTestDetails="getTestDetails"
            :testId="test.id"
        />
        <Nav />
        <div class="flex-grow h-screen overflow-auto">
            <BurgerNav />
            <div v-if="pageLoad">
                <PageLoading />
            </div>
            <form v-if="!pageLoad">
                <div class="p-10 pb-0">
                    <div class="bg-slate-300 p-4 rounded-md">
                        <h1 class="text-xl text-center text-slate-900">
                            Edit Test
                        </h1>
                    </div>
                </div>

                <div class="overflow-auto m-10 mt-0">
                    <div class="w-775px 850px:w-full">
                        <div class="pb-5">
                            <div class="mt-8 flex justify-between m-1">
                                <div class="flex items-center">
                                    <label class="text-slate-600"
                                        >Test name :
                                    </label>
                                    <div>
                                        <input
                                            class="border-b border-slate-300 pl-2 focus:outline-none w-60 ring-1 ring-slate-300 ml-2 p-1 rounded-md"
                                            v-model="test.test_name"
                                        />
                                        <p
                                            v-if="!test.test_name"
                                            class="text-red-500 text-xs"
                                        >
                                            required
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <label class="text-slate-600"
                                        >Passing Score :
                                    </label>
                                    <div>
                                        <input
                                            class="border-b border-slate-300 pl-2 focus:outline-none w-28 ring-1 ring-slate-300 ml-2 p-1 rounded-md"
                                            v-model="test.passing_score"
                                        />
                                        <p
                                            v-if="!test.test_name"
                                            class="text-red-500 text-xs"
                                        >
                                            required
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2">
                            <div class="mb-1 ml-2">
                                <button
                                    class="text-blue-600 text-sm"
                                    @click.prevent="copyTestLink"
                                >
                                    Copy test link
                                </button>
                            </div>
                            <div class="text-slate-600 mr-2 mb-1">
                                <p>
                                    ({{ setLength }})
                                    <span v-if="setLength === 1">part</span
                                    ><span v-if="setLength > 1">parts</span> ({{
                                        totalQuestions.length
                                    }})
                                    <span v-if="totalQuestions.length === 1"
                                        >question</span
                                    ><span v-if="totalQuestions.length > 1"
                                        >questions</span
                                    >
                                </p>
                            </div>
                        </div>
                        <div
                            v-for="(set, setIndex) in test.sets"
                            class="m-1 mb-10 mt-0 p-10 shadow-lg shadow-black ring-1 ring-slate-300 rounded-md"
                        >
                            <p
                                class="mb-4 text-slate-600 underline underline-offset-4"
                            >
                                Part {{ setIndex + 1 }}
                            </p>
                            <div class="">
                                <div class="bg-white rounded-mdpt-5 pb-5">
                                    <p class="mb-1 text-slate-600">
                                        Intructions :
                                    </p>
                                    <textarea
                                        class="focus:outline-none text-cyan-900 ring-1 ring-slate-500 h-28 p-2 rounded-md w-full"
                                        v-model="set.instructions"
                                        >{{ set.instructions }}</textarea
                                    >
                                    <p
                                        v-if="!set.instructions"
                                        class="text-red-400 text-xs mt-1"
                                    >
                                        required
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="set.questions.length > 0"
                                class="bg-white rounded-md mt-4"
                            >
                                <div
                                    v-for="(question, index) in set.questions"
                                    :key="question.id"
                                >
                                    <div class="mb-4">
                                        <div
                                            class="ring-1 ring-slate-400 rounded-md p-10 pt-8 pb-8"
                                        >
                                            <div class="bg-white">
                                                <p class="text-slate-600">
                                                    Part
                                                    {{ setIndex + 1 }}
                                                    question number
                                                    {{ index + 1 }}
                                                </p>
                                                <div class="w-full">
                                                    <input
                                                        placeholder="Question here..."
                                                        class="w-full ring-1 ring-slate-400 p-2 text-cyan-800 rounded-md mt-1 focus:outline-none"
                                                        v-model="
                                                            question.question
                                                        "
                                                    />
                                                    <p
                                                        v-if="
                                                            !question.question
                                                        "
                                                        class="text-red-400 text-xs mt-1"
                                                    >
                                                        required
                                                    </p>
                                                </div>
                                            </div>

                                            <div
                                                class="p-4 ring-1 ring-slate-400 rounded-md mt-4"
                                            >
                                                <p class="text-slate-600">
                                                    Answers :
                                                </p>
                                                <div
                                                    class="mt-4"
                                                    v-for="(
                                                        answer, ansIndex
                                                    ) in question.answers"
                                                    :key="answer.id"
                                                >
                                                    <div>
                                                        <div
                                                            v-if="
                                                                answer.is_correct
                                                            "
                                                            class="text-cyan-500"
                                                        >
                                                            <span
                                                                class="text-sm"
                                                                >(Correct
                                                                answer)</span
                                                            >
                                                        </div>
                                                        <div>
                                                            <div
                                                                class="grid grid-cols-5 rounded-md p-2 ring-1 ring-slate-400"
                                                                :class="{
                                                                    'bg-green-300 rounded-md ':
                                                                        answer.is_correct,
                                                                }"
                                                            >
                                                                <div
                                                                    class="col-span-3"
                                                                >
                                                                    <input
                                                                        :placeholder="
                                                                            'Answer number ' +
                                                                            (ansIndex +
                                                                                1)
                                                                        "
                                                                        class="focus:outline-none w-full"
                                                                        :class="{
                                                                            'bg-green-300 rounded-md':
                                                                                answer.is_correct,
                                                                        }"
                                                                        v-model="
                                                                            answer.answer
                                                                        "
                                                                    />
                                                                </div>
                                                                <div
                                                                    class="col-span-2 flex justify-end"
                                                                >
                                                                    <div
                                                                        class="flex justify-end items-center mr-2 text-slate-600"
                                                                    >
                                                                        <label
                                                                            >Set
                                                                            as
                                                                            answer</label
                                                                        >
                                                                        <input
                                                                            v-model="
                                                                                answer.is_correct
                                                                            "
                                                                            class="ml-1"
                                                                            type="checkbox"
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <p
                                                            v-if="
                                                                !answer.answer
                                                            "
                                                            class="text-red-400 text-xs mt-1 ml-1 -mb-2"
                                                        >
                                                            required
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end">
                                                    <button
                                                        @click.prevent="
                                                            showAnswer(
                                                                question.id
                                                            )
                                                        "
                                                        class="text-sm mr-1 text-blue-500 hover:text-blue-600 mt-5"
                                                    >
                                                        <i
                                                            class="fa-solid fa-plus"
                                                        ></i>
                                                        Add answer
                                                    </button>
                                                </div>
                                            </div>
                                            <div
                                                v-if="
                                                    set.questions.length > 1 &&
                                                    test.passing_score <=
                                                        totalQuestions.length
                                                "
                                                class="flex justify-end mt-4"
                                            >
                                                <button
                                                    @click.prevent="
                                                        showDeleteQuestion(
                                                            question.id
                                                        )
                                                    "
                                                    class="bg-red-500 p-2 rounded-md hover:bg-red-600 text-white"
                                                >
                                                    <i
                                                        class="fa-regular fa-trash-can"
                                                    ></i>
                                                    Delete question
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <p class="text-sm text-red-500 mt-2">
                                    No questions available
                                </p>
                            </div>

                            <div class="pt-6 flex">
                                <div>
                                    <button
                                        @click="showNewQuestion(set.id)"
                                        type="button"
                                        class="text-green-500 hover:text-green-600"
                                    >
                                        <i
                                            class="fa-regular fa-square-plus mr-2"
                                        ></i
                                        >Add question
                                    </button>
                                </div>
                                <div>
                                    <button
                                        v-if="test.sets.length > 1"
                                        @click.prevent="
                                            showDeleteSetForm(
                                                set.id,
                                                test.sets.length
                                            )
                                        "
                                        class="text-red-400 hover:text-red-500 ml-4"
                                    >
                                        <i class="fa-regular fa-trash-can"></i>
                                        Delete set
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 flex items-center pt-5">
                            <button
                                v-if="!loading"
                                @click.prevent="updateSet()"
                                class="p-2 pl-4 pr-4 rounded-md bg-blue-500 hover:bg-blue-600 text-white"
                            >
                                <i class="fa-solid fa-cloud"></i> Update test
                            </button>
                            <button
                                v-if="loading"
                                class="p-2 pl-4 pr-4 rounded-md bg-blue-500 text-white"
                            >
                                <i class="fa-solid fa-spinner animate-spin"></i>
                                Loading...
                            </button>
                            <div>
                                <button
                                    class="bg-green-500 p-2 rounded-md hover:bg-green-600 ml-4 text-white"
                                    @click.prevent="showNewSetForm"
                                >
                                    <i class="fa-solid fa-plus"></i> Add new set
                                    of questions
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
