<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js" integrity="sha512-b3xr4frvDIeyC3gqR1/iOi6T+m3pLlQyXNuvn5FiRrrKiMUJK3du2QqZbCywH6JxS5EOfW0DY0M6WwdXFbCBLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function loadSalesChart(){
        var data=JSON.parse(document.getElementById('sales-data').value);
        console.log(data);
        var ctx = document.getElementById('myChart').getContext('2d');
        if(data.type!= 5 && data.type!= 1){
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: data.sales_label,
                        data: data.sales,
                        backgroundColor: '#0C7CE6',
                        borderColor: '#0C7CE6',
                        borderWidth: 1
                    },{
                        label: data.return_label,
                        data: data.return,
                        backgroundColor: '#DD5145',
                        borderColor: '#DD5145',
                        borderWidth: 1
                    }]
                },
                options: {

                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMin: 0,
                            suggestedMax: 100 ,
                            ticks: {
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return 'Rs. ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }else{
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Sales Report'],
                    datasets: [{
                        label: data.sales_label,
                        data: [data.sales],
                        backgroundColor: '#0C7CE6',
                        borderColor: '#0C7CE6',
                        borderWidth: 1
                    },{
                        label: data.return_label,
                        data:[ data.return],
                        backgroundColor: '#DD5145',
                        borderColor: '#DD5145',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis:'y',
                    scales: {
                        x: {
                            beginAtZero: true,
                            suggestedMin: 0,
                            suggestedMax: 100 ,
                            ticks: {
                                // Include a dollar sign in the ticks
                                callback: function(value, index, values) {
                                    return 'Rs. ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
    }
</script>
