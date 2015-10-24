<!DOCTYPE HTML>
<html>

<head>
<title>Colin Terndrup's Calculator</title>
</head>

<body>

<h1>Colin Terndrup's Calculator</h1>
<h2>CS 143 Project 1A</h2>
Type an expression in the box below and click submit!
(example: 4*7-2+50)

<ul>
<li>Only numbers and +,-,*,and / operators are allowed in the expression.</li>
<li>The evaluation follows standard operator precedence.</li>
<li>The calculator does not support parentheses.</li>
<li>The calculator handles invalid input "gracefully". It does not output PHP error message.</li>
</ul>

<!-- create form and collect user input -->
<form method="get" action="calculator.php">
<input type="text" name="expression">
<input type="submit" value="calculate">
</form>

<?php
# get user_input
$expression = $_GET["expression"];

# only display result if user enters some input
if ($expression != "") {
    echo "<h2>Result</h2>";
    # check user input for errors to validate input
    # before calling eval()
    if (preg_match("/[^\s\d\.\*\/\+\-]/",$expression) or (preg_match("/^\s+$/",$expression))) {
	echo "Invalid expression!";
    }
    elseif (preg_match("/\/0/",$expression)) {
        echo "Divide by zero error!";
    }
    else { 
        # case to handle two consecutive minus signs
        $expression = preg_replace("/--/","- -",$expression);
        # return from script if replacement error occurred
        if (is_null($expression)) { echo "ERROR!<br>"; return; }
        # evaluate arithmetic expression entered by user
        $result = eval("return $expression;");
        # display result unless it is non-numeric
        if (is_numeric($result)) { echo $expression . " = " . $result; }
	else { echo "Invalid expression!"; }
    }
}
?>

</body>
</html>
