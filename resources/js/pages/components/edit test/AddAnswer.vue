<script setup>
import { defineProps, ref } from "vue";
import { useToastr } from "../../../toaster";

const toastr = useToastr();
const loading = ref(false);
const { newAnswerForm, selectedQuestion, closeAnswer, getTestDetails, testId } =
    defineProps([
        "newAnswerForm",
        "selectedQuestion",
        "closeAnswer",
        "getTestDetails",
        "testId",
    ]);

const answer = ref("");

const saveNewAnswer = (selectedQuestion, testId) => {
    loading.value = true;
    axios
        .post("/api/create/new/edit/answer", {
            question_id: selectedQuestion.toString(),
            answer: answer.value,
        })
        .then((response) => {
            loading.value = false;
            toastr.success(response.data.message);
            answer.value = "";
            closeAnswer();
            getTestDetails(testId);
        })
        .catch((error) => {
            loading.value = false;
            if (error.response.status === 422) {
                toastr.error(error.response.data.message);
            }
        });
};
</script>
<template>
    <div v-if="newAnswerForm" class="absolute h-screen w-100%">
        <div class="bg-slate-500/90 h-screen flex justify-center">
            <form class="900px:w-50% 600px:w-70% w-90% mt-10">
                <div class="flex justify-between items-center p-2">
                    <p class="text-white">Add new answer</p>
                    <button
                        class="text-white text-2xl"
                        @click.prevent="closeAnswer"
                    >
                        <i class="fa-regular fa-rectangle-xmark"></i>
                    </button>
                </div>
                <div class="border border-slate-300 rounded-md bg-white">
                    <div class="col-span-4">
                        <div class="p-10 pb-4">
                            <input
                                v-model="answer"
                                class="ml-2 w-full focus:outline-none border-b border-slate-300"
                                placeholder="Type answer here.."
                            />
                        </div>
                        <div
                            class="p-10 -mt-3 pt-0 pb-4 ml-5 text-xs text-red-500"
                        >
                            <p>required</p>
                        </div>
                    </div>
                    <div class="p-10 flex justify-center">
                        <button
                            v-if="!loading"
                            @click.prevent="
                                saveNewAnswer(selectedQuestion, testId)
                            "
                            class="bg-blue-500 p-2 text-white rounded-md hover:bg-blue-600"
                        >
                            <i class="fa-solid fa-download"></i> Save answer
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
