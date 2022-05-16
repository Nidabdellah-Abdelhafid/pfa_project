<?php

include("2bd2dd1077e1968492fe33619b1c9d28239cf58d.php");

$html=file_get_html('https://sehhty.com/en/ma-covid/');

$data=$html->find('div#vac',0)->innertext;
$data1=$html->find('.govTable',1)->innertext;
$data2=$html->find('div#daily_cases',0)->innertext;
$data3=$html->find('div.col-lg-4',0)->outertext;
$data4=$html->find('div.col-lg-4',1)->outertext;
$data5=$html->find('div.col-lg-4',2)->outertext;



$arr=array();

for($i=0;$i<24;$i++){
    if ($i%2 !=0){
        $arr[$i]=$html->find('div.cardlabel',$i)->plaintext;

    }
}

$arr1=array();

for($i=0;$i<12;$i++){

        $arr1[$i]=$html->find('div.cardcases',$i)->plaintext;


}



?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrapfin.min.css">
    <link rel="stylesheet" href="assets/css/thems.css" rel="stylesheet">
    <link rel="stylesheet" href="app.css">
    <title>MACOVID-19</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <style>

:root {
  --blue: #00BEC1;
}


.title {
  color: var(--blue);
  text-align: center;
}

.country_options {
  margin-left: 50%;
  transform: translate(-50%);
  height: 35px;
  -webkit-appearance: none;
  border: 2px solid var(--blue);
}


th{position:sticky;top:0px;background:#900C3F;}*,*::before,*::after{box-sizing:border-box}h6{margin-top:0;margin-bottom:0.5rem}strong{font-weight:bolder}a{color:#4768ad;text-decoration:none;background-color:transparent}a:hover{color:#0148ae;text-decoration:none}.col-lg-4 img{vertical-align:middle;border-style:none;border-radius:3px;border:1px solid #b9b7b7;width:22px;height:14px}table{border-collapse:collapse}th{text-align:inherit}h6{margin-bottom:0.5rem;font-weight:900;line-height:1.25;color:#001737}h6{font-size:0.875rem}.col-md-12,.col-lg-4{position:relative;width:100%;padding-right:15px;padding-left:15px}@media (min-width: 768px){.col-md-12{flex:0 0 100%;max-width:100%}}@media (min-width: 992px){.col-lg-4{flex:0 0 33.33333%;max-width:99.33333%}}.col-lg-4 .table{width:100%;margin-bottom:1rem;color:#5C5D60}.col-lg-4 .table th,.table td{padding:0.75rem;vertical-align:top;border-top:1px solid rgba(72, 94, 144, 0.16)}.col-lg-4 .table thead th{vertical-align:bottom;border-bottom:1px solid #E1E5EE;border-top:1px solid #E1E5EE}.col-lg-4 .table .thead-light th{color:#747474;background-color:#F7F8FA;border-color:#E1E5EE;padding:15px 10px;font-weight:700}.card{position:relative;display:flex;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:1px solid rgba(72, 94, 144, 0.16);border-radius:0.25rem}.card-header{padding:0.75rem 1.25rem;margin-bottom:0;background-color:rgba(0, 0, 0, 0.03);border-bottom:1px solid rgba(72, 94, 144, 0.16)}.card-header:first-child{border-radius:calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0}.progress{display:flex;height:1rem;overflow:hidden;font-size:0.65625rem;background-color:#e3e7ed;border-radius:0.25rem}.progress-bar{display:flex;flex-direction:column;justify-content:center;color:#fff;text-align:center;white-space:nowrap;background-color:#4768ad;transition:width 0.6s ease}@media (prefers-reduced-motion: reduce){.progress-bar{transition:none}}.text-left{text-align:left!important}.text-right{text-align:right!important}.text-primary{color:#4768ad!important}@media print{*,*::before,*::after{text-shadow:none!important;box-shadow:none!important}a:not(.btn){text-decoration:underline}thead{display:table-header-group}tr,img{page-break-inside:avoid}.table{border-collapse:collapse!important}.table td,.table th{background-color:#fff!important}}strong{font-weight:600}.card-header{background-color:transparent;border-color:rgba(72, 94, 144, 0.16)}.card-header{padding:15px}@media (min-width: 576px){.card-header{padding:15px 20px}}.row-sm>div{padding-left:10px;padding-right:10px}.progress{height:10px}.col-lg-4 .table th,.table td{padding:8px 10px;line-height:1.5}.col-lg-4 .table thead th{font-weight:500}.col-lg-4 .table thead th{border-bottom-width:1px}.col-lg-4 .thead-light{color:#fff}.col-lg-4 .thead-light th{border-top-width:0}.col-lg-4 .thead-light + tbody tr:first-child td{border-top-width:0}.col-lg-4 .thead-light{background-color:rgba(72, 94, 144, 0.16);color:#596882}.ht-5{height:5px}.mg-0{margin:0px}.mg-r-5{margin-left:5px}.mg-t-20{margin-top:20px}.pd-x-10{padding-left:10px;padding-right:10px}.caseColor1{color:#6800ff}.caseColor2{color:#1ec598}.caseColor3{color:#e85347}.caseColor5{color:#64b1bd}.caseBg1{background-color:#6800ff!important}.caseBg2{background-color:#1ec598!important}.caseBg3{background-color:#e85347!important}.caseBg5{background-color:#64b1bd!important}.table.covid-data{margin:0}.table.covid-data td{padding:10px 10px;font-size:12px}.percentage-data{display:flex;align-items:center;justify-content:flex-end}.percentage-data .progress{width:80px;border-radius:0;margin-left:10px}.percentage-data strong{min-width:42px}.mg-t-20{display:inline-block}.comparecontent{max-height:450px;overflow:auto}.comparecontent::-webkit-scrollbar{width:5px}.comparecontent::-webkit-scrollbar-thumb{border-radius:10px;background:-webkit-gradient(linear,left top,left bottom,from(#664ca2),to(#4f1ba2));box-shadow:inset 2px 2px 2px rgba(255,255,255,.25), inset -2px -2px 2px rgba(0,0,0,.25)}.comparecontent::-webkit-scrollbar-track{background-color:#fff;background:linear-gradient(to right,#c5c5c5,#bfbebf 1px,#d7d6da 1px,#c5c3c3)}


    .box {
    border: 1px solid #868282;
    margin: 3px;
    margin-bottom: 3px;
    border-radius: 5px 5px 0 0;
    background: #FCA881;
    display: inline-block;
    color: #fff;
    width: 250px;
    box-sizing: border-box;
    align-items: center;
    }

    .box3 {
    max-width: 45%;
}
div{

    font-size: 100%;
    vertical-align: baseline;
}
#table{
    margin-top: 120px;
    color:#fff;
    background: #FF6E2B;
    margin-left: 40%;
    text-align: center;
    padding: 0;
    text-indent: initial;

    border-spacing: 2px;
    border: 2px;
    font-size: 100%;
    vertical-align: baseline;
}

    .govTable {
    min-width: 45%;
    margin: 0 auto;
    display: inline-block;
    direction: rtl;}
    .boxo{
    width: 100%;
    margin-left: 0;
    margin-right: 0;
    }
    .v1{

        width: 100%;
    }
    #sectiono {
            margin-top: 100px;
            width: 75%;
            text-align: center;
            position: absolute;
        }
        #asido {
            margin-top: 120px;
            margin-left: 60%;}
            .boxo{
                width: 100%;

            }
            .bamo{
                width: 100%;
                float: left;

                text-align: center;

            }



