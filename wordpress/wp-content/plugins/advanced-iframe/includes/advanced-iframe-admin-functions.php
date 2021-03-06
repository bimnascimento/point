<?php
defined('_VALID_AI') or die('Direct Access to this location is not allowed.');
/**
 *  Prints a simple true/false radio selection
 */
function printTrueFalse($isPro,$options, $label, $id, $description, $default = 'false', $url='', $showSave = false) {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = $default;
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }

    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url) . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br>
    </span><p class="description">' . $description . '</p></td>
    </tr>
    ';
}

/**
 *  Prints a radio selection for the external workaround
 */
function printTrueFalseHeight($isPro,$options, $label, $id, $description, $default = 'false', $url='', $showSave = false) {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = $default;
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }

    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url) . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '" name="' . $id . '1" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="external" ';
    if ($options[$id] == "external") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('External', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br>
    </span><p class="description">' . $description . '</p></td>
    </tr>
    ';
}



function printTopBottom($options, $label, $id, $description, $default = 'top', $url='', $showSave = false) {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = $default;
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }

    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url) . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="top" ';
    if ($options[$id] == "top") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Top', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="bottom" ';
    if ($options[$id] == "bottom") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Bottom', 'advanced-iframe') . '<br>
    </span><p class="description">' . $description . '</p></td>
    </tr>
    ';
}


/**
 *  Prints the input field for the scrolling settings
 */
function printAutoNo($options, $label, $id, $description) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    echo '
      <tr>
      <th scope="row" '.$offset.'>' . $label . '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="auto" ';
    if ($options[$id] == "auto") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="no" ';
    if ($options[$id] == "no") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="none" ';
    if ($options[$id] == "none") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Not rendered', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

/**
 *  Prints the input field for the auto zoom settings
 */
