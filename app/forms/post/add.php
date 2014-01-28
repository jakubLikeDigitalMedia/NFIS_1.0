<?php
$post = new Post_Model();

$inputErrors = (isset($_SESSION['user']['errors']['current_empl']))? $_SESSION['user']['errors']['current_empl']: array();
$userInputs = (isset($_SESSION['user']['inputs']['current_empl']))? $_SESSION['user']['inputs']['current_empl']: array();

$postFormGen = new FormGenerator('add_post', $post->getProperty("createScriptPath"), 'POST', 'form-horizontal add_post_form ');
$formHelper = new FormHelper( array('inputs' => $userInputs, 'errors' => $inputErrors) );

$postTitle = array(
    'type' => 'text',
    'label' => 'Title',
    'value' => '',
    'name' => 'post_title',
    'options' => ''
);

$postDesc = array(
    'type' => 'text',
    'label' => 'Description',
    'value' => '',
    'name' => 'post_desc',
    'options' => ''
);

$postContent = array(
    'type' => 'textarea',
    'label' => 'Full Story',
    'value' => '',
    'name' => 'post_content',
    'options' => ''
);

$createPostFormItems = array(
    'Add post' => array(
        $postTitle,
        $postDesc,
        $postContent
    )
);

$postFormGen->createElements(array($postTitle, $postDesc, $postContent));
$postSubmit = $postFormGen->setSubmitButton('post_submit', 'submit', array('class' => 'btn btn-default'));

$postFormGen->addElements($createPostFormItems);


echo $postFormGen->render();
