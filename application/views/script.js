var doc = document.documentElement;
var mouseDown = false;
var firstCornerX, firstCornerY, secondCornerX, secondCornerY;
var height, width;
var offset = 12;

var canvas, context, bounds;

//WHEN USER CLICKS ON THE CANVAS
function startRect(event) {
    mouseDown = true;
 
    //remember top left coordinates for rectangle
    firstCornerX = event.pageX - bounds.left;
    firstCornerY = event.pageY - bounds.top;
        
    //mark down the top left coordinates
    /*
    context.beginPath();
    context.moveTo(firstCornerX - offset, firstCornerY - offset);
    context.lineTo(firstCornerX + offset, firstCornerY + offset);
    context.moveTo(firstCornerX + offset, firstCornerY - offset);
    context.lineTo(firstCornerX - offset, firstCornerY + offset);
    context.stroke();
    */
  
}

//WHEN USER DRAGS MOUSE AROUND CANVAS
function updateRect(event) {
    //console.log(doc.scrollLeft + " " + doc.scrollTop);
    
    if (mouseDown == true) {     
        context.clearRect(0, 0, width, height);
    
        context.fillRect(firstCornerX, firstCornerY, event.pageX - bounds.left - firstCornerX, event.pageY - bounds.top - firstCornerY);
        context.stroke();
    }
}

//WHEN USER RELEASES LEFT CLICK
function endRect(event) {    
    mouseDown = false;
    
    secondCornerX = event.pageX - bounds.left;
    secondCornerY = event.pageY - bounds.top;
    
    firstCornerY = Math.floor(firstCornerY);
    secondCornerY = Math.floor(secondCornerY);
    
    console.log(firstCornerX + " " + firstCornerY + " " + secondCornerX + " " + secondCornerY);
    
    /*
    context.fillRect(firstCornerX, firstCornerY, event.clientX - firstCornerX, event.clientY - firstCornerY);
    context.stroke();
    
    
    context.beginPath();
    context.moveTo(event.clientX - offset, event.clientY - offset);
    context.lineTo(event.clientX + offset, event.clientY + offset);
    context.moveTo(event.clientX + offset, event.clientY - offset);
    context.lineTo(event.clientX - offset, event.clientY + offset);
    context.stroke();
    */
    
}

//GENERATE  AND INITIALISE CANVAS WHEN BUTTON IS CLICKED
$(document).ready(function() {
    $("#submitLogo").hide();
    $("#submitDate").hide();
    $("#submitAmount").hide();
    
    $("#generateCanvas").click(function() {
        $("#msg").html('Press, hold and drag your cursor across the logo to create an area, then press the Submit button corresponding to the data field covered');
        $("#submitLogo").show();
        $("#submitDate").show();
        $("#submitAmount").show();
        
        $("#canvas").append('<canvas id="myCanvas" height="600" width="430" onmousedown="startRect(event)" onmousemove="updateRect(event)" onmouseup="endRect(event)"> </canvas>');
        
        canvas = document.getElementById("myCanvas");
        
        context = canvas.getContext("2d");
        bounds = canvas.getBoundingClientRect();
        height = canvas.height;
        width = canvas.width;
        context.fillStyle = "rgba(0, 0, 235, 0.6)";
        
    });
})