</style>
<!--

TemplateMo 548 Training Studio

https://templatemo.com/tm-548-training-studio

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/templatemo-training-studio.css">


    </head>

    <body>

    <!-- ***** Preloader Start ***** -->
    <div id="js-preloader" class="js-preloader">
      <div class="preloader-inner">
        <span class="dot"></span>
        <div class="dots">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
    <!-- ***** Preloader End ***** -->


    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <a href="index.html" class="logo">MA<em> Covid</em></a>
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="#features">About</a></li>
                            <li class="scroll-to-section"><a href="#our-classes">Regional Risks</a></li>
                            <li class="scroll-to-section"><a href="#chart">Charts</a></li>
                            <li class="scroll-to-section"><a href="#contact-us">Contact</a></li>
                            <li class="main-button"><a href="vaccinated/admin/login.php">Sign Up</a></li>
                        </ul>
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->

    <!-- ***** Main Banner Area Start ***** -->
    <div class="main-banner" id="top">
        <img src="assets\images\imgcovid.jpg" id="bg-video">
            <source src="assets/images/gym-video.mp4" type="video/mp4" />
        </video>
        <div class="video-overlay header-text">
            <div class="caption">
                <h6>We Can Beat It Together</h6>
                <h2>Health monitoring in <em>Morocco</em></h2>
                <div class="main-button scroll-to-section">
                    <a href="untitled.html" target="_blank" >Personnal Risks</a>
                </div>
            </div>
        </div>
    </div>
    <!-- ***** Main Banner Area End ***** -->

    <!-- ***** Features Item Start ***** -->
    <section class="section" id="features">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>What You need to <em>Know</em></h2>
                        <img src="assets/images/line-dec.png" alt="waves">
                        <p>The MACOVID is a dynamic system for regional and personal COVID-19 risk assessment based on the most recent data available.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="features-items">
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="First One">
                            </div>
                            <div class="right-content">
                                <h4>National Guidelines</h4>
                                <p>Any of the hundreds of evidence-based clinical guidelines for best practice. there is a guidelines for managing the risk of spread
                                    of the Covid-19 pandemic in the workplace
                                     </p>
                                <a href="https://www.lavieeco.com/documents/guide_relative_mesures_sanitaires.pdf" class="text-button" class="text-button" target="_blank" >Discover More</a>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="second one">
                            </div>
                            <div class="right-content">
                                <h4>World updates regarding COVID-19</h4>
                                <p>There is no one perfect statistic to compare the outbreaks different countries have experienced during this pandemic.Looking at a variety of metrics gives you a more complete view of the virus’ toll on each country.</p>
                                <a href="https://edition.cnn.com/interactive/2020/health/coronavirus-maps-and-cases/" class="text-button" target="_blank">Discover More</a>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="third ">
                            </div>
                            <div class="right-content">
                                <h4> WHO Guidelines</h4>
                                <p>The World Health Organization (WHO) is building a better future for people everywhere. Health lays the foundation for vibrant and productive communities, stronger economies, safer nations and a better world</p>
                                <a href="https://www.who.int/teams/health-product-and-policy-standards/standards-and-specifications/norms-and-standards-for-pharmaceuticals/guidelines" class="text-button" target="_blank">Discover More</a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <ul class="features-items">
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="Fourth">
                            </div>
                            <div class="right-content">
                                <h4>Last 24 hours update</h4>
                                <p>Here are updates related to Covid-19 in the last 24 hours</p>
                                <a href="https://www.worldometers.info/coronavirus/country/morocco/" class="text-button" target="_blank">Discover More</a>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="fifth">
                            </div>
                            <div class="right-content">
                                <h4>COVID-19 Morocco
                                    (Total Data)</h4>
                                <p>The disease has spread to every continent and case numbers continue to rise.</p>
                                    </p>
                                <a href="http://sehati.gov.ma/" class="text-button" target="_blank">Discover More</a>
                            </div>
                        </li>
                        <li class="feature-item">
                            <div class="left-icon">
                                <img src="assets/images/features-first-icon.png" alt="six">
                            </div>
                            <div class="right-content">
                                <h4> Total Data via a Map</h4>
                                <p> This Data is collected from multiple sources that update at different times. Some places may not provide complete information.</p>
                                <a href="https://infographics.channelnewsasia.com/covid-19/map.html" class="text-button"  target="_blank">Discover More</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Features Item End ***** -->

    <!-- ***** Call to Action Start ***** -->
    <section class="section" id="call-to-action">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <h2>Change <em>Strats </em> with<em>You</em>!</h2>
                        <p>Certain measures will be needed to reduce the risk of exposure and spread of COVID-19(PHYSICAL DISTANCING,FACE COVERINGS,HAND HYGIENE..).</p>
                        <div class="main-button scroll-to-section">
                            <a href="https://www.usm.edu/student-health-services/covid-19-health-protocols.php" target="_blank">Read more</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <!-- ***** Call to Action End ***** -->

    <!-- ***** Our Classes Start ***** -->
    <section class="section" id="our-classes">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>Regional <em>Risks</em></h2>

                    </div>
                </div>
            </div>
            <div class="row" id="tabs">
              <div class="col-lg-4">
                <ul>
                    <li><a href='#tabs-0'>Map</a></a></li>
                  <li><a href='#tabs-1'>Transmission  Risk</a></li>
                  <li><a href='#tabs-2'>SocioEconomic Risk</a></a></li>
                  <li><a href='#tabs-3'>Public Health Risk</a></a></li>
                  <li><a href='#tabs-4'>Overall Risk</a></a></li>

                </ul>
              </div>
              <div class="col-lg-8">
                <section class='tabs-content'>
                  <article id='tabs-0'>
                  <!-- <div id="map">

                   </div> -->
                   <div class="container rounded alert">
