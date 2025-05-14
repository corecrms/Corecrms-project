if ($("#sales-statistics").length > 0) {
  var options = {
    series: [
      {
        name: "Revenue",
        data: [9, 25, 25, 20, 20, 18, 25, 15, 20, 12, 8, 20],
      },
      {
        name: "Expenses",
        data: [-10, -18, -9, -20, -20, -10, -20, -20, -8, -15, -18, -20],
      },
    ],
    grid: {
      padding: {
        top: 5, // Adds space on the left
        right: 5, // Adds space on the right
      },
    },
    colors: ["#0E9384", "#E04F16"],
    chart: {
      type: "bar",
      height: 290,
      stacked: true,
      zoom: {
        enabled: true,
      },
    },
    responsive: [
      {
        breakpoint: 280,
        options: {
          legend: {
            position: "bottom",
            offsetY: 0,
          },
        },
      },
    ],
    plotOptions: {
      bar: {
        horizontal: false,
        borderRadius: 4,
        borderRadiusApplication: "around", // "around" / "end"
        borderRadiusWhenStacked: "all", // "all"/"last"
        columnWidth: "20%",
      },
    },
    dataLabels: {
      enabled: false,
    },
    yaxis: {
      labels: {
        offsetX: -15,
        formatter: (val) => {
          return val / 1 + "K";
        },
      },
      min: -30,
      max: 30,
      tickAmount: 6,
    },
    xaxis: {
      categories: [
        " Jan ",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
      ],
    },
    legend: { show: false },
    fill: {
      opacity: 1,
    },
  };

  var chart = new ApexCharts(
    document.querySelector("#sales-statistics"),
    options
  );
  chart.render();
}

