<script setup>
import { ref, onMounted } from "vue";
import { useToastr } from "../../../toaster.js";

const toastr = useToastr();
const showNoteConfirmation = ref(false);
const title = ref("");
const content = ref("");
const notes = ref([]);
const exitMenu = ref(false);
const noteContent = ref(true);
const loading = ref(false);

const handleSubmit = () => {
    loading.value = true;
    axios
        .post("/api/create/new/note", {
            title: title.value,
            content: content.value,
        })
        .then((response) => {
            loading.value = false;
            toastr.success(response.data.message);
            getNotes();
            hideNote();
            title.value = "";
            content.value = "";
        })
        .catch((error) => {
            loading.value = false;
            if (error.response.status === 422) {
                toastr.error(error.response.data.message);
            }
        });
};

const showNote = () => {
    showNoteConfirmation.value = true;
    noteContent.value = false;
};

const hideNote = () => {
    showNoteConfirmation.value = false;
    exitMenu.value = true;
    setTimeout(() => {
        exitMenu.value = false;
        noteContent.value = true;
    }, 500);
};

const getNotes = () => {
    axios.get("/api/get/updated/notes").then((response) => {
        notes.value = response.data;
    });
};

const deleteNote = (noteId, noteIndex) => {
    notes.value[noteIndex].loading = true;
    axios
        .post(`/api/delete/note/${noteId}`)
        .then((response) => {
            notes.value[noteIndex].loading = true;
            toastr.success(response.data.message);
            const index = notes.value.findIndex((note) => note.id === noteId);

            if (index !== -1) {
                notes.value.splice(index, 1);
            }
        })
        .catch((error) => {
            notes.value[noteIndex].loading = false;
            console.error("Error deleting notes:", error);
        });
};

onMounted(() => {
    getNotes();
});
</script>

<template>
    <div>
        <div
            class="p-10 pt-3 874px:border-l 874px:border-slate-300 874px:h-screen 874px:overflow-auto"
        >
            <div>
                <div
                    class="mt-0 874px:mt-20 mb-8 flex justify-between items-center"
                >
                    <button
                        v-if="!showNoteConfirmation"
                        @click.prevent="showNote"
                        class="bg-green-400 hover:bg-green-500 text-white p-10 pb-1 pt-1 text-sm rounded-md"
                    >
                        Add Note
                    </button>
                    <p class="text-slate-500">My Notes ({{ notes.length }})</p>
                </div>

                <div class="overflow-hidden">
                    <form
                        v-if="showNoteConfirmation"
                        class="slideFromTop w-full"
                    >
                        <div>
                            <div
                                class="ring-1 ring-slate-300 p-10 pt-5 mb-5 rounded-md bg-green-600/50"
                            >
                                <p class="mb-5 mt-2 text-white">Add New Note</p>
                                <div>
                                    <p class="text-sm text-white">Note Title</p>
                                    <input
                                        v-model="title"
                                        class="text-sm ring-1 ring-slate-300 w-full p-1 pl-2 pr-2 rounded-md mt-1 focus:outline-none"
                                    />
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm text-white">Content</p>
                                    <textarea
                                        v-model="content"
                                        class="text-sm ring-1 ring-slate-300 w-full p-1 pl-2 pr-2 rounded-md h-28 mt-1 focus:outline-none"
                                    ></textarea>
                                </div>
                                <div class="mt-5 flex justify-center">
                                    <div class="w-40">
                                        <button
                                            v-if="!loading"
                                            @click.prevent="handleSubmit"
                                            class="bg-blue-500 hover:bg-blue-600 w-full pt-1 pb-1 text-sm rounded-md text-white"
                                        >
                                            Save Note
                                        </button>
                                        <button
                                            type="button"
                                            v-if="loading"
                                            class="bg-blue-500 w-full pt-1 pb-1 text-sm rounded-md text-white"
                                        >
                                            <i
                                                class="fa-solid fa-spinner animate-spin"
                                            ></i>
                                            Loading...
                                        </button>
                                        <br />
                                        <button
                                            class="text-sm w-full mt-4 underline underline-offset-2 text-red-500"
                                            @click.prevent="hideNote"
                                        >
                                            Close Form
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div
                        v-if="exitMenu"
                        class="slideFromBottom text-3xl flex justify-center items-end h-80 ring-1 ring-slate-300 p-2 pt-5 mb-5 rounded-md bg-green-600/50"
                    ></div>
                </div>

                <div v-if="noteContent">
                    <div
                        v-if="notes.length > 0"
                        v-for="(note, noteIndex) in notes"
                        class="ring-1 ring-slate-300 rounded-md bg-white mb-5"
                    >
                        <div class="p-10 pt-5 pb-5 border-b border-slate-300">
                            <div>
                                <p class="text-slate-700 text-sm">Note Title</p>
                                <p
                                    class="ring-1 ring-slate-300 rounded-md w-full mt-1 focus:outline-none p-2"
                                >
                                    {{ note.title }}
                                </p>
                            </div>
                            <div class="mt-2">
                                <p class="text-slate-700 text-sm">Content</p>
                                <p
                                    class="ring-1 ring-slate-300 rounded-md w-full mt-1 focus:outline-none p-2"
                                >
                                    {{ note.content }}
                                </p>
                            </div>
                            <div class="mt-4">
                                <button
                                    v-if="!note.loading"
                                    @click.prevent="
                                        deleteNote(note.id, noteIndex)
                                    "
                                    class="text-sm bg-red-500 p-2 pt-1 pb-1 text-white rounded-md hover:bg-red-600"
                                >
                                    Delete Note
                                </button>
                                <button
                                    v-if="note.loading"
                                    class="text-sm bg-red-500 p-2 pt-1 pb-1 text-white rounded-md"
                                >
                                    <i
                                        class="fa-solid fa-spinner animate-spin"
                                    ></i>
                                    Loading...
                                </button>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        v-if="!showNoteConfirmation"
                        class="ring-1 ring-slate-300 p-6 rounded-md"
                    >
                        <p class="text-red-500">No note available</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@keyframes slideEnter {
    from {
        transform: translateY(-100%);
    }
    to {
        transform: translateY(0);
    }
}

@keyframes slideExit {
    from {
        transform: translateY(0);
    }
    to {
        transform: translateY(-100%);
    }
}

.slideFromTop {
    animation: slideEnter 0.5s forwards;
}

.slideFromBottom {
    animation: slideExit 0.5s forwards;
}
</style>