<div class="row">

        <div class="col-lg-4">
        <div id="myForm" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[1]."<br/>".  $arr1[0] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm()">Close</button>';

                ?>
            </div>
        </div>

        <div class="col-lg-4">
        <div id="myForm1" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[3]."<br/>".  $arr1[1] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm1()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm2" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[5]."<br/>".  $arr1[2] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm2()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm3" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[7]."<br/>".  $arr1[3] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm3()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm4" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[9]."<br/>".  $arr1[4] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm4()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm5" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[11]."<br/>".  $arr1[5] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm5()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm6" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[13]."<br/>".  $arr1[6] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm6()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm7" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[15]."<br/>".  $arr1[7] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm7()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm8" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[17]."<br/>".  $arr1[8] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm8()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm9" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[19]."<br/>".  $arr1[9] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm9()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm10" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[21]."<br/>".  $arr1[10] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm10()">Close</button>';

                ?>
            </div>
        </div>
        <div class="col-lg-4">
        <div id="myForm11" class="alert alert-info" style="display: none;">
            <?php

                echo ''.$arr[23]."<br/>".  $arr1[11] .'cas <br><br>
                <button type="button" class="btn btn-danger" onclick="closeForm11()">Close</button>';

                ?>
            </div>
        </div>






    <div class="col-lg-12">

        <div class="bs-component">
                   <div class="map map__image" id="map">


            <svg width="100%" height="100%" viewBox="0 0 600 600" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;">
                <g transform="matrix(1,0,0,1,0,-150.166)">
                    <a id="MA-01" xlink:title="Tanger-Tétouan-Al Hoceïma"  onclick="openForm3()"> <path d="M505.091,182.589L504.892,186.859L506.041,198.086L506.195,198.152L502.255,200.168L495.946,200.872L491.886,205.353L488.042,202.629L484.853,207.196L480.646,208.863L469.044,205.177L466.305,200.334L461.938,203.157L455.035,203.297L452.987,210.197L453.268,214.479L447.364,217.28L444.398,214.698L444.195,211.412L440.184,207.794L431.143,203.926L431.007,196.757L424.616,195.599L422.689,193.654L411.977,192.597L418.39,173.967L420.41,170.051L424.166,155.313L428.854,155.229L431.125,157.004L434.659,154.417L439.282,154.501L441.866,151.162L445.108,150.166L449.411,151.162L447.848,155.252L448.236,160.122L450.504,166.518L453,167.294L457.149,173.011L462.344,177.222L471.697,182.512L484.015,185.115L500.469,179.842L505.091,182.589Z"/>
                    </a>
                    <a id="MA-02" xlink:title="L'Oriental" onclick="openForm5()"> <path d="M531.531,341.948L526.256,342.012L525.506,340.089L531.379,336.585L528.32,334.86L526.167,327.685L527.414,322.496L522.088,322.895L514.66,320.173L506.68,319.698L505.547,311.562L500.222,308.356L501.128,304.346L497.765,301.387L501.509,296.345L502.669,292.225L509.196,284.92L513.063,279.298L525.129,279.699L534.43,278.549L536.678,276.136L541.826,275.618L536.533,267.937L531.24,262.231L526.994,261.671L526.072,258.819L534.838,250.793L534.521,248.786L522.692,247.158L520.925,243.899L516.857,242.319L511.136,249.383L505.743,253.721L507.51,258.379L504.428,262.328L502.344,258.649L500.032,260.868L494.549,258.649L494.606,253.038L498.971,251.201L500.105,246.48L504.683,237.349L505.181,230.332L501.191,226.787L504.5,223.948L504.183,218.045L507.719,216.567L507.537,211.311L511.525,209.338L513.505,203.219L513.197,199.808L506.432,198.254L506.041,198.086L504.892,186.859L505.091,182.589L508.797,178.616L517.093,182.77L523.41,183.208L532.626,178.978L535.409,174.188L538.721,180.152L538.679,183.518L542.953,187.241L552.317,188.232L557.662,185.154L565.806,188.09L565.675,189.806L582.934,203.267L580.289,208.888L583.548,214.39L582.213,220.062L585.147,223.835L585.122,227.832L587.301,233.125L585.346,243.933L587.103,252.506L589.563,259.431L586.701,266.263L586.422,270.59L590.066,276.757L594.421,281.585L591.297,285.403L597.449,295.244L601.682,297.217L612,305.153L607.348,309.864L604.396,310.349L602.74,319.238L605.155,321.342L604.157,324.638L588.479,323.913L582.99,324.099L572.767,321.628L569.431,325.888L564.717,325.802L556.162,322.821L550.505,325.888L544.704,325.206L540.788,328.016L542.747,337.79L538.033,341.44L531.531,341.948Z" />
                    </a>
                    <a id="MA-03" xlink:title="Fès- Meknès" onclick="openForm6()"> <path d="M497.765,301.387L495.633,299.827L492.856,302.339L488.552,302.339L486.523,296.195L479.359,288.59L472.218,289.074L468.633,286.308L469.103,283.149L465.74,279.456L459.494,280.04L456.503,286.494L453.186,285.979L452.479,286.43L450.738,288.815L450.656,288.813L450.781,288.675L450.291,287.063L447.408,283.385L448.496,278.102L443.928,278.232L440.013,272.605L440.556,268.979L434.465,266.322L428.663,262.339L428.675,262.258L431.431,258.839L430.444,256.075L430.676,255.191L433.171,249.02L430.397,245.242L433.497,240.48L427.624,237.543L425.085,233.586L428.983,228.743L432.867,230.37L433.226,233.976L438.229,234.63L443.232,231.622L443.452,226.647L440.107,222.847L445.817,220.322L447.364,217.28L453.268,214.479L452.987,210.197L455.035,203.297L461.938,203.157L466.305,200.334L469.044,205.177L480.646,208.863L484.853,207.196L488.042,202.629L491.886,205.353L495.946,200.872L502.255,200.168L506.195,198.152L506.432,198.254L513.197,199.808L513.505,203.219L511.525,209.338L507.537,211.311L507.719,216.567L504.183,218.045L504.5,223.948L501.191,226.787L505.181,230.332L504.683,237.349L500.105,246.48L498.971,251.201L494.606,253.038L494.549,258.649L500.032,260.868L502.344,258.649L504.428,262.328L507.51,258.379L505.743,253.721L511.136,249.383L516.857,242.319L520.925,243.899L522.692,247.158L534.521,248.786L534.838,250.793L526.072,258.819L526.994,261.671L531.24,262.231L536.533,267.937L541.826,275.618L536.678,276.136L534.43,278.549L525.129,279.699L513.063,279.298L509.196,284.92L502.669,292.225L501.509,296.345L497.765,301.387Z"/>
                    </a>
                    <a id="MA-04" xlink:title="Rabat-Salé-Kénitra" onclick="openForm1()"> <path d="M447.364,217.28L445.817,220.322L440.107,222.847L443.452,226.647L443.232,231.622L438.229,234.63L433.226,233.976L432.867,230.37L428.983,228.743L425.085,233.586L427.624,237.543L433.497,240.48L430.397,245.242L433.171,249.02L430.676,255.191L430.444,256.075L431.431,258.839L428.675,262.258L428.53,263.21L430.202,269.187L428.609,270.862L413.593,267.792L412.433,262.864L408.734,263.037L405.616,270.383L408.3,274.007L401.772,277.887L398.469,273.72L391.025,272.981L391.042,272.972L391.983,266.841L391.187,262.345L388.666,256.995L388.264,257.063L383.862,258.017L384.106,254.337L380.786,246.845L378.581,245.792L384.137,242.166L391.555,233.934L400.93,217.479L407.3,204.466L411.977,192.597L422.689,193.654L424.616,195.599L431.007,196.757L431.143,203.926L440.184,207.794L444.195,211.412L444.398,214.698L447.364,217.28Z" />
                    </a>
                    <a id="MA-05" xlink:title="Béni-Mellal-Khénifra" onclick="openForm7()"> <path d="M450.781,288.675L449.91,289.641L448.441,291.188L448.605,300.324L447.245,305.012L444.473,308.605L440.524,308.238L438.878,311.557L441.844,313.987L439.777,318.275L434.61,318.787L433.142,323.519L427.567,324.062L426.317,328.531L429.09,332.805L426.785,336.235L423.45,336.32L421.13,340.564L417.068,343.109L410.38,344.126L405.685,348.786L398.795,350.986L393.977,350.227L392.835,357.574L384.568,358.25L382.069,355.667L378.84,356.813L377.58,354.056L379.798,349.04L375.51,347.169L374.649,344.296L371.227,342.167L373.852,339.292L376.752,340.48L383.278,339.801L384.873,336.065L379.994,327.531L379.802,315.284L379.847,314.338L383.673,305.742L383.922,301.686L381.841,296.748L384.22,285.314L386.532,284.024L387.823,274.898L390.179,273.451L391.025,272.981L398.469,273.72L401.772,277.887L408.3,274.007L405.616,270.383L408.734,263.037L412.433,262.864L413.593,267.792L428.609,270.862L430.202,269.187L428.53,263.21L428.663,262.339L434.465,266.322L440.556,268.979L440.013,272.605L443.928,278.232L448.496,278.102L447.408,283.385L450.291,287.063L450.781,288.675Z" />
                    </a>
                    <a id="MA-06" xlink:title="Casablanca-Settat" onclick="openForm()" > <path d="M378.581,245.792L380.786,246.845L384.106,254.337L383.862,258.017L388.264,257.063L388.666,256.995L391.187,262.345L391.983,266.841L391.042,272.972L390.179,273.451L387.823,274.898L386.532,284.024L384.22,285.314L381.841,296.748L383.922,301.686L383.673,305.742L379.847,314.338L379.825,314.79L372.788,311.808L370.931,308.763L367.758,307.801L361.063,309.147L354.86,299.259L350.354,299.215L346.563,291.857L344.042,293.109L342.918,302.819L337.982,308.285L332.472,310.32L330.98,313.813L325.464,314.868L321.766,305.294L311.613,306.406L307.843,302.983L309.221,298.099L305.02,295.919L315.39,285.831L321.21,278.445L321.336,276.219L325.579,271.675L330.225,270.982L334.002,266.848L340.541,264.741L357.948,256.354L363.535,255.127L374.698,246.96L378.581,245.792Z" />
                    </a>
                    <a id="MA-07" xlink:title="Marrakech-Safi" onclick="openForm2()"> <path d="M379.825,314.79L379.802,315.284L379.994,327.531L384.873,336.065L383.278,339.801L376.752,340.48L373.852,339.292L371.227,342.167L374.649,344.296L375.51,347.169L379.798,349.04L377.58,354.056L372.733,359.288L366.7,360.04L357.017,365.255L355.602,365.853L349.027,370.714L345.965,377.862L341.578,374.487L332.641,379.911L324.349,377.801L316.461,380.699L315.575,373.908L308.345,377.659L305.72,380.383L300.747,380.092L299.874,383.061L294.03,380.231L289.806,381.128L287.056,379.866L282.878,382.904L278.8,382.746L279.06,378.522L275.974,375.065L275.138,368.249L276.757,360.729L275.466,354.552L281.042,346.191L282.481,340.97L293.634,327.43L297.243,320.593L297.899,315.165L296.173,311.887L298.396,307.096L296.322,304.087L305.02,295.919L309.221,298.099L307.843,302.983L311.613,306.406L321.766,305.294L325.464,314.868L330.98,313.813L332.472,310.32L337.982,308.285L342.918,302.819L344.042,293.109L346.563,291.857L350.354,299.215L354.86,299.259L361.063,309.147L367.758,307.801L370.931,308.763L372.788,311.808L379.825,314.79Z" />
                    </a>
                    <a id="MA-08" xlink:title="Drâa-Tafilalet"onclick="openForm8()" > <path d="M497.765,301.387L501.128,304.346L500.222,308.356L505.547,311.562L506.68,319.698L514.66,320.173L522.088,322.895L527.414,322.496L526.167,327.685L528.32,334.86L531.379,336.585L525.506,340.089L526.256,342.012L531.531,341.948L531.586,344.746L522.231,344.683L516.575,350.083L512.877,351.796L509.942,350.137L508.715,354.861L505.161,357.566L505.161,364.235L514.157,373.467L503.961,385.417L502.862,389.543L492.149,391.208L485.495,393.693L480.295,398.858L477.335,398.902L473.029,403.215L463.625,408.957L455.117,416.612L452.888,416.729L447.34,422.537L446.308,425.406L440.706,430.392L437.552,437.922L437.116,441.279L430.481,438.979L429.503,436.118L425.913,438.481L418.19,439.974L413.079,440.036L404.106,436.003L404.837,429.32L402.389,427.14L404.292,424.021L402.661,420.433L407.171,416.491L398.854,406.206L402.502,401.418L400.576,398.673L396.092,398.793L395.251,394.88L387.32,401.024L381.471,402.35L375.649,399.457L373.61,404.813L368.852,408.597L361.822,405.092L359.697,400.189L363.231,390.612L360.785,388.726L360.921,385.107L355.891,384.793L359.969,379.596L355.618,379.28L356.026,372.814L354.53,368.709L356.241,365.583L357.017,365.255L366.7,360.04L372.733,359.288L377.58,354.056L378.84,356.813L382.069,355.667L384.568,358.25L392.835,357.574L393.977,350.227L398.795,350.986L405.685,348.786L410.38,344.126L417.068,343.109L421.13,340.564L423.45,336.32L426.785,336.235L429.09,332.805L426.317,328.531L427.567,324.062L433.142,323.519L434.61,318.787L439.777,318.275L441.844,313.987L438.878,311.557L440.524,308.238L444.473,308.605L447.245,305.012L448.605,300.324L448.441,291.188L449.91,289.641L450.656,288.813L450.738,288.815L452.479,286.43L453.186,285.979L456.503,286.494L459.494,280.04L465.74,279.456L469.103,283.149L468.633,286.308L472.218,289.074L479.359,288.59L486.523,296.195L488.552,302.339L492.856,302.339L495.633,299.827L497.765,301.387Z"/>
                    </a>
                    <a id="MA-09" xlink:title="Souss-Massa" onclick="openForm4()"> <path d="M404.106,436.003L401.175,437.828L394.086,438.482L391.941,440.381L386.938,438.922L377.749,437.742L368.371,442.459L360.609,444.112L358.851,446.967L353.498,449.347L346.876,454.799L331.838,465.192L328.831,468.423L319.884,472.823L319.895,489.832L317.971,489.56L309.677,479.253L309.54,475.247L304.782,474.322L304.51,471.083L301.383,469.386L302.878,464.29L297.44,457.949L302.471,456.402L304.238,450.205L301.111,447.26L300.431,443.069L297.848,438.72L292.138,438.098L289.418,441.36L285.883,439.341L282.756,442.292L280.581,439.808L276.23,443.225L273.783,441.205L272.967,435.609L269.432,438.098L267.975,434.039L269.784,431.039L275.529,425.376L280.757,414.649L282.894,408.696L284.429,399.333L280.429,393.078L277.392,390.131L273.35,389.225L276.535,381.081L275.974,375.065L279.06,378.522L278.8,382.746L282.878,382.904L287.056,379.866L289.806,381.128L294.03,380.231L299.874,383.061L300.747,380.092L305.72,380.383L308.345,377.659L315.575,373.908L316.461,380.699L324.349,377.801L332.641,379.911L341.578,374.487L345.965,377.862L349.027,370.714L355.602,365.853L356.241,365.583L354.53,368.709L356.026,372.814L355.618,379.28L359.969,379.596L355.891,384.793L360.921,385.107L360.785,388.726L363.231,390.612L359.697,400.189L361.822,405.092L368.852,408.597L373.61,404.813L375.649,399.457L381.471,402.35L387.32,401.024L395.251,394.88L396.092,398.793L400.576,398.673L402.502,401.418L398.854,406.206L407.171,416.491L402.661,420.433L404.292,424.021L402.389,427.14L404.837,429.32L404.106,436.003Z" />
                    </a>
                    <a id="MA-10" xlink:title="Guelmim-Oued Noun"  onclick="openForm10()"> <path d="M267.975,434.039L269.432,438.098L272.967,435.609L273.783,441.205L276.23,443.225L280.581,439.808L282.756,442.292L285.883,439.341L289.418,441.36L292.138,438.098L297.848,438.72L300.431,443.069L301.111,447.26L304.238,450.205L302.471,456.402L297.44,457.949L302.878,464.29L301.383,469.386L304.51,471.083L304.782,474.322L309.54,475.247L309.677,479.253L317.971,489.56L319.895,489.832L319.917,517.687L319.687,548.31L308.136,545.751L296.806,526.738L292.274,529.277L285.929,529.786L280.037,527.5L273.239,518.348L267.121,520.893L260.096,513L254.884,511.982L248.993,514.529L246.953,512.746L227.465,513L225.426,515.293L219.081,512.236L211.453,512.178L203.841,500.49L201.509,494.307L213.427,489.022L219.405,480.692L229.299,470.819L243.574,463.309L249.711,459.191L254.208,453.108L259.53,448.162L266.197,438.58L267.975,434.039Z" />
                    </a>
                    <a id="MA-11" xlink:title="Laâyoune-Sakia El Hamra" onclick="openForm9()" > <path d="M201.509,494.307L203.841,500.49L211.453,512.178L219.081,512.236L225.426,515.293L227.465,513L246.953,512.746L248.993,514.529L254.884,511.982L260.096,513L267.121,520.893L273.239,518.348L280.037,527.5L285.929,529.786L292.274,529.277L296.806,526.738L308.136,545.751L319.687,548.31L319.639,588.706L285.783,588.605L192.799,588.664L192.708,639.494L192.823,651.653L185.861,649.866L180.649,650.608L172.038,653.58L157.083,648.628L148.245,649.37L139.181,648.132L131.703,653.58L110.855,648.132L107.683,650.361L101.792,652.343L99.299,650.608L94.54,653.828L91.821,651.847L90.461,647.636L88.195,646.148L83.664,647.141L82.239,643.817L85.117,634.463L85.498,628.193L84.535,622.022L86.767,613.034L90.556,606.564L92.587,598.145L95.148,595.025L97.868,588.765L98.693,580.983L101.391,577.522L107.433,574.364L109.793,570.676L117.613,568.725L129.051,561.312L133.432,557.06L138.839,542.966L139.283,539.241L142.542,533.891L148.489,517.13L152.161,514.891L155.515,508.356L158.012,505.557L167.461,504.377L190.984,499.38L201.509,494.307Z" />
                    </a>
                    <a id="MA-12" xlink:title="Dakhla-Oued Ed-Dahab"  onclick="openForm11()"> <path d="M82.239,643.817L83.664,647.141L88.195,646.148L90.461,647.636L91.821,651.847L94.54,653.828L99.299,650.608L101.792,652.343L107.683,650.361L110.855,648.132L131.703,653.58L139.181,648.132L148.245,649.37L157.083,648.628L172.038,653.58L180.649,650.608L185.861,649.866L192.823,651.653L192.947,694.781L188.127,695.494L179.29,700.158L170.452,701.631L154.363,712.659L149.851,721.183L149.302,724.558L154.787,781.783L108.279,781.313L45.258,781.36L4.778,781.954L2.363,790.975L0,792L1.45,780.603L3.164,772.464L3.164,765.989L9.831,747.878L14.613,742.422L16.337,743.514L21.724,741.25L24.274,733.101L26.866,730.834L27.48,724.838L29.596,717.841L32.411,716.881L35.183,712.128L33.659,709.772L44.135,690.236L50.22,681.435L51.616,676.673L49.572,674.312L56.082,670.386L56.822,668.299L66.282,659.371L72.303,651.907L77.827,649.42L79.212,645.937L82.239,643.817Z" />
                    </a>
                </g>
            </svg>

    </div>

