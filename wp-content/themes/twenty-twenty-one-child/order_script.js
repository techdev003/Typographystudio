 
       document.addEventListener('DOMContentLoaded', function () {
    const menuItems = document.querySelectorAll('.sidebar-menu-item');
    const contentSections = document.querySelectorAll('.content-section');

    // Initially hide Completed, Canceled, Profile, and Logout sections
    document.getElementById('Completed').style.display = 'none';
    document.getElementById('Canceled').style.display = 'none';
    document.getElementById('profile-setting').style.display = 'none';
    // document.getElementById('Logout').style.display = 'none';
    document.getElementById('payment').style.display = 'none';
     document.getElementById('billing').style.display = 'none';

    menuItems.forEach(function (menuItem) {
        menuItem.addEventListener('click', function () {
            const sectionId = menuItem.getAttribute('data-section');
            contentSections.forEach(function (section) {
                section.style.display = 'none';
            });
            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.style.display = 'block';
            }

            // Handle the Logout functionality
           jQuery("#redirectButton").on("click", function() {            // Ask for confirmation
           
                // If the user clicks OK, redirect to the desired URL
                window.location.href = "https://typographystudio.com"; // Replace with your desired URL
            
        });
        });
    });

jQuery("#continueButton").click(function(){
   
  savedAddressContainer.style.display = 'block';
});

//  const formdata = document.querySelectorAll('.add_newform');
// jQuery("#newaddbutton").click(function){
    
//     formdata.style.display = 'block';
// }


// ------------ Add new and continue with save address button js  End ----------------


    // Get the billing and shipping input fields
    const billingName = document.getElementById("billingName");
    const billingContact = document.getElementById("billingContact");
    const billingEmail = document.getElementById("billingEmail");
    const billingAddress = document.getElementById("billingAddress");
    const billingHouseNo = document.getElementById("billingHouseNo");
    const billingCity = document.getElementById("billingCity");
    const billingState = document.getElementById("billingState");
    const billingZip = document.getElementById("billingZip");

    const shippingName = document.getElementById("shippingName");
    const shippingContact = document.getElementById("shippingContact");
    const shippingEmail = document.getElementById("shippingEmail");
    const shippingAddress = document.getElementById("shippingAddress");
    const shippingHouseNo = document.getElementById("shippingHouseNo");
    const shippingCity = document.getElementById("shippingCity");
    const shippingState = document.getElementById("shippingState");
    const shippingZip = document.getElementById("shippingZip");

    const sameAddressCheckbox = document.getElementById("sameAddressCheckbox");

    // Add an event listener to the checkbox
    sameAddressCheckbox.addEventListener("change", function () {
        if (this.checked) {
            // Autofill shipping fields with billing information
            shippingName.value = billingName.value;
            shippingContact.value = billingContact.value;
            shippingEmail.value = billingEmail.value;
            shippingAddress.value = billingAddress.value;
            shippingHouseNo.value = billingHouseNo.value;
            shippingCity.value = billingCity.value;
            shippingState.value = billingState.value;
            shippingZip.value = billingZip.value;
        } else {
            // Clear the shipping fields
            shippingName.value = "";
            shippingContact.value = "";
            shippingEmail.value = "";
            shippingAddress.value = "";
            shippingHouseNo.value = "";
            shippingCity.value = "";
            shippingState.value = "";
            shippingZip.value = "";
        }
    });
});


