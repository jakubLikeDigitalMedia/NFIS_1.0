<?php

$brand = new Brand_Model();
$position =  new Position_Model();
$location = new Location_Model();
$department = new Department_Model();
$employee = new Employee_Model();
$employment = new Employment_Model();
$address = new Address_Model();

$superiorList = $employee->getSuperiors();
$brandList = $brand->getPropertyList($brand::TITLE);
$positionList = $position->getPropertyList($position::TITLE);
$locationList = $location->getPropertyList($location::TITLE);
$departmentList = $department->getPropertyList($department::TITLE);


session_start();
$inputErrors = (isset($_SESSION['user']['errors']['current_empl']))? $_SESSION['user']['errors']['current_empl']: array();
$userInputs = (isset($_SESSION['user']['inputs']['current_empl']))? $_SESSION['user']['inputs']['current_empl']: array();

$formHelper = new FormHelper( array('inputs' => $userInputs, 'errors' => $inputErrors) );

$name = $employee->getTableCollumnName($employee::NAME);
$name = array(
    'type' => 'text',
    'label' => 'Name',
    'name' => $name,
    'value' => $formHelper->getFieldValue($name, 'Enter your Name'),
    'options' => array('error' => $formHelper->getFieldError($name))
);

$surname = $employee->getTableCollumnName($employee::SURNAME);
$surname = array(
    'type' => 'text',
    'label' => 'Surname',
    'name' => $surname,
    'value' => $formHelper->getFieldValue($surname, 'Enter your Surname'),
    'options' => array('error' => $formHelper->getFieldError($surname))
);

$dob = $employee->getTableCollumnName($employee::DOB);
$dob = array(
    'type' => 'text',
    'label' => 'Date of birth',
    'name' => $dob,
    'value' => $formHelper->getFieldValue($dob, 'Click here to get calendar'),
    'options' => array('error' => $formHelper->getFieldError($dob))
);

$email = $employee->getTableCollumnName($employee::EMAIL);
$email = array(
    'type' => 'text',
    'label' => 'Email',
    'name' => $email,
    'value' => $formHelper->getFieldValue($email, 'Enter you email'),
    'options' => array('error' => $formHelper->getFieldError($email))
);

$phone = $employee->getTableCollumnName($employee::PHONE_NUMBER);
$phone = array(
    'type' => 'text',
    'label' => 'Phone Number',
    'name' => $phone,
    'value' => $formHelper->getFieldValue($phone, 'Enter you phone number'),
    'options' => array('error' => $formHelper->getFieldError($phone))
);

$position = $employment->getTableCollumnName($employment::POSITION);
$position = array(
    'type' => 'select',
    'label' => 'Position',
    'name' => $position,
    'value' => array('values' => $positionList, 'selected' => $formHelper->getFieldValue($position)),
    'options' => array('error' => $formHelper->getFieldError($position))
);

$brand = $employment->getTableCollumnName($employment::BRAND);
$brand = array(
    'type' => 'select',
    'label' => 'Brand',
    'name' => $brand,
    'value' => array('values' => $brandList, 'selected' => $formHelper->getFieldValue($brand)),
    'options' => array('error' => $formHelper->getFieldError($brand))
);

$department = $employment->getTableCollumnName($employment::DEPARTMENT);
$department = array(
    'type' => 'select',
    'label' => 'Department',
    'name' => $department,
    'value' => array('values' => $departmentList, 'selected' => $formHelper->getFieldValue($department)),
    'options' => array('error' => $formHelper->getFieldError($department))
);

$location = $employment->getTableCollumnName($employment::LOCATION);
$location = array(
    'type' => 'select',
    'label' => 'Location',
    'name' => $location,
    'value' => array('values' => $locationList, 'selected' => $formHelper->getFieldValue($location)),
    'options' => array('error' => $formHelper->getFieldError($location))
);

$parent = $employee->getTableCollumnName($employee::PARENT);
$parent = array(
    'type' => 'select',
    'label' => 'Superior',
    'name' => $parent,
    'value' => array('values' => array(), 'selected' => $formHelper->getFieldValue($parent)),
    'options' => array('error' => $formHelper->getFieldError($parent))
);

// address
$street = $address->getTableCollumnName($address::STREET);
$street = array(
    'type' => 'text',
    'label' => 'House number & Street',
    'name' => $street,
    'value' => $formHelper->getFieldValue($street, 'Enter Street'),
    'options' => array('error' => $formHelper->getFieldError($street))
);

$postcode = $address->getTableCollumnName($address::POSTCODE);
$postcode = array(
    'type' => 'text',
    'label' => 'Postcode',
    'name' => $postcode,
    'value' => $formHelper->getFieldValue($postcode, 'Enter Postcode'),
    'options' => array('error' => $formHelper->getFieldError($postcode))
);

