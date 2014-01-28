<?php

$employee = new Employee_Model();
$group = new Group_Model();
$brand = new Brand_Model();
$position =  new Position_Model();
$location = new Location_Model();
$department = new Department_Model();
$employment = new Employment_Model();

$grpConnection = new QueryHandler();
//$groupArray = $grpConnection->selectQuery("SELECT grp_id, title FROM `group`", 'grp_id');

$groupList = $group->getPropertyList($group::TITLE);
$brandList = $brand->getPropertyList($brand::TITLE);
$positionList = $position->getPropertyList($position::TITLE);
$locationList = $location->getPropertyList($location::TITLE);
$departmentList = $department->getPropertyList($department::TITLE);

$inputErrors = (isset($_SESSION['user']['errors']['current_empl']))? $_SESSION['user']['errors']['current_empl']: array();
$userInputs = (isset($_SESSION['user']['inputs']['current_empl']))? $_SESSION['user']['inputs']['current_empl']: array();

$formGen = new FormGenerator('change_permissions', $employee->getProperty("createScriptPath"), 'post');
$formHelper = new FormHelper( array('inputs' => $userInputs, 'errors' => $inputErrors) );

$groupSelect = $group::TITLE;
$groupSelect = array(
    'type' => 'select',
    'label' => 'Show group',
    'name' => $groupSelect,
    'value' => array('values' => $groupList, 'selected' => $formHelper->getFieldValue($groupSelect)),
    'options' => array('error' => $formHelper->getFieldError($groupSelect), 'class' => 'filter_by_value', 'attrs' => 'data-dynatable-query="group"', 'id' => 'group_select', 'textValues' => 'true')
);

$positionSelect = $employment::POSITION;
$positionSelect = array(
    'type' => 'select',
    'label' => 'Position',
    'name' => $positionSelect,
    'value' => array('values' => $positionList, 'selected' => $formHelper->getFieldValue($positionSelect)),
    'options' => array('error' => $formHelper->getFieldError($positionSelect), 'class' => 'filter_by_value', 'attrs' => 'data-dynatable-query="position"', 'id' => 'position_select', 'textValues' => 'true')
);

$brandSelect = $employment::BRAND;
$brandSelect = array(
    'type' => 'select',
    'label' => 'Brand',
    'name' => $brandSelect,
    'value' => array('values' => $brandList, 'selected' => $formHelper->getFieldValue($brandSelect)),
    'options' => array('error' => $formHelper->getFieldError($brandSelect), 'class' => 'filter_by_value', 'attrs' => 'data-dynatable-query="brand"', 'id' => 'brand_select', 'textValues' => 'true')
);

$departmentSelect = $employment::DEPARTMENT;
$departmentSelect = array(
    'type' => 'select',
    'label' => 'Department',
    'name' => $departmentSelect,
    'value' => array('values' => $departmentList, 'selected' => $formHelper->getFieldValue($departmentSelect)),
    'options' => array('error' => $formHelper->getFieldError($departmentSelect), 'class' => 'filter_by_value', 'attrs' => 'data-dynatable-query="department"', 'id' => 'department_select', 'textValues' => 'true')
);

$locationSelect = $employment::LOCATION;
$locationSelect = array(
    'type' => 'select',
    'label' => 'Location',
    'name' => $locationSelect,
    'value' => array('values' => $locationList, 'selected' => $formHelper->getFieldValue($locationSelect)),
    'options' => array('error' => $formHelper->getFieldError($locationSelect), 'class' => 'filter_by_value', 'attrs' => 'data-dynatable-query="location"', 'id' => 'location_select', 'textValues' => 'true')
);

$addToGroupSelect = $group::TITLE;
$addToGroupSelect = array(
    'type' => 'select',
    'label' => 'Add to group',
    'name' => 'add_to_group',
    'value' => array('values' => $groupList, 'selected' => $formHelper->getFieldValue($addToGroupSelect)),
    'options' => array('error' => $formHelper->getFieldError($addToGroupSelect))
);

$groupSelect = $formGen->renderElements($formGen->createElements(array($groupSelect)), '');
$brandSelect = $formGen->renderElements($formGen->createElements(array($brandSelect)), '');
$positionSelect = $formGen->renderElements($formGen->createElements(array($positionSelect)), '');
$locationSelect = $formGen->renderElements($formGen->createElements(array($locationSelect)), '');
$departmentSelect = $formGen->renderElements($formGen->createElements(array($departmentSelect)), '');
$addToGroupSelect = $formGen->renderElements($formGen->createElements(array($addToGroupSelect)), '');


?>
<p></p>
<form method="post" action="<?php echo $actionLink; ?>">
    
        <ul id="filter_by_value">
            <li><?php echo $groupSelect; ?></li>
            <li><?php echo $brandSelect; ?></li>
            <li><?php echo $positionSelect; ?></li>
            <li><?php echo $locationSelect; ?></li>
            <li><?php echo $departmentSelect; ?></li>
        </ul>
    
    <table id="add_employee_to_group">
        <thead>
          <th data-dynatable-column="name" class="dynatable-head">Name</th>
          <th data-dynatable-column="surname" class="dynatable-head">Surname</th>
          <th data-dynatable-column="brand" class="dynatable-head">Brand</th>
          <th data-dynatable-column="position" class="dynatable-head">Position</th>
          <th data-dynatable-column="location" class="dynatable-head">Location</th>
          <th data-dynatable-column="department" class="dynatable-head">Department</th>
          <th data-dynatable-column="group" class="dynatable-head">Group</th>
          <th data-dynatable-column="add">Add</th>
        </thead>
        <tbody>
        </tbody>
      </table>
    <?php echo $addToGroupSelect; ?>
    <input type="submit" value="submit" />
</form>
<script>
    $(function(){
        function sortJSON(data, key) {
    return data.sort(function(a, b) {
        var x = a[key]; var y = b[key];
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
    }
        var rcrds = sortJSON(<?php echo $this->getEmployee_ModelsList('id_group','all'); ?>,'index');
    dynatable = $("#add_employee_to_group").dynatable({
            dataset: {
               records: rcrds,
            },
            features: {
           },
           inputs: {
             queries: $('#group_select, #brand_select, #position_select, #location_select, #department_select')
           }
        }).data('dynatable');
        
       //console.log(dynatable.settings.dataset.originalRecords.index.sort());
    });
</script>