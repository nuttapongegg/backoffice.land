$(document).ready(function () {
  $("#datepicker").datepicker({
    format: "mm-yyyy",
    // mode: "range",
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
  });

  var date = new Date();
  slowgraphdashboard(date.getMonth() + 1, date.getFullYear());
  // slowgraphdashboard(date.getFullYear());

  var lastValue = null;
  $("#datepicker").on("change", function (e) {
    // console.log('sa',lastValue);
    if (lastValue !== e.target.value) {
      var split = e.target.value.split("-");
      // console.log(split[0]);
      // console.log(split[1]);
      slowgraphdashboard(split[0], split[1]);

      lastValue = e.target.value;
    }
  });
  function slowgraphdashboard(month, year) {
    // function slowgraphdashboard(year) {
    jQuery.ajax({
      type: "GET",
      url: "/dashboard/ajax-datadocumentday/" + month + `/` + year,
      success: function (data) {

          console.log(data);
        // console.log(data.Month_Receipt);

        // Highcharts.setOptions({
        //   lang: {
        //     thousandsSep: ','
        //   }
        // });

        var options = {
          series: [
            {
              name: "เงินสด",
              // type: "column",
              data: data.Month_Cash_Flow,
            },
            {
              name: "ต้นทุนรถ",
              // type: "column",
              data: data.Month_Car_Cost,
            },
            {
              name: "เงินรวม",
              // type: "line",
              data: data.Month_Net,
            },
            // {
            //   name: 'เงินรวม',
            //   data: data.Month_Total_Money
            // }
          ],
          chart: {
            toolbar: {
              show: false,
            },
            height: 450,
            type: "area",
            fontFamily: "Kanit, sans-serif",
            zoom: {
              enabled: false,
            },
          },
          grid: {
            borderColor: "#f2f6f7",
          },
          dataLabels: {
            enabled: false,
          },
          stroke: {
            //   width: [1.5, 1.5, 1.5],
            //   curve: ['straight', 'straight', 'straight'],
            //   lineCap: 'butt',
            //   dashArray: [0, 0, 0]
            width: [0, 0, 1.5],
            curve: "straight",
            // dashArray: [0, 0, 2],
          },
          title: {
            text: undefined,
            align: "left",
            offsetX: 110,
          },
          legend: {
            position: "top",
            tooltipHoverFormatter: function (val, opts) {
              return (
                val +
                " ( " +
                Number(opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex]).toLocaleString() +
                " )"
              );
            },
          },
          markers: {
            size: 0,
            hover: {
              sizeOffset: 6,
            },
          },
          xaxis: {
            categories: [
              "1",
              "2",
              "3",
              "4",
              "5",
              "6",
              "7",
              "8",
              "9",
              "10",
              "11",
              "12",
              "13",
              "14",
              "15",
              "16",
              "17",
              "18",
              "19",
              "20",
              "21",
              "22",
              "23",
              "24",
              "25",
              "26",
              "27",
              "28",
              "29",
              "30",
              "31",
            ],
            axisBorder: {
              color: "rgba(119, 119, 119, 0.05)",
              offsetX: 0,
              offsetY: 0,
            },
            axisTicks: {
              color: "rgba(119, 119, 119, 0.05)",
              width: 6,
              offsetX: 0,
              offsetY: 0,
            },
          },
          // tooltip: {
          //   y: [
          //     {
          //       title: {
          //         formatter: function (val) {
          //           return val;
          //         },
          //       },
          //     },
          //     {
          //       title: {
          //         formatter: function (val) {
          //           return val;
          //         },
          //       },
          //     },
          //     {
          //       title: {
          //         formatter: function (val) {
          //           return val;
          //         },
          //       },
          //     },
          //     // {
          //     //   title: {
          //     //     formatter: function (val) {
          //     //       return val;
          //     //     }
          //     //   }
          //     // }
          //   ],
          // },
          // , "#4E62B1"
          colors: ["#f754fa", "#f00", "#4E62B1"],
          fill: {
            type: "gradient",
            gradient: {
              opacityFrom: 0.6,
              opacityTo: 0.8,
            },
          },
          yaxis: {
            labels: {
              formatter: function (val, index) {
                return Number(val).toLocaleString();
                // return val.toFixed(2);
              },
            },
          },
        };

        var chart1 = new ApexCharts(
          document.querySelector("#salesReport"),
          options
        );
        chart1.render();
        chart1.destroy();

        chart1 = new ApexCharts(
          document.querySelector("#salesReport"),
          options
        );
        chart1.render();
        function salesReport() {
          chart1.updateOptions({
            colors: [`rgb(${myVarVal})`, `rgba(${myVarVal}, 0.4)`, "#FEB019"],
          });
        }
      },
      error: function (error) {
        console.log("Error: " + error);
      },
    });
  }
});