</div>
</div></div></div>

                    <h4> Why using Maps?</h4>
                    <p>Some may think maps are unnecessary and complicated tools, but in reality, maps simplify your life, take complex data sets and display them in a pleasing graphic you can use to answer questions about your word</p>

                  </article>

                  <article id='tabs-1'>
                    <img src="assets/images/Transmission_Risk.pnj.webp" alt="Second Training">
                    <h4>Transmission_ Risk</h4>
                    <p>requires 3 conditions (contagious individual, sensitive individual, effective contact between them) measurement in effective contact/unit.</p>
                    <!--<div class="main-button">
                        <a href="#">View Schedule</a>
                    </div>-->
                  </article>
                  <article id='tabs-2'>
                    <img src="assets/images/SocioEconomic_Risk.png" alt="Third Class">
                    <h4>SocioEconomic Risk</h4>
                    <p>The term socio-economic refers to the interplay between the social and economic habits of a group of people like the dating habits of millionaires! The prefix socio- refers to "the study of people's behaviors", including how they interact with each other or their family structures.</p>
                    <!--<div class="main-button">
                        <a href="#">View Schedule</a>
                    </div>-->
                  </article>
                  <article id='tabs-3'>
                    <img src="assets/images/Public_Health_Risk.jfif" alt="Fourth Class">
                    <h4>Public Health Risk</h4>
                    <p>A public health risk is something that is likely to be harmful to human health or contribute to disease in humans.</p>
                   <!---- <div class="main-button">
                        <a href="#">View Schedule</a>
                    </div>-->
                  </article>
                  <article id='tabs-4'>
                    <img src="assets/images/Overall_Risk.png" alt="Fourth Class">
                    <h4>Overall Risk</h4>
                    <p>the overall risk is not only the sums of the individual risks that we identify. In a certain project, but also includes other sources of uncertainty.</p>
                   <!---- <div class="main-button">
                        <a href="#">View Schedule</a>
                    </div>-->
                  </article>
                </section>
              </div>
            </div>
        </div>
    </section>

