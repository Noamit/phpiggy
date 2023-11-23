<?php include $this->resolve("partials/_header.php"); ?>
<section class="max-w-2xl mx-auto mt-12 p-4 bg-white shadow-md border border-gray-200 rounded">
    <!DOCTYPE html>
    <html>

    <head>
        <title>Expense Tracker</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>

    <body>
        <canvas id="expenseChart" width="400" height="400"></canvas>
        <script>
        var expenseLabels = <?php echo json_encode($categories);?>;
        var expenseData = <?php echo json_encode($totals);?>;
        // var _backgroundColor = [
        //     'rgba(255, 99, 132, 0.5)',
        //     'rgba(54, 162, 235, 0.5)',
        //     'rgba(255, 206, 86, 0.5)',
        //     'rgba(75, 192, 192, 0.5)'
        // ];
        // var _borderColor = [
        //     'rgba(255, 99, 132, 1)',
        //     'rgba(54, 162, 235, 1)',
        //     'rgba(255, 206, 86, 1)',
        //     'rgba(75, 192, 192, 1)'
        // ];
        var _backgroundColor = [
            'rgba(54, 162, 235, 0.5)',
        ];
        var _borderColor = [
            'rgba(54, 162, 235, 1)',
        ];

        var ctx = document.getElementById('expenseChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: expenseLabels,
                datasets: [{
                    label: 'Expenses',
                    data: expenseData,
                    backgroundColor: _backgroundColor,
                    borderColor: _borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: "Total Expense By Category"
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        </script>
    </body>

    </html>


</section>
<?php include $this->resolve("partials/_footer.php"); ?>