// if ($("#top-category").length > 0) {
//   var ctx = document.getElementById("top-category").getContext("2d");
//   var mySemiDonutChart = new Chart(ctx, {
//     type: "doughnut", // Chart type
//     height: "300px",
//     width: "100%",
//     data: {
//       labels: ["Lifestyles", "Sports", "Electronics"],
//       datasets: [
//         {
//           label: "Semi Donut",
//           data: [16, 24, 50],
//           backgroundColor: ["#092C4C", "#E04F16", "#FE9F43"],
//           borderWidth: 5,
//           borderRadius: 10,
//           borderColor: "#fff", // Border between segments
//           hoverBorderWidth: 0, // Border radius for curved edges
//           cutout: "50%",
//         },
//       ],
//     },
//     options: {
//       layout: {
//         padding: {
//           top: -20, // Set to 0 to remove top padding
//           bottom: -20, // Set to 0 to remove bottom padding
//         },
//       },
//       responsive: true,
//       maintainAspectRatio: false,
//       plugins: {
//         legend: {
//           display: false, // Hide the legend
//         },
//       },
//     },
//   });
// }
if ($("#top-category").length > 0) {
    async function fetchTopCategories() {
        try {
            const response = await fetch('/top-categories');
            return await response.json();
        } catch (error) {
            console.error('Error fetching top categories:', error);
            return {
                labels: ['No Data', 'Available', 'Yet'],
                data: [1, 1, 1],
                colors: ['#092C4C', '#E04F16', '#FE9F43']
            };
        }
    }

    async function initializeChart() {
        const chartData = await fetchTopCategories();

        var canvas = document.getElementById("top-category");
        canvas.height = 230; // Explicitly set height
        canvas.width = 200; // Explicitly set width


        var ctx = document.getElementById("top-category").getContext("2d");
        var mySemiDonutChart = new Chart(ctx, {
            type: "doughnut",
            // height: "300px",
            // width: "100%",
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: "Top Categories",
                    data: chartData.data,
                    backgroundColor: chartData.colors,
                    borderWidth: 5,
                    borderRadius: 10,
                    borderColor: "#fff",
                    hoverBorderWidth: 0,
                    cutout: "50%",
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: -20,
                        bottom: 0,
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} units sold (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initializeChart);
}

// if ($("#customer-chart").length > 0) {
//   var radialChart = {
//     chart: {
//       height: "300px",
//       width: "100%",
//       type: "radialBar",
//       parentHeightOffset: 0,
//       offsetX: 0, // Adjust horizontal alignment if needed
//       offsetY: 0,
//       toolbar: {
//         show: false,
//       },
//     },
//     plotOptions: {
//       radialBar: {
//         hollow: {
//           margin: 10, // Gap between radial bars
//           size: "30%", // Inner hollow size
//         },
//         track: {
//           background: "#E6EAED", // Background color for unfilled track
//           strokeWidth: "100%",
//           margin: 5, // Margin between tracks
//         },
//         strokeWidth: "rounded",
//         dataLabels: {
//           name: {
//             offsetY: -5, // Fine-tune label position
//           },
//           value: {
//             offsetY: 5, // Adjust value position
//           },
//         },
//       },
//     },
//     grid: {
//       padding: {
//         top: 0, // Remove extra padding at the top
//         bottom: 0, // Remove extra padding at the bottom
//         left: 0, // Remove extra padding on the left
//         right: 0, // Remove extra padding on the right
//       },
//     },
//     stroke: {
//       lineCap: "round", // Rounded stroke ends
//     },
//     colors: ["#E04F16", "#0E9384"],
//     series: [70, 70],
//     labels: ["Sale", "Return"],
//   };

//   var chart = new ApexCharts(
//     document.querySelector("#customer-chart"),
//     radialChart
//   );

//   //chart.render();

//   chart.render().then(() => {
//     // Access the rendered SVG here
//     const svg = document.querySelector("#customer-chart .apexcharts-svg");
//     if (svg) {
//       svg.style.height = "300px"; // Ensure SVG matches the desired height
//       svg.style.width = "auto";
//     } else {
//       console.error("SVG element not found");
//     }
//   });
// }


if ($("#customer-chart").length > 0) {
    // Fetch data from Laravel endpoint
    async function fetchCustomerSalesReturns() {
        try {
            const response = await fetch('/customer-sales-returns');
            return await response.json();
        } catch (error) {
            console.error('Error fetching sales/returns data:', error);
            return {
                series: [50, 50],
                labels: ['Sale', 'Return'],
                totalSales: 0,
                totalReturns: 0
            };
        }
    }

    // Initialize chart with real data
    async function initializeChart() {
        const chartData = await fetchCustomerSalesReturns();

        var radialChart = {
            chart: {
                height: "300px",
                width: "100%",
                type: "radialBar",
                parentHeightOffset: 0,
                offsetX: 0,
                offsetY: 0,
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 10,
                        size: "30%",
                    },
                    track: {
                        background: "#E6EAED",
                        strokeWidth: "100%",
                        margin: 5,
                    },
                    strokeWidth: "rounded",
                    dataLabels: {
                        name: {
                            offsetY: -5,
                            // fontSize: '13px',
                            // color: '#6E6B7B',
                            // fontWeight: 500
                        },
                        value: {
                            offsetY: 5,
                            // fontSize: '20px',
                            // color: '#5D596C',
                            // fontWeight: 600,
                            // formatter: function(val) {
                            //     return val + '%';
                            // }
                        },
                        // total: {
                        //     show: true,
                        //     label: 'Total',
                        //     color: '#6E6B7B',
                        //     fontSize: '13px',
                        //     formatter: function(w) {
                        //         return '100%';
                        //     }
                        // }
                    },
                },
            },
            grid: {
                padding: {
                    top: 0,
                    bottom: 0,
                    left: 0,
                    right: 0,
                },
            },
            stroke: {
                lineCap: "round",
            },
            colors: ["#E04F16", "#0E9384"],
            series: chartData.series,
            labels: chartData.labels,
            // tooltip: {
            //     enabled: true,
            //     custom: function({series, seriesIndex, dataPointIndex, w}) {
            //         const labels = w.config.labels;
            //         const value = series[seriesIndex];
            //         const amount = seriesIndex === 0 ?
            //             chartData.totalSales : chartData.totalReturns;

            //         return `
            //             <div class="p-2">
            //                 <div><strong>${labels[seriesIndex]}</strong></div>
            //                 <div>Percentage: ${value}%</div>
            //                 <div>Total Amount: $${amount.toFixed(2)}</div>
            //             </div>
            //         `;
            //     }
            // }
        };

        var chart = new ApexCharts(
            document.querySelector("#customer-chart"),
            radialChart
        );

        chart.render().then(() => {
            const svg = document.querySelector("#customer-chart .apexcharts-svg");
            if (svg) {
                svg.style.height = "300px";
                svg.style.width = "auto";
            }
        });
        // document.querySelector('#cSaleAmount').innerHTML = '$' ;
        document.querySelector('#cReturnAmount').innerHTML = '$' + parseInt(chartData.totalReturns);
        document.querySelector('#cSaleAmount').innerHTML = '$' + parseInt(chartData.totalSales);
        document.querySelector('#cSalePercentage').innerHTML = chartData.series[0] + '%';
        document.querySelector('#cReturnPercentage').innerHTML = chartData.series[1] + '%';
    }

    // Initialize the chart when the page loads
    document.addEventListener('DOMContentLoaded', initializeChart);

}