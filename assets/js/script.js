$(function(){
var canvas=document.getElementById("canvasInfo");
var cxt=canvas.getContext("2d");
var bloc=document.getElementById("blocCanvas1")
  // hexagon
  var numberOfSides = 5,
      size = bloc.offsetWidth/2.3,
      Xcenter = 180,
      Ycenter = 180;

  cxt.beginPath();
  cxt.moveTo (Xcenter +  size * Math.cos(0), Ycenter +  size *  Math.sin(0));

  for (var i = 1; i <= numberOfSides;i += 1) {
    cxt.lineTo (Xcenter + size * Math.cos(i * 2 * Math.PI / numberOfSides), Ycenter + size * Math.sin(i * 2 * Math.PI / numberOfSides));
  }

  cxt.strokeStyle = "rgba(253, 154, 42, 0.7)";
  cxt.lineWidth = 1;
  cxt.stroke();
});
