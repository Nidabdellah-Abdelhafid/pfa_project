<!DOCTYPE html>
<html>

<head>
    <title>Personal Risk Form</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <link rel="stylesheet" href="assets/css/bootstrapfin.min.css">
    <link rel="stylesheet" href="assets/css/" rel="stylesheet">
    <style>
        html,
        body {
            min-height: 100%;
        }

        body,
        div,
        form,
        input,
        select,
        p {
            padding: 0;
            margin: 0;
            outline: none;
            font-family: Roboto, Arial, sans-serif;
            font-size: 14px;
            color: #666;
            line-height: 22px;
        }

        h1 {
            position: absolute;
            margin: 0;
            font-size: 32px;
            color: #fff;
            z-index: 2;
        }

        h2 {
            font-weight: 400;
        }

        .testbox {
            display: flex;
            justify-content: center;
            align-items: center;
            height: inherit;
            padding: 20px;
        }

        form {
            width: 100%;
            padding: 20px;
            border-radius: 6px;
            background: #fff;
            box-shadow: 0 0 20px 0 #095484;
        }

        .banner {
            position: relative;
            height: 210px;
            background-image: url("b.jpeg");
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .banner::after {
            content: "";
            background-color: rgba(0, 0, 0, 0.4);
            position: absolute;
            width: 100%;
            height: 100%;
        }
    </style>
</head>




<body>
    <div class="container rounded alert alert-warning mt-5 mb-5">
        <div class="testbox ">
            <form action="{{url('form')}}" method="get">
                <div class="banner">
                    <h1>Personal Risk Form</h1>
                </div>
                <fieldset>
                    <legend class="alert alert-info mt-3 text-dark">response</legend>
                    <table class="table table-light alert text-dark">
                        <tr>
                            <td>
                                AGE : <input class="input-group bg-light border-1" type="number" name="age" placeholder="Enter your age ..." required>
                            </td>
                            <td>
                                GENDER :<select class="form-select form-select-lg  bg-info  text-light " name="gender" required>
                                    <option> </option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    </select>
                            </td>
                        </tr>
                        <tr>
                            <td>CITY : <select class="form-select form-select-lg bg-info mb-3 text-light" name="city" required>
                <option></option>
                <option>CASABLANCA</option>
                <option>RABAT</option>
                <option>TANGER</option>
              </select></td>
                            <td></td>
                        </tr>
                    </table>
                </fieldset>

                <fieldset>
                    <legend class="alert alert-info mt-3 text-dark">OCCUPATION </legend>
                    <table class="table table-light alert text-dark">
                        <tr>
                            <td style="color: red;">*</td>
                            <td>Select one that best describes your occupation.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="occupation[]" value="Nurse and other health worker"> Nurse and other health worker</td>
                            <td><input type="checkbox" name="occupation[]" value="Receptionists/Bar tenders" /> Receptionists/Bar tenders</td>
                            <td><input type="checkbox" name="occupation[]" value="Agriculture" /> Agriculture</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="occupation[]" value="Others" /> Others</td>
                            <td><input type="checkbox" name="occupation[]" value="Shops or supermarket salesperson" /> Shops or supermarket salesperson</td>
                            <td><input type="checkbox" name="occupation[]" value="Medical Doctor" /> Medical Doctor</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="occupation[]" value="Domestic housekeeper/cleaner" /> Domestic housekeeper/cleaner</td>
                            <td><input type="checkbox" name="occupation[]" value="Teacher/Trainer/Professor" /> Teacher/Trainer/Professor</td>
                            <td><input type="checkbox" name="occupation[]" value="Travel attendent/guide" /> Travel attendent/guide</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="occupation[]" value="None" /> None</td>
                            <td><input type="checkbox" name="occupation[]" value="Driver and other transport work" /> Driver and other transport work</td>
                            <td><input type="checkbox" name="occupation[]" value="Religious professional" /> Religious professional</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="occupation[]" value="Security/Police/Fire fighter" /> Security/Police/Fire fighter</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </fieldset>




                <fieldset>
                    <legend class="alert alert-info mt-3 text-dark">EXPOSURE </legend>
                    <table class="table table-light alert text-dark">
                        <tr>
                            <td style="color: red;">*</td>
                            <td> Select one that best describes your exposure.</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="exposure[]" value="Need to attend meetings in person occasionally" /> Need to attend meetings in person occasionally</td>
                            <td><input type="checkbox" name="exposure[]" value="Met with known COVID19 infected person" /> Met with known COVID19 infected person</td>
                            <td><input type="checkbox" name="exposure[]" value="Have to be in proximity of less than 2 meters in workplace with other colleagues" /> Have to be in proximity of less than 2 meters in workplace with other colleagues</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="exposure[]" value="Have more than one family members, who need to go out and meet with other people" /> Have more than one family members, who need to go out and meet with other people</td>
                            <td><input type="checkbox" name="exposure[]" value="Have a family member, who need to go out and meet with other people" /> Have a family member, who need to go out and meet with other people</td>
                            <td><input type="checkbox" name="exposure[]" value="None" /> None</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="exposure[]" value="Travelled to COVID-19 infected area recently" /> Travelled to COVID-19 infected area recently</td>
                            <td><input type="checkbox" name="exposure[]" value="Have to be in proximity of less than 2 meters in workplace (or in field, as part of job) with public" /> Have to be in proximity of less than 2 meters in workplace (or in field,
                                as part of job) with public</td>
                            <td><input type="checkbox" name="exposure[]" value="Need" /> Need to travel/walk in public areas where maintaining the distance
                                of more than 2 meters is impossible.</td>
                        </tr>
                    </table>
                </fieldset>



                <fieldset>
                    <legend class="alert alert-info mt-3 text-dark">SAFETY MEASURE </legend>
                    <table class="table table-light alert text-dark">
                        <tr>
                            <td style="color: red;">*</td>
                            <td>Do You ?</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="safety[]" value="Maintain physical distance when going outside" /> Maintain physical distance when going outside</td>
                            <td><input type="checkbox" name="safety[]" value="Use sunglasses/face shields" /> Use sunglasses/face shields</td>
                            <td><input type="checkbox" name="safety[]" value="Sanitize hands using hand washing or sanitizer" /> Sanitize hands using hand washing or sanitizer</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="safety[]" value="None" /> None</td>
                            <td><input type="checkbox" name="safety[]" value="Wear mask when going to crowded areas" /> Wear mask when going to crowded areas</td>
                            <td></td>
                        </tr>
                    </table>
                </fieldset>


                <fieldset>
                    <legend class="alert alert-info mt-3 text-dark">HABITS </legend>
                    <table class="table table-light alert text-dark">
                        <tr>
                            <td style="color: red;">*</td>
                            <td>Do You ?</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="habits[]" value="Smoke regularly" /> Smoke regularly</td>
                            <td><input type="checkbox" name="habits[]" value="Use sunglasses/face shields" /> Use sunglasses/face shields</td>
                            <td><input type="checkbox" name="habits[]" value="None" /> None</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="habits[]" value="Drink Alcohol Regularly" /> Drink Alcohol Regularly</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </fieldset>




                <fieldset>
                    <legend class="alert alert-info mt-3 text-dark">EXISTING HEALTH CONDITION </legend>
                    <table class="table table-light alert text-dark">
                        <tr>
                            <td style="color: red;">*</td>
                            <td> Do you have any of the following health condition ?</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="existing[]" value="Cardiovascular/Coronary heart disease" /> Cardiovascular/Coronary heart disease</td>
                            <td><input type="checkbox" name="existing[]" value="Renal Disease" /> Renal Disease</td>
                            <td><input type="checkbox" name="existing[]" value="Hypertension" /> Hypertension</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="existing[]" value="Diabetes" /> Diabetes</td>
                            <td><input type="checkbox" name="existing[]" value="Obesity" /> Obesity</td>
                            <td><input type="checkbox" name="existing[]" value="Cerebrovascular disease" /> Cerebrovascular disease</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="existing[]" value="None" /> None</td>
                            <td><input type="checkbox" name="existing[]" value="Asthma/Chronic obstructive pulmonary disease" /> Asthma/Chronic obstructive pulmonary disease</td>
                            <td><input type="checkbox" name="existing[]" value="Cancer(including treated within 3 years)" /> Cancer(including treated within 3 years)</td>
                        </tr>
                    </table>
                </fieldset>




                <fieldset>
                    <legend class="alert alert-info mt-3 text-dark">SYMPTOM </legend>
                    <table class="table table-light alert text-dark">
                        <tr>
                            <td style="color: red;">*</td>
                            <td>Do you have any of the following symptoms(currently) ?</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="symptom[]" value="Cough" /> Cough</td>
                            <td><input type="checkbox" name="symptom[]" value="Chills" /> Chills</td>
                            <td><input type="checkbox" name="symptom[]" value="Throat pain" /> Throat pain</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="symptom[]" value="Headache" /> Headache</td>
                            <td><input type="checkbox" name="symptom[]" value="Body pain (Myalgia)" /> Body pain (Myalgia)</td>
                            <td><input type="checkbox" name="symptom[]" value="Diarrhoea" /> Diarrhoea</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="symptom[]" value="High fever" /> High fever</td>
                            <td><input type="checkbox" name="symptom[]" value="Fatigue" /> Fatigue</td>
                            <td><input type="checkbox" name="symptom[]" value="Shortness of breathe" /> Shortness of breathe</td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" name="symptom[]" value="Nausea and Vomitting" /> Nausea and Vomitting</td>
                            <td><input type="checkbox" name="symptom[]" value="None" /> None</td>
                            <td></td>
                        </tr>
                    </table>
                </fieldset>






                <input type="submit" value="ENVOYER" name="submit" class="btn btn-success" formaction="{{url('form')}}">
            </form>
        </div>

    </div>

</body>

</html>
