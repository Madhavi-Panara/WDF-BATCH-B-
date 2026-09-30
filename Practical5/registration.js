const form=document.getElementById("registrationForm");

const firstName=document.getElementById("firstName");
const lastName=document.getElementById("lastName");
const email=document.getElementById("email");
const studentId=document.getElementById("studentId");
const course=document.getElementById("course");
const year=document.getElementById("year");
const mobile=document.getElementById("mobile");
const password=document.getElementById("password");
const confirmPassword=document.getElementById("confirmPassword");
const terms=document.getElementById("terms");

const passwordStrength=document.getElementById("passwordStrength");

password.addEventListener("input",function() {
    const value=password.value;

    if(value.length===0) {
        passwordStrength.textContent="";
    }
    else if(value.length<6) {
        passwordStrength.textContent="Weak";
        passwordStrength.style.color="red";
    }
    else if(value.length<10) {
        passwordStrength.textContent="Medium";
        passwordStrength.style.color="orange";
    }
    else {
        passwordStrength.textContent="Strong";
        passwordStrength.style.color="green";
    }
});

form.addEventListener("submit",function(event) {

    event.preventDefault();

    document.querySelectorAll(".error").forEach(function(error) {
        error.textContent="";
    });

    let valid=true;

    const namePattern=/^[A-Za-z ]+$/;
    const mobilePattern=/^[0-9]{10}$/;
    const studentIdPattern=/^[A-Za-z0-9]+$/;

    if(firstName.value.trim()==="") {
        document.getElementById("firstNameError").textContent="First name is required.";
        valid=false;
    }
    else if(!namePattern.test(firstName.value.trim())) {
        document.getElementById("firstNameError").textContent="Enter a valid first name.";
        valid=false;
    }

    if(lastName.value.trim()==="") {
        document.getElementById("lastNameError").textContent="Last name is required.";
        valid=false;
    }
    else if(!namePattern.test(lastName.value.trim())) {
        document.getElementById("lastNameError").textContent="Enter a valid last name.";
        valid=false;
    }

    if(email.value.trim()==="") {
        document.getElementById("emailError").textContent="Email is required.";
        valid=false;
    }
    else if(!email.validity.valid) {
        document.getElementById("emailError").textContent="Enter a valid email address.";
        valid=false;
    }

    if(studentId.value.trim()==="") {
        document.getElementById("studentIdError").textContent="Student ID is required.";
        valid=false;
    }
    else if(!studentIdPattern.test(studentId.value.trim())) {
        document.getElementById("studentIdError").textContent="Enter a valid Student ID.";
        valid=false;
    }

    if(course.value==="") {
        document.getElementById("courseError").textContent="Please select a course.";
        valid=false;
    }

    if(year.value==="") {
        document.getElementById("yearError").textContent="Please select your year.";
        valid=false;
    }

    if(mobile.value.trim()==="") {
        document.getElementById("mobileError").textContent="Mobile number is required.";
        valid=false;
    }
    else if(!mobilePattern.test(mobile.value.trim())) {
        document.getElementById("mobileError").textContent="Enter a valid 10-digit mobile number.";
        valid=false;
    }

    if(password.value==="") {
        document.getElementById("passwordError").textContent="Password is required.";
        valid=false;
    }
    else if(password.value.length<6) {
        document.getElementById("passwordError").textContent="Password must contain at least 6 characters.";
        valid=false;
    }

    if(confirmPassword.value==="") {
        document.getElementById("confirmPasswordError").textContent="Please confirm your password.";
        valid=false;
    }
    else if(password.value!==confirmPassword.value) {
        document.getElementById("confirmPasswordError").textContent="Passwords do not match.";
        valid=false;
    }

    if(!terms.checked) {
        document.getElementById("termsError").textContent="You must accept the terms and conditions.";
        valid=false;
    }

    if(valid) {
        alert("Account created successfully!");
        form.submit();
    }
});