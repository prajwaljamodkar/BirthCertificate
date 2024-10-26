<!DOCTYPE html>
<html>
<head>
    <title>Birth Certificate</title>
</head>
<body>

<form action="pdf.php" method="post">
    <h2>Fill the Details:</h2>
    <p style="color:Red"><b>*All details are mandatory.</b></p>

    <label for="fname"><b>First Name:</b></label><br>
    <input type="text" placeholder="Enter First Name" name="fname" required /><br><br>

    <label for="mname"><b>Middle Name:</b></label><br>
    <input type="text" placeholder="Enter Middle Name" name="mname" required /><br><br>

    <label for="lname"><b>Last Name:</b></label><br>
    <input type="text" placeholder="Enter Last Name" name="lname" required /><br><br>

    <label for="birth"><b>Pick Your Birth Date:</b></label><br>
    <input type="date" id="birthday" name="birthdate" required /><br><br>

    <label for="bplace"><b>Birth Place:</b></label><br>
    <input type="text" placeholder="Enter Birth Place" name="bplace" required /><br><br>

    <label for="gender"><b>Gender:</b></label><br>
    <input type="radio" name="gender" value="Female" required> Female
    <input type="radio" name="gender" value="Male"> Male
    <input type="radio" name="gender" value="Other"> Other<br><br>

    <label for="frname"><b>Father's Name:</b></label><br>
    <input type="text" placeholder="Enter Father's Name" name="frname" required /><br><br>

    <label for="mrname"><b>Mother's Name:</b></label><br>
    <input type="text" placeholder="Enter Mother's Name" name="mrname" required /><br><br>

    <label for="caste"><b>Religion:</b></label><br>
    <input type="radio" name="caste" value="Hinduism" required> Hindu
    <input type="radio" name="caste" value="Buddhism"> Buddha
    <input type="radio" name="caste" value="Islam"> Muslim
    <input type="radio" name="caste" value="Christianity"> Christian
    <input type="radio" name="caste" value="Other"> Other<br><br>

    <label for="category"><b>Category:</b></label><br>
    <input type="radio" name="category" value="Open" required> Open
    <input type="radio" name="category" value="OBC"> OBC
    <input type="radio" name="category" value="SC"> SC
    <input type="radio" name="category" value="ST"> ST
    <input type="radio" name="category" value="Other"> Other<br><br>

    <input type="submit" name="submit" value="Create Birth Certificate" />
</form>

</body>
</html>
