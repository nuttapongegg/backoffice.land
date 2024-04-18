
$(document).ready(function () {
  var date = new Date();
  slowgraph(date.getFullYear());

  var lastValue = null;
  $("#datepicker").on("change", function(e) {
      // console.log('sa',lastValue);
      if(lastValue !== e.target.value){
      // console.log(e.target.value);
      slowgraph(e.target.value);
      lastValue = e.target.value;
      }
  })

  function slowgraph(year) {
    jQuery.ajax({
      type: 'GET',
      url: '/report/ajax-ajaxdatareportprofitmonth/'+ year,
      success: function (data) {

        // console.log(data.Month_Revenue);
        // console.log(aaa);
        //Sessions By Device Chart
        var options = {
          plotOptions: {
            pie: {
              size: 10,
              donut: {
                size: '70%'
              }
            }
          },
          dataLabels: {
            enabled: false,
          },

          series: data.Month_Revenue,
          labels: ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"],
          chart: {
            type: 'donut',
            fontFamily: 'poppins, sans-serif',
            height: 220
          },
          legend: {
            show: false
          },
          colors: ['#ADFF2F', '#00CCFF', '#990000', '#990099', '#FFFF33', '#FF6600', '#FF1493', '#8B4513', '#191970', '#2F4F4F', '#FFD700', '#DA70D6'],
          responsive: [{
            breakpoint: 0,
            options: {
              chart: {
                width: 100,
              },
              legend: {
                show: false,
                position: 'bottom'
              }
            },
          }]
        };
        var chart2 = new ApexCharts(document.querySelector("#sessionsDevice"), options);
        chart2.render();
        chart2.destroy();

        chart2 = new ApexCharts(document.querySelector("#sessionsDevice"), options);
        chart2.render();
        function sessionsDevice() {
          chart2.updateOptions({
            colors: [`rgb(${myVarVal})`, '#2dce89', '#ffda82'],
          })
        }
      },
      error: function (error) {
        console.log('Error: ' + error);
      }
    });
  };
})


// Sessions By Country Chart
// var options = {
//   series: [{
//     name: 'Sessions',
//     data: [400, 430, 470, 540, 1100, 1200, 1380]
//   }],
//   chart: {
//     toolbar: {
//       show: false
//     },
//     type: 'bar',
//     fontFamily: 'poppins, sans-serif',
//     height: 330
//   },
//   grid: {
//     borderColor: '#f2f6f7',
//   },
//   plotOptions: {
//     bar: {
//       borderRadius: 4,
//       horizontal: true,
//       barHeight: "30%",
//       borderRadius: 3,
//     }
//   },
//   colors: ['#000'],
//   dataLabels: {
//     enabled: false
//   },
//   xaxis: {
//     categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'United States', 'China', 'Germany'],
//   }
// };
