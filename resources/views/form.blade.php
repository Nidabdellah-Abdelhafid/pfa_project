<?php

   // LA RECUPERATION DES DONNEES A PARTIR DU FORMILAIRE:

   $age = filter_input(INPUT_GET, 'age');
   $gender = filter_input(INPUT_GET, 'gender');
   //$city = filter_input(INPUT_GET, 'city');
   if(isset($_GET['occupation'])){
    $occupation1 = $_GET['occupation'];
   //13
   //echo "$occupation1[0] + $occupation1[1] + $occupation1[2] + $occupation1[3] + $occupation1[4] + $occupation1[5] + $occupation1[6]+ $occupation1[7]+ $occupation1[8]+ $occupation1[9]+ $occupation1[10]+ $occupation1[11]+ $occupation1[12]";
   if(isset($_GET['exposure'])){
   $exposure1 = $_GET['exposure'];
   //9
   //echo "$exposure1[0] +$exposure1[1] +$exposure1[2] + $exposure1[3] + $exposure1[4] + $exposure1[5] + $exposure1[6] + $exposure1[7] + $exposure1[8]";
   if(isset($_GET['safety'])){
   $safety1 = $_GET['safety'];
   //5
   //echo "$safety1[0] + $safety1[1] + $safety1[2] + $safety1[3] + $safety1[4]";
   if(isset($_GET['existing'])){
   $existing1 = $_GET['existing'];
   //9
   //echo "$existing1[0] + $existing1[1] + $existing1[2] + $existing1[3] +$existing1[4] +$existing1[5] +$existing1[6] +$existing1[7] +$existing1[8]";

   if(isset($_GET['symptom'])){
    $symptom1 = $_GET['symptom'];
   //11
   //echo "$symptom1[0] + $symptom1[1] + $symptom1[2] + $symptom1[3] + $symptom1[4] + $symptom1[5] + $symptom1[6] + $symptom1[7] + $symptom1[8] + $symptom1[9] + $symptom1[10]";



//////////////////////////// age risk factor /////////////////////////////////////
   $alpha = 8.947;
   $beta = 1.492;
   $x = (intval($age)-45.11)/27.32;
   $age_risk_factor = $alpha*exp($beta*$x);

   if($age_risk_factor > 100){
            $age_risk_factor = 100;
        }
   //echo "$age_risk_factor";
 ///////////////////////////////////////////////////////////////////////////////////

 //////////////////////////// comorbidities_factor /////////////////////////////////////
   $a = -0.0000340646579722815;
   $b = 0.0067296534381777200;
   $c = 0.6356245655700390000;
   $comorbidities_factor = ($a*pow($age,2))+($b*$age)+$c;
   //echo "$comorbidities_factor";
 ///////////////////////////////////////////////////////////////////////////////////



///////////////////////////////// CRF //////////////////////////////////////////////
$health = array("Asthma/Chronic obstructive pulmonary disease" => 100, "Cardiovascular/Coronary heart disease" => 99, "Renal Disease" => 90, "Hypertension" => 54, "Cancer(including treated within 3 years)" => 54, "Diabetes" => 69, "Obesity" => 54, "Cerebrovascular disease" => 48, "None" => 0);
//echo $health[$existing1[0]];
if(isset($existing1[0])){
  $crf0 =  $health[$existing1[0]];
 }
else{
  $crf0 = 0;
}
if(isset($existing1[1])){
  $crf1 =  $health[$existing1[1]];
 }
else{
  $crf1 = 0;
}
if(isset($existing1[2])){
  $crf2 =  $health[$existing1[2]];
 }
 else{
  $crf2 = 0;
}
if(isset($existing1[3])){
  $crf3 =  $health[$existing1[3]];
 }
else{
  $crf3 = 0;
}
if(isset($existing1[4])){
  $crf4 =  $health[$existing1[4]];
 }
else{
  $crf4 = 0;
}
if(isset($existing1[5])){
  $crf5 =  $health[$existing1[5]];
 }
else{
  $crf5 = 0;
}
if(isset($existing1[6])){
  $crf6 =  $health[$existing1[6]];
 }
else{
  $crf6 = 0;
}
 if(isset($existing1[7])){
  $crf7 =  $health[$existing1[7]];
 }
else{
  $crf7 = 0;
}
 if(isset($existing1[8])){
  $crf8 =  $health[$existing1[8]];
 }
else{
  $crf8 = 0;
}

$crf =  $crf0 + $crf1 + $crf2 +$crf3 +$crf4 +$crf5 +$crf6 +$crf7 +$crf8;
// echo "$crf";
if($crf > 100){
  $crf = 100;
}
//////////////////////////////////////////////////////////////////////////////////////

////////////////////////////// total_covid_risk_index  //////////////////////////////////
 //if gender is female. multiply covid risk index by factor of 0.4224
 $total_covid_risk_index = $age_risk_factor*((1-$comorbidities_factor)+($comorbidities_factor*$crf/100));

 if($gender == "Female"){
     $total_covid_risk_index = $total_covid_risk_index*0.4224;
 }


 //normalizing probability calculation formula

 if($total_covid_risk_index < 0){
     $total_covid_risk_index = 0;
 }
 if($total_covid_risk_index > 100){
     $total_covid_risk_index = 100;
 }
 //echo "$total_covid_risk_index";
//////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////// COVID RISK INDEX //////////////////////////////////////
 // normalizing covid risk index
 if($total_covid_risk_index >= 0 && $total_covid_risk_index < 6){
  $covid_risk_index = 0+$total_covid_risk_index*3.333333333;
}else if($total_covid_risk_index > 6 && $total_covid_risk_index <= 15){
  $covid_risk_index = 20+($total_covid_risk_index-6)*2.2222222;
}else if($total_covid_risk_index > 15 && $total_covid_risk_index <= 28){
  $covid_risk_index = 40+($total_covid_risk_index-15)*1.53846;
}else if($total_covid_risk_index > 28 && $total_covid_risk_index <= 48){
  $covid_risk_index = 60+($total_covid_risk_index-28);
}else if($total_covid_risk_index > 48){
  $covid_risk_index = 80+($total_covid_risk_index-48)*0.38462;
}
//echo "$covid_risk_index";
//////////////////////////////////////////////////////////////////////////////////////////



/////////////////////////////// Symptoms /////////////////////////////////////////////

$symptoms = array("High fever" => 100,"Cough" => 87 ,"Chills" => 13 ,"Fatigue" => 34 ,"Shortness of breathe" => 57,"Throat pain" => 18 ,"Headache" => 17 ,"Body pain (Myalgia)" => 24 ,"Nausea and Vomitting" => 13,"Nasal congestion" => 12 ,"Diarrhoea" => 14 ,"Sputum Production" => 31 ,"Chest pain" => 17,"None" => 0);

if(isset($symptom1[0])){
  $ss0 = $symptoms[$symptom1[0]];
 }
else{
  $ss0 = 0;
}
if(isset($symptom1[1])){
  $ss1 = $symptoms[$symptom1[1]];
 }
else{
  $ss1 = 0;
}
if(isset($symptom1[2])){
  $ss2 = $symptoms[$symptom1[2]];
 }
else{
  $ss2 = 0;
}
if(isset($symptom1[3])){
  $ss3 = $symptoms[$symptom1[3]];
 }
else{
  $ss3 = 0;
}
if(isset($symptom1[4])){
  $ss4 = $symptoms[$symptom1[4]];
 }
else{
  $ss4 = 0;
}
if(isset($symptom1[5])){
  $ss5 = $symptoms[$symptom1[5]];
 }
else{
  $ss5 = 0;
}
if(isset($symptom1[6])){
  $ss6 = $symptoms[$symptom1[6]];
 }
else{
  $ss6 = 0;
}
if(isset($symptom1[7])){
  $ss7 = $symptoms[$symptom1[7]];
 }
else{
  $ss7 = 0;
}
if(isset($symptom1[8])){
  $ss8 = $symptoms[$symptom1[8]];
 }
else{
  $ss8 = 0;
}
if(isset($symptom1[9])){
  $ss9 = $symptoms[$symptom1[9]];
 }
else{
  $ss9 = 0;
}
if(isset($symptom1[10])){
  $ss10 = $symptoms[$symptom1[10]];
 }
else{
  $ss10 = 0;
}

$ss = $ss0 + $ss1 + $ss2 + $ss3 + $ss4 + $ss5 + $ss6 + $ss7 + $ss8 + $ss9 + $ss10 ;
//echo "$ss";
///////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////

$occupations = array("Driver and other transport work" => 100 , "Medical Doctor" => 20 ,"Nurse and other health worker" => 67 , "Shops or supermarket salesperson" => 50 , "Domestic housekeeper/cleaner" => 50 ,"Religious professional" => 40 , "Teacher/Trainer/Professor" => 40 ,"Travel attendent/guide" => 33, "Security/Police/Fire fighter" => 20 ,"Receptionists/Bar tenders" => 20 ,"Agriculture" => 20 , "Others" => 10 , "None" => 0);
if(isset($occupation1[0])){
  $occ0 = $occupations[$occupation1[0]];
 }
else{
  $occ0 = 0;
}
if(isset($occupation1[1])){
  $occ1 = $occupations[$occupation1[1]];
 }
else{
  $occ1 = 0;
}
if(isset($occupation1[2])){
  $occ2 = $occupations[$occupation1[2]];
 }
else{
  $occ2 = 0;
}
if(isset($occupation1[3])){
  $occ3 = $occupations[$occupation1[3]];
 }
else{
  $occ3 = 0;
}
if(isset($occupation1[4])){
  $occ4 = $occupations[$occupation1[4]];
 }
else{
  $occ4 = 0;
}
if(isset($occupation1[5])){
  $occ5 = $occupations[$occupation1[5]];
 }
else{
  $occ5 = 0;
}
if(isset($occupation1[6])){
  $occ6 = $occupations[$occupation1[6]];
 }
else{
  $occ6 = 0;
}
if(isset($occupation1[7])){
  $occ7 = $occupations[$occupation1[7]];
 }
else{
  $occ7 = 0;
}
if(isset($occupation1[8])){
  $occ8 = $occupations[$occupation1[8]];
 }
else{
  $occ8 = 0;
}
if(isset($occupation1[9])){
  $occ9 = $occupations[$occupation1[9]];
 }
else{
  $occ9 = 0;
}
if(isset($occupation1[10])){
  $occ10 = $occupations[$occupation1[10]];
 }
else{
  $occ10 = 0;
}
if(isset($occupation1[11])){
  $occ11 = $occupations[$occupation1[11]];
 }
else{
  $occ11 = 0;
}
if(isset($occupation1[12])){
  $occ12 = $occupations[$occupation1[12]];
 }
else{
  $occ12 = 0;
}
$weight_factor_values_for_occupation = array($occ0,$occ2, $occ2, $occ3, $occ4, $occ5, $occ6, $occ7, $occ8, $occ9, $occ10, $occ11, $occ12);

if(!isset($weight_factor_values_for_occupation)){
  $weight_factor_values_for_occupation = 0;
}
/////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////
//weight factor values for exposure
$exposures = array("Travelled to COVID-19 infected area recently" => 100,"Met with known COVID19 infected person" => 100, "Have to be in proximity of less than 2 meters in workplace (or in field, as part of job) with public" => 90, "Need" =>70, "Need to attend meetings in person occasionally" => 80, "Have to be in proximity of less than 2 meters in workplace with other colleagues"=> 60, "Have more than one family members, who need to go out and meet with other people" => 50, "Have a family member, who need to go out and meet with other people" => 25,"None" => 0);

if(isset($exposure1[0])){
  $exp0 = $exposures[$exposure1[0]];
 }
else{
  $exp0 = 0;
}
if(isset($exposure1[1])){
  $exp1 = $exposures[$exposure1[1]];
 }
else{
  $exp1 = 0;
}
if(isset($exposure1[2])){
  $exp2 = $exposures[$exposure1[2]];
 }
else{
  $exp2 = 0;
}
if(isset($exposure1[3])){
  $exp3 = $exposures[$exposure1[3]];
 }
else{
  $exp3 = 0;
}
if(isset($exposure1[4])){
  $exp4 = $exposures[$exposure1[4]];
 }
else{
  $exp4 = 0;
}
if(isset($exposure1[5])){
  $exp5 = $exposures[$exposure1[5]];
 }
else{
  $exp5 = 0;
}
if(isset($exposure1[6])){
  $exp6 = $exposures[$exposure1[6]];
 }
else{
  $exp6 = 0;
}
if(isset($exposure1[7])){
  $exp7 = $exposures[$exposure1[7]];
 }
else{
  $exp7 = 0;
}
if(isset($exposure1[8])){
  $exp8 = $exposures[$exposure1[8]];
 }
else{
  $exp8 = 0;
}

$weight_factor_values_for_exposure = array($exp0 ,$exp1 ,$exp2 ,$exp3 ,$exp4 ,$exp5 ,$exp6 ,$exp7 ,$exp8);

$exposuresum = array_sum($weight_factor_values_for_exposure);
if(!isset($weight_factor_values_for_exposure)){
         $weight_factor_values_for_exposure = 0;
     }

////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////
 //weight factor values for safety measures

$safetys = array("Wear mask when going to crowded areas" => -14, "Sanitize hands using hand washing or sanitizer" => -15, "Maintain physical distance when going outside" => -10, "Use sunglasses/face shields" => -11, "None" => 10);

if(isset($safety1[0])){
  $saf0 = $safetys[$safety1[0]];
 }
else{
  $saf0 = 0;
}

if(isset($safety1[1])){
  $saf1 = $safetys[$safety1[1]];
 }
else{
  $saf1 = 0;
}

if(isset($safety1[2])){
  $saf2 = $safetys[$safety1[2]];
 }
else{
  $saf2 = 0;
}

if(isset($safety1[3])){
  $saf3 = $safetys[$safety1[3]];
 }
else{
  $saf3 = 0;
}

if(isset($safety1[4])){
  $saf4 = $safetys[$safety1[4]];
 }
else{
  $saf4 = 0;
}



$weight_factor_values_for_safety_measures = array($saf0, $saf1, $saf2, $saf3, $saf4);
if(!isset($weight_factor_values_for_safety_measures)){
        $weight_factor_values_for_safety_measures = 0;
    }

////////////////////////////////////////////////////////////////////////////////////////


$merge_occupation_exposure_safetymeasure = array_merge($weight_factor_values_for_occupation, $weight_factor_values_for_exposure, $weight_factor_values_for_safety_measures);
//echo "merge_occupation_exposure_safetymeasure === $merge_occupation_exposure_safetymeasure";
$sum_occupation_exposure_safetymeasure = array_sum($merge_occupation_exposure_safetymeasure);
//echo "sum_occupation_exposure_safetymeasure == $sum_occupation_exposure_safetymeasure";


$probability_of_covid_infection = $sum_occupation_exposure_safetymeasure;




if($probability_of_covid_infection < 0){
            $probability_of_covid_infection = 0;
        }

        if($probability_of_covid_infection > 100){
            $probability_of_covid_infection = 100;
        }




$regional_risk = ($probability_of_covid_infection/$exposuresum)*100;


//////////////////////////////////////////////////////////////////////////////////////
}
else {
  echo "erreur symptom";
}
}
else{
  echo "erreur existing";
}
}
else{
  echo "erreur safety";
}
}
else{
  echo "erreur exposure";
}
}
else{
  echo "erreur occupation";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Design by foolishdeveloper.com -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrapfin.min.css">

    <title>Personal Risk</title>
    <link rel="stylesheet" href="assets/css/t1.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>

<style>
   @keyframes progress {
  0% { --percentage: 0; }
  100% { --percentage: var(--value); }
}

@property --percentage {
  syntax: '<number>';
  inherits: true;
  initial-value: 0;
}

[role="progressbar"] {
  --percentage: var(--value);
  --primary: #369;
  --secondary: #adf;
  --size: 300px;
  animation: progress 2s 0.5s forwards;
  width: var(--size);
  aspect-ratio: 2 / 1;
  position: relative;
  display: flex;
  align-items: flex-end;
  justify-content: center;
}

[role="progressbar"]::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: conic-gradient(from 0.75turn at 50% 100%, green 90deg, yellow, red 180deg);
  mask: radial-gradient(at 50% 100%, white 55%, transparent 0);
  mask-mode: alpha;
  -webkit-mask: radial-gradient(at 50% 100%, #0000 55%, #000 0);
  -webkit-mask-mode: alpha;
  overflow: hidden;
  border-radius: 50% / 100% 100% 0 0;
}

[role="progressbar"]::after {
  content: "";
  width: 60%;
  height: 10%;
  position: absolute;
  bottom: -5%;
  left: 0;
  background:
    radial-gradient(circle at 83.33333% 50%, #fff 4%, #000 0 5%, #0000 0),
    conic-gradient(at -20% 50%, #0000 88deg, #000 88.25deg 91.75deg, #0000 92deg);
  transform-origin: 83.33333% 50%;
  transform: rotate(calc(var(--value) * 180deg / 100))
}

/* demo */
body {
  margin: 0;
  display: grid;
  place-items: center;
  height: 100vh;

}


</style>

</head>

   <body>



<div class="mt-3">
<div class="alert alert-warning mt-10">
   <div class="container rounded alert  mb-2 ">
<div class="row ">

    <div class="col-lg-12">

        <div class="bs-component ">

<table  class="table table-light  alert text-dark">
<thead>
<tr>

      <th ><div class="icon lab1">
            <div class="tooltip alert">
               definition
            </div>
            <span><i class="fab"></i>age risk factor</span>
         </div></th>
      <th><div class="icon lab2">
            <div class="tooltip  alert">
            definition
            </div>
            <span><i class="fab"></i>COVID RISK INDEX</span>
         </div></th>
      <th ><div class="icon lab3">
            <div class="tooltip alert">
            definition
            </div>
            <span><i class="fab"></i>probability of covid infection</span>
         </div></th>
      <th>
            <div class="icon lab4">
            <div class="tooltip alert">
            The INFORM COVID-19 Risk Index is an experimental adaptation of the INFORM Epidemic Risk Index and aims to identify<br>
            </div>
            <span><i class="fab"></i>regional risk</span>

         </div>
         </th>

</tr>
</thead>
<tbody>
<tr>
<td style="height:  220px;text-align: center;"  ><?php echo "$age_risk_factor" ?></td>
<td  style="height: 220px;text-align: center;" ><?php echo "$covid_risk_index" ?></td>
<td style="height:  220px;text-align: center;"  ><?php echo "$probability_of_covid_infection" ?></td>
<td  style="height: 220px;text-align: center;" ><?php echo "$regional_risk" ?></td>
</tr>
</tbody>
</table>
</div></div></div></div>
<br>
<br>
<br>
<br>
   <div class="container rounded alert alert-light mt-2 mb-5 ">
       <div class="row">

           <div class="col-lg-12">

               <div class="bs-component">
   <table  class="table  alert text-dark">
      <tr>
         <td role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo "$covid_risk_index"; ?>">
 </td>
 <td >&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
 <td role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo "$probability_of_covid_infection"; ?>">
   </td>
   <td > &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
   <td role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="--value: <?php echo "$regional_risk"; ?>">

   </td>
   </tr>




   <tr>
         <td>COVID RISK INDEX (%)

 </td>
 <td >&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
 <td>
      PROBABILITY OF INFECTION
   </td>
   <td >&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
   <td>
      REGIONAL RISK
         </td>
   </tr>
   </table>

   </div></div></div></div>
   <div class="container rounded alert   mt-5 mb-5 ">
<div class="row">

    <div class="col-lg-12">

        <div class="bs-component">
   <div class="page-header ">
               <div class="alert alert-dismissible alert-danger text-dark">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <h4 class="alert-heading">Attention!</h4>
            <p class="mb-0">Limiter l'accès et l'utilisation des appareils partagés tels que les machines à café, les fontaines à eau, les fours à micro-ondes, etc ;
 </p>
            </div>
            <button class="btn btn-danger"><a target="_blank" style="text-decoration: none;" href="{{url('/')}}">Return</a></button>
               </div>


               </div></div></div></div>

               </div>

               </div>
   </body>
</html>
