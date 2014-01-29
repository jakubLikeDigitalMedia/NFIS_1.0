<?php
// form for post appearance in backend

if(isset($exist) && $exist == FALSE){
    echo "<h1>$errorMessage</h1>";
    return;
}

$formHelper = new FormHelper(array('inputs' => $inputs, 'errors' => $errors));
$formGen = new FormGenerator('post_appearance', $actionLink, 'post');

echo $formGen->startFormTag();

(!empty($postAppearances))? $formGen->setSubmitButton('name', 'Edit Display Rules', ''): $formGen->setSubmitButton('name', 'Create Display Rules', '');

$style = "style='display: inline-block; margin-left: 20px;'";
//var_dump($sitemap);
foreach($sitemap as $sectionId => $section){
    if (!in_array($section[Section_Model::CODE], array('inside_nobel'))) continue;
    echo '<ol>';
    echo "<h3>{$section['title']}</h3>";
    foreach ($section['pages'] as $pageId => $page) {
        echo "<h4>{$page[Page_Model::TITLE]}</h4>";
        echo '<div>Add posts from other section</div>';
        echo '<li class="sections">';
        //die(var_dump($pageList));

        $options = array();
        $options['multiple'] = TRUE;

        echo $formGen->createElement('hidden', '', 'default', $pageId, array('multiple' => TRUE));
        // list of other pages in section
        foreach ($pageList as $id => $title) {
            $options['id'] = Post_Appearance_Model::DISPLAY_PAGE.'_'.$pageId.'_'.$id;
            $displayPage = array(
                'type' => 'checkbox',
                'label' => $title,
                'name' => Post_Appearance_Model::DISPLAY_PAGE,
                'value' => $formHelper->getFieldValue(Post_Appearance_Model::DISPLAY_PAGE, "$pageId-$id"),
                'options' => $options
            );

            if ($id == $pageId) continue; // skip same page

            // display rules already set
            if (!empty($postAppearances)){
                foreach ($postAppearances as $key => $appearance) {
                    if (in_array($pageId, $appearance) && in_array($id, $appearance)){
                        $displayPage['options']['checked'] = TRUE;
                        unset($postAppearances[$key]);
                        break;
                    }
                }
            }
            echo $formGen->createElement($displayPage['type'], $displayPage['label'], $displayPage['name'], $displayPage['value'], $displayPage['options']);
            echo '</br>';
        }

        echo '</li>';
    }
    echo '</ol>';
}
echo $formGen->createSubmitButton();
$formGen->endFormTag();
$postAppearanceModel = new Post_Appearance_Model();
$postAppearanceModel->unsetSession();

