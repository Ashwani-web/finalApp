 <!DOCTYPE html>
<html>
<body>
<style>
	form {
  border: 3px solid #f1f1f1;
}

/* Full-width inputs */
input[type=text], input[type=password],input[type=email] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}


/* Set a style for all buttons */
button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

/* Add a hover effect for buttons */
button:hover {
  opacity: 0.8;
}

/* Extra style for the cancel button (red) */
.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

/* Center the avatar image inside this container */
.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

/* Avatar image */
img.avatar {
  width: 40%;
  border-radius: 50%;
}

/* Add padding to containers */
.container {
  padding: 16px;
}

/* The "Forgot password" text */
span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
    display: block;
    float: none;
  }
  .cancelbtn {
    width: 100%;
  }
}
.title1{
	text-align:center;
	background-color: #52af50;
    padding: 10px;
    font-size: 20px;
    color: #fff;
}
</style>


<div class="container" style="padding: 100px 370px 0px 370px;">

<form  method="POST"  action="{{url('api/reset')}}" style="padding: 10px 50px 10px 50px; ">
  {{ csrf_field() }}
<h2 class="title1">Forgot Password</h2>
  <div class="container">

     
   <label for="email"><b>Your Email</b></label>
    <input type="email"  name="email" value="{{ $email }}" readonly>
    <label for="uname"><b>New Password</b></label>
    
    <input id="password" type="password" class="form-control" name="password" required>

    <label for="psw"><b>Confirm Password</b></label>
    <!-- <input type="password" placeholder="Confirm Password" name="password" required> -->
     <input id="password-confirm" type="password" class="form-control" name="confirm_password" required>

    <button type="submit">Submit</button>
     
  </div>

   
</form>
</div>
</body>
</html>