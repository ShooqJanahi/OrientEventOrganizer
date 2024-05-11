<!DOCTYPE html>
<html>
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>Booking an Event</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- bootstrap css -->
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
      <!-- style css -->
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <!-- Responsive-->
      <link rel="stylesheet" href="css/responsive.css">
      <!-- fevicon -->
      <link rel="icon" href="images/fevicon.png" type="image/gif" />
      <!-- font css -->
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
      <!-- Scrollbar Custom CSS -->
      <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
      <!-- Tweaks for older IEs-->
      <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
      
     <style>
    /* CSS styles */

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 500px;
      border-radius: 5px;
    }

    .modal-content h2 {
      margin-top: 0;
    }

    .modal-content p {
      margin-bottom: 10px;
    }

    .modal-content ul {
      list-style: none;
      padding: 0;
    }

    .modal-content li {
      margin-bottom: 5px;
    }

    .modal-content label {
      margin-left: 5px;
    }

    .modal-content button {
      margin-top: 10px;
    }

    #menuDetails {
      display: none;
      margin-top: 20px;
    }

    #menuDetails h4 {
      margin-top: 0;
    }

    #menuContent p {
      margin-bottom: 5px;
    }
  </style>
  
   </head>
   <body>
      <div class="header_section header_bg">
         <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
               <a class="navbar-brand"href="index.html"><img src="images/logo.png"></a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav ml-auto">
                     <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="Events.php">EVENTS</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="blog.php">Blog</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                     </li>
                  </ul>
                  <form class="form-inline my-2 my-lg-0">
                     <div class="login_bt">
                        <ul>
                           <li><a href="#"><span class="user_icon"><i class="fa fa-user" aria-hidden="true"></i></span>Login</a></li>
                           <li><a href="#"><i class="fa fa-search" aria-hidden="true"></i></a></li>
                        </ul>
                     </div>
                  </form>
               </div>
            </nav>
         </div>
      </div>
      <!-- header section end -->
      <!-- coffee section start -->
      <div class="coffee_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <h1 class="coffee_taital">Booking an Event</h1>
               </div>
            </div>
         </div>
         <div class="coffee_section_2">
           <!-- <div id="main_slider" class="carousel slide" data-ride="carousel">
                <div><img src="images/Main-Scroll-2.jpg"></div>
                <div><img src="images/Main-Scroll-3.jpg"></div
            </div>-->
           
           <div id="main_slider" class="carousel slide" data-ride="carousel">
  <!-- Slider Images -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images/about-img.png" alt="Slide 1">
    </div>
    <div class="carousel-item">
      <img src="images/Main-Scroll-2.jpg" alt="Slide 2">
    </div>
   <!-- <div class="carousel-item">
      <img src="images/banner-img.png" alt="Slide 3">
    </div>-->
  </div>

  <!-- Navigation Buttons -->
  <a class="carousel-control-prev" href="#main_slider" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#main_slider" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
           
           <!-- HTML Form -->
<form id="bookingForm">
  <button type="button" onclick="proceedBooking()">Proceed</button>
  <button type="button" onclick="cancelBooking()">Cancel</button>
</form>

<!-- Catering Modal -->
<div id="cateringModal" style="display: none;">
  <h2>Catering Options</h2>
  <label for="cateringSelection">Select Catering:</label>
  
  
  
<input type="checkbox" id="noneCheckbox" name="cateringSelection" value="none">
<label for="noneCheckbox">None</label><br>
<input type="checkbox" id="breakfastCheckbox" name="cateringSelection" value="breakfast">
<label for="breakfastCheckbox">Breakfast</label><br>
<input type="checkbox" id="lunchCheckbox" name="cateringSelection" value="lunch">
<label for="lunchCheckbox">Lunch</label><br>
<input type="checkbox" id="hotBeveragesCheckbox" name="cateringSelection" value="hotBeverages">
<label for="hotBeveragesCheckbox">Hot Beverages</label><br>
<input type="checkbox" id="coldBeveragesCheckbox" name="cateringSelection" value="coldBeverages">
<label for="coldBeveragesCheckbox">Cold Beverages</label><br>

<div id="menuDetails" style="display: none;">
  <h3>Selected Catering:</h3>
  <h4>Breakfast: <span id="breakfastPrice">0</span> BD per person</h4>
  <ul id="breakfastList"></ul>
  <h4>Lunch: <span id="lunchPrice">0</span> BD per person</h4>
  <ul id="lunchList"></ul>
  <h4>Hot Beverages: <span id="hotBeveragesPrice">0</span> BD per person</h4>
  <ul id="hotBeveragesList"></ul>
  <h4>Cold Beverages: <span id="coldBeveragesPrice">0</span> BD per person</h4>
  <ul id="coldBeveragesList"></ul>
