
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