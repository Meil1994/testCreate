<script setup>
import { defineProps, ref } from "vue";
import { useToastr } from "../../../toaster";

const toastr = useToastr();
const loading = ref(false);
const {
    showDeleteConfirmation,
    hideDeleteQuestion,
    selectedDeleteItem,
    testId,
    getTestDetails,
} = defineProps([
    "showDeleteConfirmation",
    "hideDeleteQuestion",
    "selectedDeleteItem",
    "testId",
    "getTestDetails",
]);

const deleteQuestion = (selectedDeleteItem, testId) => {
    loading.value = true;
    axios
        .post(`/api/delete/question/${selectedDeleteItem}`)
        .then((response) => {
            loading.value = false;
            toastr.success(response.data.message);
            getTestDetails(testId);
            hideDeleteQuestion();
        });
};
</script>
<template>
    <div
        v-if="showDeleteConfirmation"
        class="absolute h-screen w-100% overflow-auto"
    >
        <div class="bg-slate-500/90 flex justify-center items-center h-screen">
            <div class="bg-white rounded-md h-80 w-80">
                <div class="flex justify-end bg-red-500 p-4 rounded-t">
                    <button
                        @click.prevent="hideDeleteQuestion"
                        class="text-white text-2xl"
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
                    <p class="text-center">
                        Are you sure you want to delete the selected question?
                    </p>
                    <div class="flex justify-center mt-10">
                        <button
                            v-if="!loading"
                            @click.prevent="
                                deleteQuestion(selectedDeleteItem, testId)
                            "
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
</template>