<!-- ajout-->

<div class="container rounded    mt-5 mb-5 ">
<div class="row">

    <div class="col-lg-12">

        <div class="bs-component">
   <div class="page-header ">
               <div class="alert   text-dark">

            <h4 class="alert-heading"></h4>
            <p class="mb-0 ">

            Statistics touch every aspect of modern life. They underlie many decisions by public authorities, businesses and communities. They provide information on trends and forces that affect our lives. High-quality statistics are essential for making informed decisions. And, we adhere to strict criteria in order to provide timely, accurate and consistent statistics that comply with international standards, without any external intervention.
            <b>This chart represents the number of infected cases in all countries according to the years 2020/2021/2022</b></p>
            </div>
               </div>


               </div></div></div></div>




    <!--ajoute-->
    <div class="container alert" id="chart">
            <div class="row">
                <div class="col-lg-12">
    <h1 class="title">Covid-19 Chart</h1>
<div class="chart_div"></div>
<select class="country_options">
    <option>Morocco</option>
</select>


</div>
</div>
</div>
   <!--ajoute-->

    <section id="sectiono">

        <article>
        <div style="background-color:white;color:black;" class="alert">
            <div class="v1">
            <?php echo $data ;?>
            </div>
        </div>
    </article>



    <article class="bamo"  >


                <div class="alert">
                        <?php echo $data3 ;?>


                        <?php echo $data4 ;?>


                        <?php echo $data5 ;?>
                        <?php echo $data5 ;?>




                        </div>



    </article>

    </section>

