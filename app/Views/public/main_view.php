<!-- Showcase -->
<section class="bg-light text-dark p-4 text-center text-sm-start">
    <div class="container">
      <?php if(strlen($msg) > 0) {?>
      <div class="row pt-5">
        <div class="col">
          <?php echo $msg; ?>
        </div>
      </div>
    <?php }?>
        <div class="d-sm-flex align-items-center justify-content-between pt-5 pb-3">
            <div>
                <h1>MDARC <span class="text-warning">Membership Portal</span> </h1>
                <p class="my-4">
                  <span class="fw-bold">Field Day 2022</span> was held during the weekend of 25-26 June. More info <a href="http://www.arrl.org/field-day" target="_blank" class="text-decoration-none">here</a>.</p>
                <p class="my-4">This website is intended for MDARC (<a href="http://mdarc.org" target="_blank" class="text-decoration-none">Mount Diablo Amateur Radio Club</a>) members to check on their membership status and later will provide payment gateway, among other additional features. More on <a href="<?php echo base_url() . '/index.php/faqs'; ?>" class="text-decoration-none">FAQ page</a>. In case anyone would like to participate in the portal development, make sure you are a registered user and then let the webmaster know via the <a href="<?php echo base_url(). '/index.php/contact'?>" class="text-decoration-none">Contact Page</a>.
                </p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#login"> User Login </button>
            </div>
            <img class="img-fluid w-25 d-none d-sm-block" src="/img/mdarc-logo.png" alt="">
        </div>
    </div>
</section>

<!--Signup for user access-->
<section class="bg-primary text-light p-5">
    <div class="container">
      <form action="<?php echo base_url() . '/index.php/register' ?>" method="post">
        <div class="d-md-flex justify-content-between align-items-center">
            <h3 class="mb-3 ms-3 mb-md-0">Sign up for user access</h3>
            <div class="col-lg-6 offset-col-lg-3">
              <div class="input-group news-input">
                  <input type="text" class="form-control" name="email" id="email" placeholder="Enter Your MDARC Email">
                  <button class="btn btn-dark" type="submit" id="button-addon2">Submit</button>
              </div>
            </div>
        </div>
      </form>
    </div>
</section>

<!--Learn Sections-->
<section id="learn" class="p-5">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg">
                <img src="/img/collaborate.svg" class="img-fluid w-75 d-none d-sm-block" alt="">
            </div>
            <div class="col-lg">
                <h2>Digital Review Subcommittee</h2>
                <p class="lead">
                    MDARC established new <span class="text-decoration-underline text-success">Digital Media Review Team</span> for its websites development, social media interactions, etc.
                </p>
                <p>
                    In addition to the work on Membership portal, the development will include websites for all the MDARC activities including Pacificon, public events, license testing and monthly meetings with lectures on many ham radio topics. And that is not all by far. Learn more by accessing the <a href="<?php echo base_url() . '/index.php/faqs'; ?>" class="text-decoration-none">FAQs</a> and some technical details by clicking on this <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#tech">About</a> link.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="bg-light">
    <div class="container">
        <div class="row align-items-center justify-content-between">
          <div class="col-lg-6">
            <h2>Mount Diablo Amateur Radio Club</h2>
                <p class="lead">
                    <a href="http://mdarc.org" class="text-decoration-none" target="_blank">The Mount Diablo Amateur Radio Club (MDARC)</a> was founded on January 9, 1947.
                </p>
                <p>
                  MDARC provides a wide variety of programs and activities for its members. These include hosting and staffing PACIFICON (ARRL Pacific Division convention) each October, bringing in great speakers and offering ham radio classes to the public.
                </p>
          </div>
          <div class="col-lg-6">
            <div id="map">
            				<div id="googleMap" style="height: 310px; width: 100%; float: right">&nbsp;</div>
            					<script>
            					function myMap() {
              					var myCenter = new google.maps.LatLng(37.934490, -122.068848);
              					var mapProp = {center:myCenter, zoom:8};
              					var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
              					var marker = new google.maps.Marker({position:myCenter});
              					marker.setMap(map);
            					}
            					</script>
            					<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeOLEmQMnt6O2kEXJ7llYr1xw2y-BEm6M&callback=myMap"></script>
              </div<!-- SB Forms JS from https://startbootstrap.com/guides/bootstrap-form-setup-guide-->
          <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
          </div>
    </div>
</section>
