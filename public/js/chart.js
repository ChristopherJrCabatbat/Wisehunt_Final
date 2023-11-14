// charts.js

// Bar Chart/Graph
var ctx = document.getElementById('chart').getContext('2d');
var userChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: datasets,
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value, index, values) {
                        return '₱ ' + value; // Add the peso sign to each label
                    },
                },
                title: {
                    display: false,
                },
            },
        },
    },
});

// Pie Chart/Graph
var ctxPie = document.getElementById('pieChart').getContext('2d');
var quantitySoldChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: productLabels,
        datasets: productDatasets,
    },
    options: {
        legend: {
            position: 'top', // Adjust the position as needed
        },
    },
});
