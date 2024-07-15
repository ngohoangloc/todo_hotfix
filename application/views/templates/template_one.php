<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Todo</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>




<section style="margin-top: 200px;">
	<div class="row">
		<div class="col" style="background: red;">
			A
		</div>
		<div class="col" style="background: green;">
			A
		</div>
	</div>
</section>


<iframe src="https://www.facebook.com/plugins/video.php?height=314&href=https%3A%2F%2Fwww.facebook.com%2Fitdnc%2Fvideos%2F391514030417134%2F&show_text=false&width=560&t=0" width="560" height="314" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>




<!DOCTYPE html>
<html>
<head>
<title>Create Dynamic form Using JavaScript</title>
<script src="js/form.js" type="text/javascript"></script>
<link href="form.css" rel="stylesheet" type="text/css">
</head>
<body>




<style type="text/css">
/*	body{
width:960px;
margin:45px auto;
background-color:#f9ebe8
}
form{
width:330px;
border-top:1px dotted #D9D9D9;
margin:10px 180px
}
button{
width:246px;
height:40px;
color:#4C4C4C;
margin-bottom:20px;
margin-left:20px
}
input{
width:280px;
height:40px;
padding:5px;
margin:20px 0 10px;
border-radius:5px;
border:4px solid #acbfa5
}
input[type = submit]{
width:100px;
background-color:#35c8ef;
border-radius:5px;
border:2px solid blue;
color:#fff
}
textarea{
width:280px;
height:70px;
padding:5px;
margin:20px 0 10px;
border-radius:5px;
border:4px solid #acbfa5
}
.four p{
text-align:center;
color:#fff;
padding:15px 0
}
.first p{
padding:15px;
color:#fff
}
.two{
background-color:#fff;
width:290px;
float:left;
height:600px
}
.main_content{
width:960px;
height:auto;
background-color:#fff
}
.two h4{
color:#4C4C4C;
text-align:center
}
.three{
text-align:center;
width:660px;
border-left:1px solid #D0D0D0;
float:left;
background-color:#fff
}
.four,.first{
width:960px;
clear:both;
background-color:#41A2CD;
height:55px
}*/
</style>



<div class="main_content">
<!--
========================================================================================
Header Div.
========================================================================================
-->
<div class="first">
<p><a href="https://www.formget.com/app/"><img id="logo" src="logo.png">
</a> Online form builder.</p>
</div>
<!--
======================================================================================
This Div is for the Buttons. When user click on buttons, respective field will appear.
=======================================================================================
-->
<div class="two">
<h4>Frequently Used Form Fields</h4><button onclick="nameFunction()">Name</button>
<button onclick="emailFunction()">Email</button>
<button onclick="contactFunction()">Contact</button>
<button onclick="textareaFunction()">Message</button>
<button onclick="resetElements()">Reset</button>
</div>
<!--
========================================================================================
This Div is meant to display final form.
========================================================================================
-->
<div class="three">
<h2>Your Dynamic Form!</h2>
<form action="#" id="mainform" method="get" name="mainform">
<span id="myForm"></span>
<p></p><input type="submit" value="Submit">
</form>
</div>
<!--
========================================================================================
Footer Div.
========================================================================================
-->
<div class="four">
<p>2014 <a href="https://www.formget.com/app/"><img src="logo.png">
</a> All rights reserved.</p>
</div>
</div>





<script type="text/javascript">
	var i = 0; /* Set Global Variable i */
function increment(){
i += 1; /* Function for automatic increment of field's "Name" attribute. */
}
/*
---------------------------------------------

Function to Remove Form Elements Dynamically
---------------------------------------------

*/
function removeElement(parentDiv, childDiv){
if (childDiv == parentDiv){
alert("The parent div cannot be removed.");
}
else if (document.getElementById(childDiv)){
var child = document.getElementById(childDiv);
var parent = document.getElementById(parentDiv);
parent.removeChild(child);
}
else{
alert("Child div has already been removed or does not exist.");
return false;
}
}
/*
----------------------------------------------------------------------------

Functions that will be called upon, when user click on the Name text field.

----------------------------------------------------------------------------
*/
function nameFunction(){
var r = document.createElement('span');
var y = document.createElement("INPUT");
y.setAttribute("type", "text");
y.setAttribute("placeholder", "Name");
var g = document.createElement("IMG");
g.setAttribute("src", "delete.png");
increment();
y.setAttribute("Name", "textelement_" + i);
r.appendChild(y);
g.setAttribute("onclick", "removeElement('myForm','id_" + i + "')");
r.appendChild(g);
r.setAttribute("id", "id_" + i);
document.getElementById("myForm").appendChild(r);
}
/*
-----------------------------------------------------------------------------

Functions that will be called upon, when user click on the E-mail text field.

------------------------------------------------------------------------------
*/
function emailFunction(){
var r = document.createElement('span');
var y = document.createElement("INPUT");
y.setAttribute("type", "text");
y.setAttribute("placeholder", "Email");
var g = document.createElement("IMG");
g.setAttribute("src", "delete.png");
increment();
y.setAttribute("Name", "textelement_" + i);
r.appendChild(y);
g.setAttribute("onclick", "removeElement('myForm','id_" + i + "')");
r.appendChild(g);
r.setAttribute("id", "id_" + i);
document.getElementById("myForm").appendChild(r);
}
/*
-----------------------------------------------------------------------------

Functions that will be called upon, when user click on the Contact text field.

------------------------------------------------------------------------------
*/
function contactFunction(){
var r = document.createElement('span');
var y = document.createElement("INPUT");
y.setAttribute("type", "text");
y.setAttribute("placeholder", "Contact");
var g = document.createElement("IMG");
g.setAttribute("src", "delete.png");
increment();
y.setAttribute("Name", "textelement_" + i);
r.appendChild(y);
g.setAttribute("onclick", "removeElement('myForm','id_" + i + "')");
r.appendChild(g);
r.setAttribute("id", "id_" + i);
document.getElementById("myForm").appendChild(r);
}
/*
-----------------------------------------------------------------------------

Functions that will be called upon, when user click on the Message textarea field.

------------------------------------------------------------------------------
*/
function textareaFunction(){
var r = document.createElement('span');
var y = document.createElement("TEXTAREA");
var g = document.createElement("IMG");
y.setAttribute("cols", "17");
y.setAttribute("placeholder", "message..");
g.setAttribute("src", "delete.png");
increment();
y.setAttribute("Name", "textelement_" + i);
r.appendChild(y);
g.setAttribute("onclick", "removeElement('myForm','id_" + i + "')");
r.appendChild(g);
r.setAttribute("id", "id_" + i);
document.getElementById("myForm").appendChild(r);
}
/*
-----------------------------------------------------------------------------

Functions that will be called upon, when user click on the Reset Button.

------------------------------------------------------------------------------
*/
function resetElements(){
document.getElementById('myForm').innerHTML = '';
}
</script>







</body>
</html>