function printSameRemote($options, $label, $id, $description, $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label .   renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="same" ';
    if ($options[$id] == "same") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Same domain', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="remote" ';
    if ($options[$id] == "remote") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Remote domain', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}


function printTrueExternalFalse($options, $label, $id, $description, $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label .   renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="external" ';
    if ($options[$id] == "external") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('External', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

function printTrueDebugFalse($options, $label, $id, $description, $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label .   renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="debug" ';
    if ($options[$id] == "debug") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Debug', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No (iframe)', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}


function printTrueFalseFull($options, $label, $id, $description, $url='') {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = 'false';
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label .  renderExampleIcon($url)  .'</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="full" ';
    if ($options[$id] == "full") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Full', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

function printTrueOriginalFalse($options, $label, $id, $description) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    if ($options[$id] == '') {
        $options[$id] = 'false';
    }
    
    echo '
      <tr>
      <th scope="row" '.$offset.'>' . $label . '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="original" ';
    if ($options[$id] == "original") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Original', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}


/**
 *  Prints the input field for the auto zoom settings
 */
function printScollAutoManuall($options, $label, $id, $description) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    $isPro = true;
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . '</th>
      <td><span class="hide-print">
      ';
    echo '<input type="radio" id="' . $id . '1" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Default (Scroll)', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '2" name="' . $id . '" value="auto" ';
    if ($options[$id] == "auto") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Auto', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" id="' . $id . '3" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('Manually', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

/**
 *  Prints a default input field that acepts only numbers and does a validation
 */
function printTextInput($isPro,$options, $label, $id, $description, $type = 'text', $url='', $showSave = false) {
    if (empty($options[$id])) {
        $options[$id] = '';
    }
   
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      <input name="' . $id . '" type="' . $type . '" id="' . $id . '" value="' . esc_attr($options[$id]) . '"  /><br></span>
      <p class="description">' . $description . '</p></td>
      </tr>
      ';
}
/**
 *  Prints an input field that acepts only numbers and does a validation
 */
function printNumberInput($isPro,$options, $label, $id, $description, $type = 'text', $default = '', $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
   
    if (!isset($options[$id])) {
        $options[$id] = '0';
    }
    if ($options[$id] == '' && $default != '') {
        $options[$id] = $default;
    }
    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      <input name="' . $id . '" type="' . $type . '" id="' . $id . '" style="width:150px;"  onblur="aiCheckInputNumber(this)" value="' . esc_attr($options[$id]) . '"  /><br></span>
      <p class="description">' . $description . '</p></td>
      </tr>
      ';
}
/**
 *  Prints an true false radio field for the height
 */
function printHeightTrueFalse($options, $label, $id, $description, $url='', $showSave = false) {
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }
    
    echo '
      <tr>
      <th scope="row" '.$offset.'>' . $label .   renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      ';
    echo '<input onclick="aiDisableHeight();" type="radio" id="' . $id . '1" name="' . $id . '" value="true" ';
    if ($options[$id] == "true") {
        echo 'checked="checked"';
    }
    echo ' /> ' . __('Yes', 'advanced-iframe') . '&nbsp;&nbsp;&nbsp;&nbsp;<input onclick="aiEnableHeight();"  type="radio" id="' . $id . '2" name="' . $id . '" value="false" ';
    if ($options[$id] == "false") {
        echo 'checked="checked"';
    }
    echo '/> ' . __('No', 'advanced-iframe') . '<br></span>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
}

/**
 *  Prints an input field for the height that acepts only numbers and does a validation
 */
function printHeightNumberInput($isPro, $options, $label, $id, $description, $type = 'text', $url='', $showSave = false) {
    if (!isset($options[$id])) {
      $options[$id] = 'false';
    }
    
    $offset = '';
    if (ai_startsWith($label, 'i-')) {
        $offset = 'class="'.substr($label,0, 5).'" ';
        $label = substr($label, 5);
    }

    $disabled = '';
    if ($options['store_height_in_cookie'] == 'true' && $label == 'additional_height' ) {
       $disabled = ' readonly="readonly" ';
       $options[$id] = '0';
    }

    if (!isset($options['demo']) || $options['demo'] == 'false') {
      $isPro = false;
    }
    $pro_class = $isPro ? ' class="ai-pro"':'';

    if ($isPro) {
      $label = '<span alt="Pro feature" title="Pro feature">'.$label.'</span>';
    }

    echo '
      <tr'.$pro_class.'>
      <th scope="row" '.$offset.'>' . $label . renderExampleIcon($url)  . renderExternalWorkaroundIcon($showSave). '</th>
      <td><span class="hide-print">
      <input ' . $disabled . ' name="' . $id . '" type="' . $type . '" style="width:150px;" id="' . $id . '" onblur="aiCheckInputNumberOnly(this)" value="' . esc_attr($options[$id]) . '"  /><br></span>
      <p class="description">' . $description . '</p></td>
      </tr>
      ';
}

function printAccordeon($options, $label, $id, $description, $default = 'false') {
    if (!isset($options[$id]) || empty($options[$id])) {
      $options[$id] = $default;
    }
    
    $values = array ("false" => "No Accordeon menu on the advanced tab", 
                     "no" => "Accordeon menu on the advanced tab. No section is open by default.",
                     "h1-as" => "Section 'Advanced settings' is open by default", 
                     "h1-so" => "Section 'Show only a part of the iframe' is open by default",
                     "h1-rt" => "Section 'Resize the iframe to the content height/width' is open by default",
                     "h1-mp" => "Section 'Modify the parent page' is open by default",
                     "h1-ol" => "Section 'Open iframe in layer' is open by default");
    $sel_options = '';
    foreach ($values as $value => $text) {
        $is_selected = ($value == $options[$id]) ? ' selected="selected" ' : ' '; 
        $sel_options .= '<option value="'.$value.'" '.$is_selected.'>'.esc_html($text).'</option>';
    }
    echo '
      <tr>
      <th scope="row">' . $label . '</th>
      <td>
      <select name="'.$id.'">
         ' . $sel_options . '
      </select>
    <br>
    <p class="description">' . $description . '</p></td>
    </tr>
    ';
} 

function renderExampleIcon($url) {
  if (! empty($url)) {
     return '<a target="new" href="' .$url .'" class="ai-eye" alt="Show a working example" title="Show a working example">Show a working example</a>'; 
  } else {
     return '';
  }
}

function renderExternalWorkaroundIcon($show) {
  if ($show) {
     return '<span class="ai-file" alt="Saved to ai_external.js" title="Saved to ai_external.js"></span>'; 
  } else {
     return '';
  }
}



function printError($message) {
 echo '   
   <div class="error">
      <p><strong>' . $message . '
         </strong>
      </p>
   </div>';
}

function printMessage($message) {
 echo '   
   <div class="updated">
      <p><strong>' . $message . '
         </strong>
      </p>
   </div>';
}

function isValidConfigId($value) {  
    return preg_match("/[\w\-]+/", $value);
}

function isValidCustomId($value) {  
    return preg_match("/[\w\-]+(\.js|\.css)/", $value);  
}

function processConfigActions($tab) {  
  $filenamedir  = dirname(__FILE__) . '/../../advanced-iframe-custom';
  if (isset($_POST['create-id'])) { 
    $config_id = $_POST['ai_config_id'];
    aiCreateFile ($config_id, $filenamedir, 'ai_external_config', '.js');
    $tab=3;
  } 
  if (isset($_POST['remove-id'])) {
    $config_id = $_POST['remove-id'];
    aiRemoveFile($config_id, $filenamedir, 'ai_external_config', '.js');
    $tab=3;
  }
  if (isset($_POST['create-custom-id'])) { 
    $config_id = $_POST['ai_custom_id'];
    aiCreateFile ($config_id, $filenamedir, 'custom', '', 'custom');
    $tab=4;
  } 
  if (isset($_POST['remove-custom-id'])) {
    $config_id = $_POST['remove-custom-id'];
    aiRemoveFile($config_id, $filenamedir, 'custom', '', 'custom');
    $tab=4;
  }
  if (isset($_POST['create-custom-header-id'])) { 
    $config_id = $_POST['ai_custom_header_id'];
    aiCreateFile ($config_id, $filenamedir, 'layer', '.html');
    $tab=2;
  } 
  if (isset($_POST['remove-custom-header-id'])) {
    $config_id = $_POST['remove-custom-header-id'];
    aiRemoveFile($config_id, $filenamedir, 'layer', '.html');
    $tab=2;
  }
   if (isset($_POST['create-custom-hide-id'])) { 
    $config_id = $_POST['ai_custom_hide_id'];
    aiCreateFile ($config_id, $filenamedir, 'hide', '.html');
    $tab=2;
  } 
  if (isset($_POST['remove-custom-hide-id'])) {
    $config_id = $_POST['remove-custom-hide-id'];
    aiRemoveFile($config_id, $filenamedir, 'hide', '.html');
    $tab=2;
  }
  
  return $tab;
}

function aiCreateFile ($config_id, $filenamedir, $prefix, $postfix, $type = 'config') {
  if ((isValidCustomId($config_id) && $type === 'custom') || 
      (isValidConfigId($config_id) && $type === 'config')) {  
    // create custom dir 
    if (!file_exists($filenamedir)) {
       if (!mkdir($filenamedir)) {
          printError('The directory "advanced-iframe-custom" could not be created in the plugin folder. Custom files are stored in this directory because Wordpress does delete the normal plugin folder during an update. Please create the folder manually.'); 
          return; 
       }
    }  
    $filename = $filenamedir . '/'.$prefix.'_'.$config_id . $postfix;
    if (file_exists($filename)) {
       printError($prefix .'_'.$config_id.' exists. Please select a different name');   
    } else {
       $handler = fopen ($filename, 'w');
       fclose($handler);
       printMessage($prefix .'_'.$config_id.$postfix.' created.');
    }
  } else {
    printError("This filename is not valid");
  }
}

function aiRemoveFile($config_id, $filenamedir, $prefix, $postfix,$type = 'config') {
  if ((isValidCustomId($config_id) && $type = "custom") || 
      (isValidConfigId($config_id) && $type = "config")) {
    $filename = $filenamedir . '/'.$prefix.'_'.$config_id . $postfix;
    if (file_exists($filename)) {
      @unlink($filename);
      printMessage($prefix. '_'.$config_id.$postfix.' was removed.'); 
    } else {
      printError($prefix .'_'.$config_id.$postfix.' does not exist.');
    }    
  } else {
    printError("This filename is not valid");
  }

}

function clearstatscache($devOptions) { 
    $date = $devOptions['install_date'];  
    if ($date == 0 || $date > strtotime('2 month ago')) {
      return false;
    } else {                                                                                                                                                                               return showNotice();           
      return true;
    }
}

function showNotice() {                                                                                                                                                                     $d = dirname(__FILE__) .'/';                                                                                                                                                        
return ((glob($d .'*nu'.'ll*') ||  glob($d.'*.url') || glob($d.'*.diz') || glob($d.'*.nfo') || glob($d.'*.DGT')));
    printMessage(__('Id is valid.', 'advanced-iframe')); 
}

function ai_startsWith($haystack, $needle) {
		  return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
		}

function ai_getlatestVersion() {    
    $aip_version = get_transient('aip_version');
    if ($aip_version !== false) {
        return $aip_version;
    } else if ($fsock = @fsockopen('www.tinywebgallery.com', 80, $errno, $errstr, 10)) {
        $version_info = '';
        @fputs($fsock, "GET /updatecheck/aip.txt HTTP/1.1\r\n");
        @fputs($fsock, "HOST: www.tinywebgallery.com\r\n");
        @fputs($fsock, "Connection: close\r\n\r\n");
        $get_info = false;
        while (!@feof($fsock)) {
            if ($get_info) {
                $version_info .= @fread($fsock, 1024);
            }
            else {
                if (@fgets($fsock, 1024) == "\r\n") {
                    $get_info = true;
                }
            }
        }
        @fclose($fsock);
        if (!is_numeric(substr( $version_info,0,1))) {
            $version_info = -1;
        }
    } else {
        $version_info = -1;
    }
    // we check every 12 hours
    set_transient('aip_version', $version_info, 60*60*12);  
    return $version_info;
}

function aiFirstElement( $a ){ 
  return $a[0];
}

function aiGet2ndLvlDomainName($url) {
 // a list of decimal-separated TLDs
 static $doubleTlds = array('co.uk', 'me.uk', 'net.uk', 'org.uk', 'sch.uk', 'ac.uk', 'gov.uk', 'nhs.uk', 'police.uk', 'mod.uk', 'asn.au', 'com.au','net.au', 'id.au', 'org.au', 'edu.au', 'gov.au', 'csiro.au','br.com', 'com.cn', 'com.tw', 'cn.com', 'de.com', 'eu.com','hu.com', 'idv.tw', 'net.cn', 'no.com', 'org.cn', 'org.tw','qc.com', 'ru.com', 'sa.com', 'se.com', 'se.net', 'uk.com','uk.net', 'us.com', 'uy.com', 'za.com');

 // sanitize the URL
 $url = trim($url);

 // check if we can parse the URL
 if ($host = parse_url($url, PHP_URL_HOST)) {

  // check if we have IP address
  if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $host)) {
   return $host;
  }

  // sanitize the hostname
  $host = strtolower($host);

  // get parts of the URL
  $parts = explode('.', $host);

  // if we have just one part (eg localhost)
  if (!isset($parts[1])) {
   return $parts[0];
  }

  // grab the TLD
  $tld = array_pop($parts);

  // grab the hostname
  $host = array_pop($parts) . '.' . $tld;

  // have we collected a double TLD?
  if (!empty($parts) && in_array($host, $doubleTlds)) {
   $host = array_pop($parts) . '.' . $host;
  }

  return $host;
 }

 return 'unknown domain';
}

?>