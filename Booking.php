
<?php
include 'header.html';
?>
<!DOCTYPE html>
<html>
   <head>
       <title>Booking an Event</title>
  
   </head>
   <body>
    
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
      <img src="images/banner-bg.jpg" alt="Slide 2">
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
      
  
   </body>
</html>

<?php
include 'footer.html';
?>