</div>
  
<!----------------------------------->
  <br>
  <div id="menuDetails" style="display: none;">
    <h4>Menu Details:</h4>
    <div id="menuContent"></div>
  </div>
  <br>
  <button type="button" onclick="confirmCatering()">Confirm</button>
  <button type="button" onclick="cancelCatering()">Cancel</button>
</div>

<!-- JavaScript -->
<script>
function proceedBooking() {

    // Show the catering modal
    document.getElementById("cateringModal").style.display = "block";
    
    // Reset the catering selection dropdown
    document.getElementById("cateringSelection").selectedIndex = -1;
  
}

function cancelBooking() {
  window.location.href = "index.php";
}



// Add an event listener to each checkbox
var cateringSelection = document.getElementsByName("cateringSelection");
for (var i = 0; i < cateringSelection.length; i++) {
  cateringSelection[i].addEventListener("change", confirmCatering);
}

function confirmCatering() {
  var selectedCatering = [];

  for (var i = 0; i < cateringSelection.length; i++) {
    if (cateringSelection[i].checked) {
      selectedCatering.push(cateringSelection[i].value);
    }
  }

  var menuDetails = document.getElementById("menuDetails");
  menuDetails.innerHTML = "";

  if (selectedCatering.length > 0) {
    menuDetails.innerHTML = "<h3>Selected Catering:</h3>";

    var totalPrice = 0;

    for (var j = 0; j < selectedCatering.length; j++) {
      var option = selectedCatering[j];
      var price = 0;
      var listItem = "";

      if (option === "breakfast") {
        price = 5;
        listItem = "<li>Croissant</li>" +
                   "<li>Scrambled Eggs</li>" +
                   "<li>Fruit Salad</li>";
      } else if (option === "lunch") {
        price = 8;
        listItem = "<li>Sandwiches</li>" +
                   "<li>Salad</li>" +
                   "<li>Soup</li>";
      } else if (option === "hotBeverages") {
        price = 2;
        listItem = "<li>Coffee</li>" +
                   "<li>Tea</li>" +
                   "<li>Hot Chocolate</li>";
      } else if (option === "coldBeverages") {
        price = 3;
        listItem = "<li>Iced Tea</li>" +
                   "<li>Lemonade</li>" +
                   "<li>Soda</li>";
      }

      if (listItem !== "") {
        menuDetails.innerHTML +=
          "<h4>" + capitalizeFirstLetter(option) + "</h4>" +
          "<ul>" + listItem + "</ul>" +
          "<p>Price per person: " + price + " BD</p>";
        totalPrice += price;
      }
    }

    menuDetails.innerHTML += "<h4>Total Price: " + totalPrice*30 + " BD</h4>";
  }

  menuDetails.style.display = selectedCatering.length > 0 ? "block" : "none";
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
/*function confirmCatering() {
  var cateringSelection = document.getElementsByName("cateringSelection");
  var selectedCatering = [];

  for (var i = 0; i < cateringSelection.length; i++) {
    if (cateringSelection[i].checked) {
      selectedCatering.push(cateringSelection[i].value);
    }
  }

  if (selectedCatering.length > 0) {
    var menuDetails = document.getElementById("menuDetails");
    menuDetails.innerHTML = "<h3>Selected Catering:</h3>";

    for (var j = 0; j < selectedCatering.length; j++) {
      var option = selectedCatering[j];
      var price = 0;
      var listItem = "";

      if (option === "breakfast") {
        price = 5;
        listItem = "<li>Croissant</li>" +
                   "<li>Scrambled Eggs</li>" +
                   "<li>Fruit Salad</li>";
      } else if (option === "lunch") {
        price = 8;
        listItem = "<li>Sandwiches</li>" +
                   "<li>Salad</li>" +
                   "<li>Soup</li>";
      } else if (option === "hotBeverages") {
        price = 2;
        listItem = "<li>Coffee</li>" +
                   "<li>Tea</li>" +
                   "<li>Hot Chocolate</li>";
      } else if (option === "coldBeverages") {
        price = 3;
        listItem = "<li>Iced Tea</li>" +
                   "<li>Lemonade</li>" +
                   "<li>Soda</li>";
      }

      if (listItem !== "") {
        menuDetails.innerHTML +=
          "<h4>" + capitalizeFirstLetter(option) + "</h4>" +
          "<ul>" + listItem + "</ul>";
      }
    }

    menuDetails.innerHTML += "<h4>Price: " + getPriceText(selectedCatering) + " BD per person</h4>";
    menuDetails.style.display = "block";
  }
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}*/

function getPriceText(selectedCatering) {
  var priceMap = {
    breakfast: 5,
    lunch: 8,
    hotBeverages: 2,
    coldBeverages: 3
  };
  var totalPrice = 0;
  
  for (var i = 0; i < selectedCatering.length; i++) {
    var option = selectedCatering[i];
    if (priceMap.hasOwnProperty(option)) {
      totalPrice += priceMap[option];
    }
  }

  return totalPrice;
}


function cancelCatering() {
  document.getElementById("cateringModal").style.display = "none";
}

// Example function to retrieve menu details based on the selected option
function getMenuDetails(cateringSelection) {
  var menuDetails = [];

  if (cateringSelection === "breakfast") {
    menuDetails.push({ name: "Breakfast Option 1", price: 10 });
    menuDetails.push({ name: "Breakfast Option 2", price: 12 });
  } else if (cateringSelection === "lunch") {
    menuDetails.push({ name: "Lunch Option 1", price: 15 });
    menuDetails.push({ name: "Lunch Option 2", price: 18 });
  } else if (cateringSelection === "hotBeverages") {
    menuDetails.push({ name: "Hot Beverage 1", price: 3 });
    menuDetails.push({ name: "Hot Beverage 2", price: 4 });
  } else if (cateringSelection === "coldBeverages") {
    menuDetails.push({ name: "Cold Beverage 1", price: 2 });
    menuDetails.push({ name: "Cold Beverage 2", price: 3 });
  }

  return menuDetails;
}

// Add event listener for the "Proceed" button click event
document.getElementById("bookingForm").addEventListener("submit", function(event) {
  event.preventDefault(); // Prevent form submission
  proceedBooking();
});
</script>
           
              <!--<div class="carousel-inner">
                  <div class="carousel-item active">
                     <div class="container-fluid">
                        <div class="row">
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/Main-Scroll-2.jpg"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">TYPES OF COFFEE</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-2.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">BEAN VARIETIES</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-3.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">COFFEE & PASTRY</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-4.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">COFFEE TO GO</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="carousel-item">
                     <div class="container-fluid">
                        <div class="row">
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-1.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">TYPES OF COFFEE</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-2.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">BEAN VARIETIES</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-3.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">COFFEE & PASTRY</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-4.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">COFFEE TO GO</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="carousel-item">
                     <div class="container-fluid">
                        <div class="row">
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-1.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">TYPES OF COFFEE</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-2.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">BEAN VARIETIES</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-3.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">COFFEE & PASTRY</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="coffee_img"><img src="images/img-4.png"></div>
                              <div class="coffee_box">
                                 <h3 class="types_text">COFFEE TO GO</h3>
                                 <p class="looking_text">looking at its layout. The point of</p>
                                 <div class="read_bt"><a href="#">Read More</a></div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>-->
               <a class="carousel-control-prev" href="#main_slider" role="button" data-slide="prev">
               <i class="fa fa-arrow-left"></i>
               </a>
               <a class="carousel-control-next" href="#main_slider" role="button" data-slide="next">
               <i class="fa fa-arrow-right"></i>
               </a>
            </div>
         </div>
      </div>
      <!-- coffee section end -->
      <!-- footer section start -->
      <div class="footer_section layout_padding margin_top90">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="footer_social_icon">
                     <ul>
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                     </ul>
                  </div>
                  <div class="location_text">
                     <ul>
                        <li>
                           <a href="#">
                           <i class="fa fa-phone" aria-hidden="true"></i><span class="padding_left_10">+01 1234567890</span>
                           </a>
                        </li>
                        <li>
                           <a href="#">
                           <i class="fa fa-envelope" aria-hidden="true"></i><span class="padding_left_10">demo@gmail.com</span>
                           </a>
                        </li>
                     </ul>
                  </div>
                  <div class="form-group">
                     <textarea class="update_mail" placeholder="Your Email" rows="5" id="comment" name="Your Email"></textarea>
                     <div class="subscribe_bt"><a href="#"><i class="fa fa-arrow-right" aria-hidden="true"></i></a></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- footer section end -->
      <!-- copyright section start -->
      <div class="copyright_section">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <p class="copyright_text">2020 All Rights Reserved. Design by <a href="https://html.design">Free Html Templates</a>
                     Distribution by <a href="https://themewagon.com">ThemeWagon</a></p>
               </div>
            </div>
         </div>
      </div>
      <!-- copyright section end -->
      <!-- Javascript files-->
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.bundle.min.js"></script>
      <script src="js/jquery-3.0.0.min.js"></script>
      <script src="js/plugin.js"></script>
      <!-- sidebar -->
      <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
      <script src="js/custom.js"></script>
   </body>
</html>