<aside id="asido">

    <table id="table" class="alert"><?php echo $data1 ;?> </table>
</aside>


<!---->
<section class="section" id="contact-us">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div id="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3336.590297406127!2d-8.436300284957523!3d33.25103528083153!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda91dc4c0413d23%3A0xc8dbb36f4b2d2cbc!2sENSA%20-%20El%20Jadida!5e0!3m2!1sfr!2sma!4v1652185143348!5m2!1sfr!2sma"  width="100%" height="600px" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <div class="contact-form">
                        <form id="contact" action="" method="post">
                          <div class="row">
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="name" type="text" id="name" placeholder="Your Name*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-6 col-sm-12">
                              <fieldset>
                                <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email*" required="">
                              </fieldset>
                            </div>
                            <div class="col-md-12 col-sm-12">
                              <fieldset>
                                <input name="subject" type="text" id="subject" placeholder="Subject">
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <textarea name="message" rows="6" id="message" placeholder="Message" required=""></textarea>
                              </fieldset>
                            </div>
                            <div class="col-lg-12">
                              <fieldset>
                                <button type="submit" id="form-submit" class="main-button">Send Message</button>
                              </fieldset>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ***** Contact Us Area Ends ***** -->

    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; 2022 ENSAJ

                    - Designed by <a rel="nofollow" href="#" class="tm-text-link" target="_parent">MACOVID</a></p>


                </div>
            </div>
        </div>
    </footer>


    <!-- jQuery -->
    <script src="assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="assets/js/scrollreveal.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/imgfix.min.js"></script>
    <script src="assets/js/mixitup.js"></script>
    <script src="assets/js/accordions.js"></script>

    <!-- Global Init -->
    <script src="assets/js/custom.js"></script>
    <script>

        function  opendialog(){
        document.getElementById("kok").setAttribute("open","true");

        }
        function closedialog(){
        document.getElementById("kok").removeAttribute("open");

        }


        </script>

     <script src="app.js"></script>


     <script>
    const countrysSelectELement = document.querySelector(".country_options");