$city = $address->getTableCollumnName($address::CITY);
$city = array(
    'type' => 'text',
    'label' => 'City',
    'name' => $city,
    'value' => $formHelper->getFieldValue($city, 'Enter City'),
    'options' => array('error' => $formHelper->getFieldError($city))
);

$county = $address->getTableCollumnName($address::COUNTY);
$county = array(
    'type' => 'text',
    'label' => 'County',
    'name' => $county,
    'value' => $formHelper->getFieldValue($county, 'Enter County'),
    'options' => array('error' => $formHelper->getFieldError($county))
);

$createAccountFormItems = array(
    'Personal Information' => array(
        $name,
        $surname,
        $dob,
        $email,
        $phone
    ),
    'Employment Information' => array(
        $position,
        $brand,
        $department,
        $location,
        $parent,
        '' => array(
            $street,
            $postcode,
            $city,
            $county
        )
    )
);

// change names of fields for previous employment section
$position['name'] = PREV_PREFIX.$employment->getTableCollumnName($employment::POSITION);
$brand['name'] = PREV_PREFIX.$employment->getTableCollumnName($employment::BRAND);
$department['name'] = PREV_PREFIX.$employment->getTableCollumnName($employment::DEPARTMENT);
$location['name'] = PREV_PREFIX.$employment->getTableCollumnName($employment::LOCATION);

$street['name'] = PREV_PREFIX.$address->getTableCollumnName($address::STREET);
$postcode['name'] = PREV_PREFIX.$address->getTableCollumnName($address::POSTCODE);
$city['name'] = PREV_PREFIX.$address->getTableCollumnName($address::CITY);
$county['name'] = PREV_PREFIX.$address->getTableCollumnName($address::COUNTY);

$prevEmplSelect = array(
    $position,
    $brand,
    $department,
    $location
);

$prevEmplAddress = array(
    $street,
    $postcode,
    $city,
    $county
);

$formGen = new FormGenerator('create_account', $employee->getProperty('createAction'), 'post');

// add multiple option to fields
$formGen->setOptionToElements($prevEmplSelect, 'multiple', TRUE);
$formGen->setOptionToElements($prevEmplAddress, 'multiple', TRUE);

//die(var_dump($prevEmplAddress));



$HTMLBlock = <<< HTML
<div id="previous-employment">
<h2>Previous Employment</h2>
<button type="button" id="add-previous-options">Add</button>
<div class="add-block" >
HTML;
if(isset($_SESSION['user']['inputs']['prev_empl'])){

    //var_dump($_SESSION['user']['errors']['prev_empl']);

    for($i=0; $i < count($_SESSION['user']['inputs']['prev_empl']); $i++){
        $inputErrors = (isset($_SESSION['user']['errors']['prev_empl'][$i]))? $_SESSION['user']['errors']['prev_empl'][$i]: array();
        $userInputs = (isset($_SESSION['user']['inputs']['prev_empl'][$i]))? $_SESSION['user']['inputs']['prev_empl'][$i]: array();

        $formHelper = new FormHelper(array('inputs' => $userInputs, 'errors' => $inputErrors));

        //var_dump($prevEmplSelect);

        $formHelper->setValuesForSelectElements($prevEmplSelect);
        $formHelper->setValuesForTextElements($prevEmplAddress);

        $formHelper->setErrorsForElements($prevEmplSelect);
        $formHelper->setErrorsForElements($prevEmplAddress);

        $HTMLBlock .= $formGen->renderElements($formGen->createElements($prevEmplSelect), 'div');
        $HTMLBlock .= $formGen->renderElements($formGen->createElements($prevEmplAddress, 'Office Address'),'div');
    }
}

$HTMLBlock .= <<< HTML
    </div>
</div>
HTML;

$formGen->addElements($createAccountFormItems);
$formGen->addHTMLBlock($HTMLBlock);
echo $formGen->render('list');



?>

<div class="new-block hide original" >
    <?php
        //render previous employment fields

        $formGen->setOptionToElements($prevEmplSelect, 'error', '');
        $formGen->setOptionToElements($prevEmplAddress, 'error', '');

        echo $formGen->renderElements($formGen->createElements($prevEmplSelect), 'div');
        echo $formGen->renderElements($formGen->createElements($prevEmplAddress, 'Office Address'), 'div');

    ?>

</div>
<?php
//clear session
if (isset($_SESSION['user']['errors'])) unset($_SESSION['user']['errors']);
if (isset($_SESSION['user']['inputs'])) unset($_SESSION['user']['inputs']);


