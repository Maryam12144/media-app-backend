let a;
let time;
setInterval(() => {
  a = new Date();
  time = "PST " + a.getHours() + '<div class="colon">:</div>' + (a.getMinutes() < 10 ? '0' : '') + a.getMinutes();
  document.getElementById("time").innerHTML = time;
}, 1000);

// setInterval(function () {
//   // toggle the class every five second
//   $(".breaking").toggleClass("breaking1");
//   setTimeout(function () {
//     // toggle back after 1 second
//     $(".breaking").toggleClass("breaking1");
//   }, 15000);
// }, 30000);

myInterval = setInterval(setColor, 30000);
function setColor() {
  // // toggle the class every five second
  // $(".tick").toggleClass("tickerBG")
  //   ? setTimeout(function () {
  //       // toggle back after 1 second
  //       $(".tick").toggleClass("");
  //     }, 15000)
  //   : $(".tick").toggleClass("tickerBG");
}

// myInterval = setInterval(animatedBreak, 6000);
// function animatedBreak() {
//   // toggle the class every five second
//   $(".animatedBreaking").toggleClass("animatedTick")
//     ? setTimeout(function () {
//         // toggle back after 1 second
//         $(".animatedBreaking").toggleClass("animatedTick");
//       }, 15000)
//     : $(".animatedBreaking").toggleClass("animatedTick");
// }

// myInterval = setInterval(setColor, 1000);

// function setColor() {
//   $(".tick").addClass("tickerBG")
//     ? $(".tick").removeClass("tickerBG")
//     : $(".tick").addClass("tickerBG");
// }

// $(document).ready(function () {
//   $(".tick").load(function () {
//     $(this).toggleClass("tickerBG");
//   });
// });

// function stopColor() {
//   clearInterval(myInterval);
// }

  // setInterval(function () {
  //   // toggle the class every five second
  //   $(".animatedBreaking").toggleClass("animatedTick");
  //   setTimeout(function () {
  //     // toggle back after 1 second
  //     $(".animatedBreaking").toggleClass("animatedTick");
  //   }, 10000);
  // }, 30000);


