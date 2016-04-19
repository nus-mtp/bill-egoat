<<<<<<< HEAD
var doc = document.documentElement;
var mouseDown = false;
var firstCornerX, firstCornerY, secondCornerX, secondCornerY;
var logoCoords, logoFCX, logoFCY, logoSCX, logoSCY;
var dateCoords, dateFCX, dateFCY, dateSCX, dateSCY;
var amountCoords, amountFCX, amountFCY, amountSCX, amountSCY;
var submissionArray;
=======
//Written by Justin

//Draw a rectangle on top of the bill image and save the coordinates of the rectangle as template coordinate



var doc = document.documentElement;
var mouseDown = false;
var firstCornerX, firstCornerY, secondCornerX, secondCornerY;
>>>>>>> e0c8e7d8a03d6bd7a968056f8c78ae26165a5ef0
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

<<<<<<< HEAD
// WHEN USER CLICKS ON SUBMIT LOGO BUTTON by James
$('#submitLogo').click(
    function(){
        logoFCX = firstCornerX;
        logoFCY = firstCornerY;
        logoSCX = secondCornerX;
        logoSCY = secondCornerY;
        logoCoords = [logoFCX,logoFCY,logoSCX,logoSCY];
    }
)

// WHEN USER CLICKS ON SUBMIT DATE BUTTON by James
$('#submitDate').click(
    function(){
        dateFCX = firstCornerX;
        dateFCY = firstCornerY;
        dateSCX = secondCornerX;
        dateSCY = secondCornerY;
        dateCoords = [dateFCX,dateFCY,dateSCX,dateSCY];
    }
)

// WHEN USER CLICKS ON SUBMIT AMOUNT BUTTON by James
$('#submitAmount').click(
    function(){
        amountFCX = firstCornerX;
        amountFCY = firstCornerY;
        amountSCX = secondCornerX;
        amountSCY = secondCornerY;
        amountCoords = [amountFCX,amountFCY,amountSCX,amountSCY];
    }
)

$("#saveCoords").click(
    function() {
        submissionArray = [logoCoords,dateCoords,amountCoords];
        $.post("https://www.billegoat.gq/index.php/Templates/saveTemplateCoords", {'submissionArray': submissionArray})
            .done(function( data ) {
                alert( "Data Loaded: " + data );
              });
    })

=======
>>>>>>> e0c8e7d8a03d6bd7a968056f8c78ae26165a5ef0
//GENERATE  AND INITIALISE CANVAS WHEN BUTTON IS CLICKED
$(document).ready(function() {
    $("#submitLogo").hide();
    $("#submitDate").hide();
    $("#submitAmount").hide();
<<<<<<< HEAD
    $("#saveCoords").hide();
=======
>>>>>>> e0c8e7d8a03d6bd7a968056f8c78ae26165a5ef0
    
    $("#generateCanvas").click(function() {
        $("#msg").html('Press, hold and drag your cursor across the logo to create an area, then press the Submit button corresponding to the data field covered');
        $("#submitLogo").show();
        $("#submitDate").show();
        $("#submitAmount").show();
<<<<<<< HEAD
        $("#saveCoords").show();
=======
>>>>>>> e0c8e7d8a03d6bd7a968056f8c78ae26165a5ef0
        
        $("#canvas").append('<canvas id="myCanvas" height="600" width="430" onmousedown="startRect(event)" onmousemove="updateRect(event)" onmouseup="endRect(event)"> </canvas>');
        
        canvas = document.getElementById("myCanvas");
        
        context = canvas.getContext("2d");
        bounds = canvas.getBoundingClientRect();
        height = canvas.height;
        width = canvas.width;
        context.fillStyle = "rgba(0, 0, 235, 0.6)";
        
    });
})
