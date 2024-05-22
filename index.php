<?php
// Include the admin header
include 'banner.html';

?>
<!DOCTYPE html>
<html>
   <head>
      <title>Orient Event Organizer</title>
   </head>
   <body>
      <!-- About section start -->
      <div class="about_section layout_padding">
         <div class="container">
            <div class="about_section_2">
               <div class="row">
                  <div class="col-md-6"> 
                     <div class="about_taital_box">
                        <h1 class="about_taital">About Orient Event Organizer Ltd</h1>
                        <h1 class="about_taital_1">Event Management & Services</h1>
                        <p class="about_text">Orient Event Organizer Ltd (OEO) has been providing top-notch event management services since 2010. Specializing in workshops, training, and seminars, OEO offers fully equipped venues, including a seminar hall with a capacity of 80, three smaller halls for 30 people each, and a lab with 40 desktop computers. We handle event booking, venue selection, resource tracking, and additional services like catering and equipment rental. Our goal is to make event management seamless and efficient for our clients.</p>
                        <p class="about_text">Our team of dedicated professionals ensures that every event is executed flawlessly. We cater to various needs, whether it’s a corporate seminar or a private workshop, providing personalized services to meet specific requirements. Our state-of-the-art facilities and experienced staff guarantee a memorable and successful event every time.</p>
                     </div>
                  </div>
                  <div class="col-md-6"> 
                     <div class="image_iman"><img src="images/event-management.jpeg" class="about_img" alt="Orient Event Organizer Ltd"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- About section end -->

      <!-- Halls and Services section start -->
      <div class="halls_section layout_padding">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <h1 class="halls_taital">Our Halls</h1>
               </div>
            </div>
         </div>
         <div class="halls_section_2">
            <div id="main_slider" class="carousel slide" data-ride="carousel">
               <div class="carousel-inner">
                  <div class="carousel-item active">
                     <div class="container-fluid">
                        <div class="row">
                           <div class="col-lg-3 col-md-6">
                              <div class="hall_img"><img src="images/seminar_hall.jpg" alt="Hall 1"></div>
                              <div class="hall_box">
                                 <h3 class="types_text">Main Seminar Hall</h3>
                                 <p class="description_text">Fully equipped with modern facilities, seating capacity of 80.</p>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="hall_img"><img src="images/small_hall1.jpg" alt="Hall 2"></div>
                              <div class="hall_box">
                                 <h3 class="types_text">Small Hall A</h3>
                                 <p class="description_text">Ideal for workshops, seating capacity of 30.</p>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="hall_img"><img src="images/small_hall2.jpg" alt="Hall 3"></div>
                              <div class="hall_box">
                                 <h3 class="types_text">Small Hall B</h3>
                                 <p class="description_text">Perfect for training sessions, seating capacity of 30.</p>
                              </div>
                           </div>
                           <div class="col-lg-3 col-md-6">
                              <div class="hall_img"><img src="images/lab.jpg" alt="Lab"></div>
                              <div class="hall_box">
                                 <h3 class="types_text">Computer Lab</h3>
                                 <p class="description_text">Equipped with 40 desktop computers, ideal for IT training.</p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- Additional carousel items if needed -->
               </div>
               <a class="carousel-control-prev" href="#main_slider" role="button" data-slide="prev">
                  <i class="fa fa-arrow-left"></i>
               </a>
               <a class="carousel-control-next" href="#main_slider" role="button" data-slide="next">
                  <i class="fa fa-arrow-right"></i>
               </a>
            </div>
         </div>
      </div>
      <!-- Halls and Services section end -->

      <!-- Include Footer -->
      <?php include 'footer.html'; ?>

     
   </body>
</html>

