<?php

function make_option_list($list_type, $list_values, $current_value)
{
    $ret='';
    foreach($list_values as $that_values)
        {
            $ret.="<option value=\"$that_values->id\"";
            if ($that_values->id == $current_value) 
                $ret.=" selected=\"selected\"";
            $ret.=">$that_values->value</option>";
        }
    return $ret;
}

function make_link_list($list_type, $route_prefix, $route_sufix, $list_values)
{
    $ret='<ul>';
    
    foreach ($list_values as $that_values)
        {
            $ret.="<li><a href=\"$route_prefix$that_values->id$route_sufix\">$that_values->value</a></li>";
        }
    
    $ret.='</ul>';
    
    return $ret;
}

function make_list($list_type, $route_value, $list_values)
{
    $ret='<ul>';
    
    foreach ($list_values as $that_values)
        {
            $ret.="<li><a href=\"$route_value$that_values->xid\">$that_values->xvalue</a></li>";
        }
    
    $ret.='</ul>';
    
    return $ret;
}

function make_file_list($route_value, $list_values, $vx_id, $vx_value)
{
    $ret='<ul>';
    
    foreach ($list_values as $that_values)
        {
            $ret.='<li><a href="'.$route_value.$that_values->$vx_id.'">'.$that_values->$vx_value.'</a></li>';
            //$ret.="<a href=\"";
            //$ret.=route('docs.edit', $that_values->doc_id);
            //$ret.="\" alt=\"edytuj\">";
            //$ret.="<span class=\"glyphicon glyphicon-import pull-right\"></span>";
            //$ret.="</a>";
        }
        
    $ret.='</ul>';
    
    return $ret;
}

function kafelek($column, $header, $text, $colors)
{?>


    <div class="col-lg-<?php echo $column; ?> mb-<?php echo $column; ?>">
      <div class="card">
        <div class="card-header"><?php echo $header; ?></div>
        <div class="card-body">
          <div class="card-text" style="white-space: pre-line"><?php echo $text; ?></div>
        </div>
      </div>
    </div>
<?php
}

?>