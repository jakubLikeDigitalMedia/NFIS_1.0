<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 20/01/2014
 * Time: 11:56
 */



/*
$group = new Group_Model();
$page = new Page_Model();

$inputErrors = $group->getErrorsFromSession();
$inputs = $group->getInputsFromSession();

var_dump($inputErrors);
var_dump($inputs);
*/

$formHelper = new FormHelper(array('inputs' => $inputs, 'errors' => $errors));
$formGen = new FormGenerator('group_create', $actionLink, 'post');

echo $formGen->startFormTag();

echo $formGen->createElement(
    'text',
    'Group name',
    Group_Model::TITLE, $formHelper->getFieldValue(Group_Model::TITLE, 'Enter name of group'),
    array('error' => $formHelper->getFieldError(Group_Model::TITLE))
);

$formGen->setSubmitButton('name', 'submit', '');
echo $formGen->createSubmitButton();

$edit = FALSE;

$style = "style='display: inline-block; margin-left: 20px;'";
var_dump($inputs);
foreach($sitemap as $sectionId => $section){
    echo "<ol style='list-style:none;'>";
    echo "<h3>{$section['title']}</h3>";
    foreach ($section['pages'] as $pageId => $page) {

        $title = $page[Page_Model::TITLE];
        $code = $page[Page_Model::CODE];

        $baseOption = array('class' => 'section_page');

        $addPostOption = $baseOption;
        $addCommentOption = $baseOption;
        $addVoteOption = $baseOption;

        if ($edit){
            $group = $page[Permissions_Model::GROUP];
            $add_post = $page[Permissions_Model::ADD_POST];
            $add_comment = $page[Permissions_Model::ADD_COMMENT];
            $add_vote = $page[Permissions_Model::ADD_VOTE];
        }
        else{
            (isset($inputs[Permissions_Model::ADD_POST.'-'.$pageId]))? $addPostOption['options']: '';

        }







        //(isset($inputs[Permissions_Model::ADD_POST.'-'.$pageId]))? $

        echo "<li><ul style='display: inline;'>";
        echo "<li $style>$title </li>";
        echo "<li $style>" . $formGen->createElement('checkbox', 'Display', 'pages', $pageId, array('class' => 'section_page', 'multiple' => TRUE));
        echo "<li $style>" . $formGen->createElement('checkbox', 'Add post', Permissions_Model::ADD_POST .'-'. $pageId, '1', array('class' => 'section_page')) . "</li>";
        echo "<li $style>" . $formGen->createElement('checkbox', 'Add comment', Permissions_Model::ADD_COMMENT .'-'. $pageId, '1', array('class' => 'section_page')) . "</li>";
        echo "<li $style>" . $formGen->createElement('checkbox', 'Add vote', Permissions_Model::ADD_VOTE .'-'. $pageId, '1', array('class' => 'section_page')) . "</li>";
        echo "</ul></li>";
    }
    echo '</ol>';
}

echo "</form>";
$group = new Group_Model();
$group->unsetSession();