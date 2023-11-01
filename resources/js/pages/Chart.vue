<script setup>
import { onMounted, ref } from "vue";
import Nav from "./components/Nav.vue";
import BurgerNav from "./components/BurgerNav.vue";
import Chart from "chart.js/auto";
import PageLoading from "./components/PageLoading.vue";

const tests = ref([]);
const individualTest = ref([]);
const passCount = ref([]);
const failCount = ref([]);
const testLenght = ref([]);
const pageLoad = ref(false);

const getTest = () => {
    pageLoad.value = true;
    axios.get("/api/get/chart/test").then((response) => {
        pageLoad.value = false;
        tests.value = response.data;
        if (tests.value.length > 0) {
            individualTest.value = response.data[0];
            testLenght.value = individualTest.value.responders.length;

            countScore();
            handleChart();
        }
    });
};

const changeTestData = (id) => {
    axios.get(`/api/get/test/search/${id}`).then((response) => {
        individualTest.value = [];
        individualTest.value = response.data;
        testLenght.value = [];
        testLenght.value = individualTest.value.responders.length;

        countScore();
        handleChart();
    });
};

const countScore = () => {
    const scores = {};
    let pass = 0;
    let fail = 0;

    for (const responder of individualTest.value.responders) {
        scores[responder.id] = 0; // Initialize the score for each responder to 0
    }

    for (const set of individualTest.value.sets) {
        for (const question of set.questions) {
            for (const answer of question.answers) {
                for (const responder of individualTest.value.responders) {
                    for (const respond of responder.responds) {
                        if (respond.question === question.id) {
                            if (respond.answer === answer.id) {
                                if (answer.is_correct) {
                                    scores[responder.id]++;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    const passingScore = individualTest.value.passing_score;
    for (const responderId in scores) {
        if (scores[responderId] >= passingScore) {
            pass++;
        }
        if (scores[responderId] < passingScore) {
            fail++;
        }
    }

    passCount.value = pass;
    failCount.value = fail;
};
let myChart;

const handleChart = () => {
    if (myChart) {
        myChart.destroy(); // Destroy the existing chart
    }
    const pass = passCount.value;
    const fail = failCount.value;

    const data = {
        labels: [`Fail (${fail})`, `Pass (${pass})`],
        datasets: [
            {
                label: "Test Takers",
                data: [fail, pass],
                backgroundColor: ["rgb(255, 99, 132)", "rgb(54, 162, 235)"],
                hoverOffset: 4,
            },
        ],
    };

    const config = {
        type: "doughnut",
        data: data,
        options: {
            maintainAspectRatio: false,
        },
    };

    if (pageLoad.value === false) {
        const ctx = document.getElementById("myChart").getContext("2d");
        myChart = new Chart(ctx, config);
    }
};

onMounted(() => {
    getTest();
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
            <div>
                <div v-if="tests.length === 0 && !pageLoad">
                    <p class="text-red-500 ml-4 mt-6">No data available</p>
                </div>
                <div
                    v-if="tests.length > 0 && !pageLoad"
                    class="flex justify-end p-5"
                >
                    <select
                        class="ring-1 ring-slate-300 hover:ring-slate-500 p-2 rounded-md focus:outline-none"
                        @change="changeTestData($event.target.value)"
                    >
                        <option v-for="test in tests" :value="test.id">
                            {{ test.test_name }}
                        </option>
                    </select>
                </div>
                <div class="h-600px w-full p-5">
                    <canvas id="myChart"></canvas>
                </div>
                <div class="flex justify-center" v-if="testLenght === 0">
                    <p
                        class="-mt-80 ring-1 ring-slate-300 w-80 h-10 pt-2 text-center text-red-500 rounded-md"
                    >
                        No responder for this test yet.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
