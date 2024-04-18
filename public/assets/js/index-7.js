// $(document).ready(function () {
//   $("#datepicker").datepicker({
//     format: "yyyy",
//     // mode: "range",
//     viewMode: "years",
//     minViewMode: "years",
//     autoclose: true
//   });

//   var date = new Date();
//   slowgraphdashboard(date.getFullYear());

//   var lastValue = null;
//   $("#datepicker").on("change", function(e) {
//       // console.log('sa',lastValue);
//       if(lastValue !== e.target.value){
//       // console.log(e.target.value);
//       slowgraphdashboard(e.target.value);
//       lastValue = e.target.value;
//       }
//   })

//   function slowgraphdashboard(year) {
//     jQuery.ajax({
//       type: 'GET',
//       url: '/dashboard/ajax-datadocumentmonth/'+year,
//       success: function (data) {

//         // console.log(data.Month_Receipt);

//         var options = {
//           series: [{
//             name: "รายรับ",
//             data: data.Month_Receipt
//           },
//           {
//             name: "รายจ่าย",
//             data: data.Month_Pay
//           },
//           {
//             name: "กำไร/ขาดทุน",
//             data: data.Month_Net
//           },
//             // {
//             //   name: 'เงินรวม',
//             //   data: data.Month_Total_Money
//             // }
//           ],
//           chart: {
//             toolbar: {
//               show: false
//             },
//             height: 445,
//             type: 'line',
//             fontFamily: 'Kanit, sans-serif',
//             zoom: {
//               enabled: false
//             },
//           },
//           grid: {
//             borderColor: '#f2f6f7',
//           },
//           dataLabels: {
//             enabled: false
//           },
//           stroke: {
//             width: [1.5, 1.5, 1.5],
//             curve: ['straight', 'straight', 'straight'],
//             lineCap: 'butt',
//             dashArray: [0, 0, 0]
//           },
//           title: {
//             text: undefined,
//           },
//           legend: {
//             position: 'top',
//             tooltipHoverFormatter: function (val, opts) {
//               return val + ' - ' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + ''
//             }
//           },
//           markers: {
//             size: 0,
//             hover: {
//               sizeOffset: 6
//             }
//           },
//           xaxis: {
//             categories: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
//             axisBorder: {
//               color: 'rgba(119, 119, 119, 0.05)',
//               offsetX: 0,
//               offsetY: 0,
//             },
//             axisTicks: {
//               color: 'rgba(119, 119, 119, 0.05)',
//               width: 6,
//               offsetX: 0,
//               offsetY: 0
//             },
//           },
//           tooltip: {
//             y: [
//               {
//                 title: {
//                   formatter: function (val) {
//                     return val
//                   }
//                 }
//               },
//               {
//                 title: {
//                   formatter: function (val) {
//                     return val
//                   }
//                 }
//               },
//               {
//                 title: {
//                   formatter: function (val) {
//                     return val
//                   }
//                 }
//               }
//               // {
//               //   title: {
//               //     formatter: function (val) {
//               //       return val;
//               //     }
//               //   }
//               // }
//             ]
//           },
//           // , "#4E62B1"
//           colors: ['#5AD494', '#FD0002', "#F6F006"],
//         };
//         var chart1 = new ApexCharts(document.querySelector("#salesReport"), options);
//         chart1.render();
//         chart1.destroy();

//         chart1 = new ApexCharts(document.querySelector("#salesReport"), options);
//         chart1.render();
//         function salesReport() {
//           chart1.updateOptions({
//             colors: [`rgb(${myVarVal})`, `rgba(${myVarVal}, 0.4)`, "#fd7e14"],
//           })
//         }
//       },
//       error: function (error) {
//         console.log('Error: ' + error);
//       }
//     });
//   };
// })
// var customerName = myObj["Month_Discount"];
// var mydata = JSON.parse(data);
// console.log(customerName);
// console.log(receipts);
// console.log(receipt);
// console.log(payment);
// console.log(discount);
// console.log(profit);

$(function () {
  // Data Table
  $('#productList').DataTable({
    language: {
      searchPlaceholder: 'Search here...',
      sSearch: '',
      lengthMenu: '_MENU_',
    }
  });

  //select2
  $('.select2').select2({
    placeholder: 'Choose one',
    searchInputPlaceholder: 'Search'
  });
})