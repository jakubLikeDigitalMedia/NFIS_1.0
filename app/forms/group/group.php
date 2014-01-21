<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 20/01/2014
 * Time: 11:56
 */



if(!$exist){
    echo "<h1>$errorMessage</h1>";
    return;
}

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

$style = "style='display: inline-block; margin-left: 20px;'";
//var_dump($sitemap);
foreach($sitemap as $sectionId => $section){
    echo "<ol style='list-style:none;'>";
    echo "<h3>{$section['title']}</h3>";
    foreach ($section['pages'] as $pageId => $page) {
        //die(var_dump($page));
        $title = $page[Page_Model::TITLE];
        $code = $page[Page_Model::CODE];

        $displayPostOption = array('class' => 'section_page', 'multiple' => TRUE);
        $baseOption = array('class' => 'section_page');
        $addPostOption = $baseOption;
        $addCommentOption = $baseOption;
        $addVoteOption = $baseOption;

        if ($edit){
            $displayPostOption['checked'] = ($page[Permissions_Model::DISPLAY_POST] == 1)?  TRUE: FALSE;
            $addPostOption['checked'] = ($page[Permissions_Model::ADD_POST] == 1)?  TRUE: FALSE;
            $addCommentOption['checked'] = ($page[Permissions_Model::ADD_COMMENT] == 1)?  TRUE: FALSE;
            $addVoteOption['checked'] = ($page[Permissions_Model::ADD_VOTE] == 1)?  TRUE: FALSE;
        }
        else{
            if (isset($inputs['pages'])){
                if (in_array($pageId, $inputs['pages'])){
                    $displayPostOption['checked'] = TRUE;
                    $enabled = array($addPostOption, $addCommentOption, $addVoteOption);
                    $formGen->setOptionToElements($enabled, 'disabled', FALSE);

                    $addPostOption['checked'] = (isset($inputs[Permissions_Model::ADD_POST.'-'.$pageId]))? TRUE: FALSE;
                    $addCommentOption['checked'] = (isset($inputs[Permissions_Model::ADD_COMMENT.'-'.$pageId]))?  TRUE: FALSE;
                    $addVoteOption['checked'] = (isset($inputs[Permissions_Model::ADD_VOTE.'-'.$pageId]))? TRUE: FALSE;
                }
                else{
                    $displayPostOption['checked'] = FALSE;
                    $enabled = array($addPostOption, $addCommentOption, $addVoteOption);
                    $formGen->setOptionToElements($enabled, 'disabled', TRUE);
                }
            }
            else{
                //$disabled = array($addPostOption, $addCommentOption, $addVoteOption);
               // $formGen->setOptionToElements($disabled, 'disabled', TRUE);
                $addPostOption['checked'] = TRUE;
                //$addPostOption['disabled'] = TRUE;
                $addCommentOption['checked'] = TRUE;
                //$addCommentOption['disabled'] = TRUE;
                $addVoteOption['checked'] = FALSE;
                //$addVoteOption['disabled'] = TRUE;

               // die(var_dump($addPostOption));
            }
        }



        //(isset($inputs[Permissions_Model::ADD_POST.'-'.$pageId]))? $

        echo "<li><ul style='display: inline;'>";
        echo "<li $style>$title </li>";
        echo "<li $style>" . $formGen->createElement('checkbox', 'Display', 'pages', $pageId, $displayPostOption);
        echo "<li $style>" . $formGen->createElement('checkbox', 'Add post', Permissions_Model::ADD_POST .'-'. $pageId, '1', $addPostOption) . "</li>";
        echo "<li $style>" . $formGen->createElement('checkbox', 'Add comment', Permissions_Model::ADD_COMMENT .'-'. $pageId, '1', $addCommentOption) . "</li>";
        echo "<li $style>" . $formGen->createElement('checkbox', 'Add vote', Permissions_Model::ADD_VOTE .'-'. $pageId, '1', $addVoteOption) . "</li>";
        echo "</ul></li>";
    }
    echo '</ol>';
}

echo "</form>";
$group = new Group_Model();
$group->unsetSession();