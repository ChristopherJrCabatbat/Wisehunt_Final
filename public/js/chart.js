// Bar Chart/Graph
var ctx = document.getElementById("chart").getContext("2d");
var userChart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: labels,
        datasets: datasets,
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function (value, index, values) {
                        return "₱ " + value; // Add the peso sign to each label
                    },
                },
                title: {
                    display: false,
                },
            },
        },
    },
});

// // Pie Chart/Graph
// var ctxPie = document.getElementById('pieChart').getContext('2d');
// var quantitySoldChart = new Chart(ctxPie, {
//     type: 'pie',
//     data: {
//         labels: productLabels,
//         datasets: productDatasets,
//     },
//     options: {
//         legend: {
//             position: 'top', // Adjust the position as needed
//         },
//     },
// });

document.addEventListener("DOMContentLoaded", function () {
    // Pie Chart/Graph
    var ctxPie = document.getElementById("pieChart").getContext("2d");
    var quantitySoldChart = new Chart(ctxPie, {
        type: "pie",
        data: {
            labels: productLabels,
            datasets: productDatasets,
        },
        options: {
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = ctx.dataset._meta[0].total;
                        let percentage = ((value * 100) / sum).toFixed(2) + "%";
                        return percentage;
                    },
                    color: "#fff", // You can set the color of the percentage text
                    anchor: "end",
                    align: "start",
                    offset: 10,
                },
            },
            legend: {
                position: "top", // Adjust the position as needed
            },
            tooltips: {
                enabled: false,
            },
        },
    });
});