let currentCountry;
const chartDiv = document.querySelector(".chart_div");

function displayChart(data) {
  const canvas = document.createElement('canvas');
  canvas.setAttribute("id", "myChart");
  chartDiv.appendChild(canvas);
  const dailyCases = data.map((day, index) => {
      if (index) return Math.abs(day.Confirmed - data[index - 1].Confirmed);
      else day.Confirmed;
  });

  var ctx = document.getElementById('myChart').getContext('2d');
  var chart = new Chart(ctx, {
      // The type of chart we want to create
      type: 'line',

      // The data for our dataset
      data: {
          labels: data.map(day => day.Date),
          datasets: [{
              label: 'Daily Cases',
              backgroundColor: '#BEFEFF',
              borderColor: '#00BEC1',
              data: dailyCases,
              borderWidth: 1,
          }]
      },

      // Configuration options go here
      options: {}
  });

}

function getCovidData(country) {
  const endpoint = `https://api.covid19api.com/total/dayone/country/${country}`;
  fetch(endpoint).then(response => response.json())
    .then(data => {
      chartDiv.innerHTML = "";
      displayChart(data);
    })
    .catch(err => console.warn(err));

}

function getCountries() {
  const endpoint = "https://api.covid19api.com/countries";
  fetch(endpoint).then(response => response.json())
    .then(countries => {
      countries.forEach(country => {
         const countryName = country.Country;
         const option = document.createElement("option");
         option.setAttribute("value", countryName);
         option.innerHTML = countryName;
         countrysSelectELement.appendChild(option);
      });
      currentCountry = countrysSelectELement.children[0].value;
      getCovidData(currentCountry);
    })
    .catch(err => console.warn(err));
}

getCountries();

countrysSelectELement.addEventListener("click", () => {
   const currentIndex = countrysSelectELement.selectedIndex;
   const countrySelected = countrysSelectELement.children[currentIndex].value;
    currentCountry = countrySelected;
   getCovidData(countrySelected);
});
</script>



<script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }

        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }


        function openForm1() {
            document.getElementById("myForm1").style.display = "block";
        }

        function closeForm1() {
            document.getElementById("myForm1").style.display = "none";
        }


        function openForm2() {
            document.getElementById("myForm2").style.display = "block";
        }

        function closeForm2() {
            document.getElementById("myForm2").style.display = "none";
        }


        function openForm3() {
            document.getElementById("myForm3").style.display = "block";
        }

        function closeForm3() {
            document.getElementById("myForm3").style.display = "none";
        }


        function openForm4() {
            document.getElementById("myForm4").style.display = "block";
        }

        function closeForm4() {
            document.getElementById("myForm4").style.display = "none";
        }

        function openForm5() {
            document.getElementById("myForm5").style.display = "block";
        }

        function closeForm5() {
            document.getElementById("myForm5").style.display = "none";
        }


        function openForm6() {
            document.getElementById("myForm6").style.display = "block";
        }

        function closeForm6() {
            document.getElementById("myForm6").style.display = "none";
        }


        function openForm7() {
            document.getElementById("myForm7").style.display = "block";
        }

        function closeForm7() {
            document.getElementById("myForm7").style.display = "none";
        }


        function openForm8() {
            document.getElementById("myForm8").style.display = "block";
        }

        function closeForm8() {
            document.getElementById("myForm8").style.display = "none";
        }


        function openForm9() {
            document.getElementById("myForm9").style.display = "block";
        }

        function closeForm9() {
            document.getElementById("myForm9").style.display = "none";
        }

        function openForm10() {
            document.getElementById("myForm10").style.display = "block";
        }

        function closeForm10() {
            document.getElementById("myForm10").style.display = "none";
        }
        function openForm11() {
            document.getElementById("myForm11").style.display = "block";
        }

        function closeForm11() {
            document.getElementById("myForm11").style.display = "none";
        }



    </script>


  </body>
</html>
