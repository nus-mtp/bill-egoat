<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Highcharts {
    
    private static $chart_id = 0;
    
    private $shared_opts     = array(); // shared grah data
    private $global_opts    = array(); // All stocked graph data
    private $opts                    = array(); // current graph data
    private $replace_keys, $orig_keys, $serie_index = 0;
    
    public $js_chart_name = 'chart'; // name of the js var    
    
    public function __construct($config = array())
    {                
        if (count($config) > 0) $this->initialize($config);
    }
    
    public function initialize($config = array(), $config_path = 'highcharts')
    {
        if (is_string($config)) // string means "load this template"
        {
            $ci =& get_instance();
            $ci->config->load($config_path);
            
            $config = $ci->config->item($config);
            
            if (count($config) > 0)
            {
                $this->opts = $this->set_local_options($config);
            }
            
        }
        if (isset($config['shared_options']) AND empty($this->shared_opts))
        {
            $this->shared_opts = $config['shared_options'];
            unset($config['shared_options']);
        }
        
        if (! isset($this->opts['series'])) $this->opts['series'] = array();
        if (! isset($this->opts['chart']['renderTo'])) 
            $this->opts['chart']['renderTo'] = 'hc_chart';

        return $this;
    }

    public function set_global_options($options = array())
    {
        if (! empty($options)) 
            $this->shared_opts = $this->set_local_options($options);
        
        return $this;
    }

    public function __call($func, $args)
    {
        if (strpos($func,'_'))
        {
            list($action, $type) = explode('_', $func);
        
            if (! isset($this->opts[$type]))
            {
                $this->opts[$type] = array();
            }
            switch ($action)
            {
                case 'set':
                $this->opts[$type] = $this->set_local_options($args[0]);
                break;
                
                case 'push':
                $this->opts[$type] += $this->set_local_options($args[0]);
                break;
                
                case 'unset':
                $this->unset_local_options($args, $type);
                break;
            }
        }
        
        return $this;
    }
    
    private function set_local_options($options = array(), $root = array())
    {
        foreach ($options as $opt_key => $opt_name)
        {        
            if(is_string($opt_key))
            {
                if(is_object($opt_name))
                {
                    $root[$opt_key] = array();
                    $root[$opt_key] = 
                    $this->set_local_options($opt_name, $root[$opt_key]); 
                }
                else $root[$opt_key] = $this->encode_function($opt_name);
            }
        }
        return $root;
    }
    
    private function unset_local_options($options = array(), $type)
    {        
        foreach ($options as $option)
        {
            if (array_key_exists($option, $this->opts[$type]))
            {
                unset($this->opts[$type][$option]);
            }
        }
    }
    
    public function set_title($title = '', $subtitle = '')
    {
        if ($title) $this->opts['title']['text'] = $title;
        if ($subtitle) $this->opts['subtitle']['text'] = $subtitle;

        return $this;
    }
    
    function set_axis_titles($x_title = '', $y_title = '')
    {
        if ($x_title) $this->opts['xAxis']['title']['text'] = $x_title;
        if ($y_title) $this->opts['yAxis']['title']['text'] = $y_title;
        
        return $this;
    }

     function set_axis_ymax( $y_max)
    {
        if ($y_max) $this->opts['yAxis']['max'] = $y_max;

        return $this;
    }

     function set_axis_ymin( $y_min)
    {
        if ($y_min) $this->opts['yAxis']['min'] = $y_min;

        return $this;
    }

     function set_axis_ytype( $type = '')
    {
        If($type and is_string($type)) $this->opts['yAxis']['type'] = $type;

        return $this;
    }

                
     function set_axis_xtype( $type = '')
    {
        If($type and is_string($type)) $this->opts['xAxis']['type'] = $type;

        return $this;
    }

     function set_axis_xmax($x_max)
    {
        if ($x_max) $this->opts['xAxis']['max'] = $x_max;

        return $this;
    }
    
    public function render_to($id = '')
    {
        $this->opts['chart']['renderTo'] = $id;

        return $this;
    }
    
    public function set_type($type = '')
    {
        if ($type AND is_string($type)) $this->opts['chart']['type'] = $type;
        
        return $this;
    }
    
    
    public function set_dimensions($width = null, $height = null)
    {
        if ($width)  $this->opts['chart']['width'] = (int)$width;
        if ($height) $this->opts['chart']['height'] = (int)$height;
        
        return $this;
    }
    
    public function set_serie($options = array(), $serie_name = '')
    {
        if ( ! $serie_name AND ! isset($options['name']))
        {
            $serie_name = count($this->opts['series']);
        }
        // override with the serie name passed
        else if ($serie_name AND isset($options['name']))
        {
            $options['name'] = $serie_name;
        }
        
        $index = $this->find_serie_name($serie_name);
                    
        if (count($options) > 0)
        {
            foreach($options as $key => $value)
            {
                    $value = (is_numeric($value)) ? (float)$value : $value;
                    $this->opts['series'][$index][$key] = $value;
            }
        }
        return $this;
    }
    
    public function set_serie_options($options = array(), $serie_name = '')
    {
        if ($serie_name AND count($options) > 0)
        {
            $index = $this->find_serie_name($serie_name);
                        
            foreach ($options as $key => $opt)
            {
                $this->opts['series'][$index][$key] = $opt;
            }
        }
        return $this;
    }
    
    
    public function push_serie_data($value = '', $serie_name = ''){
        
        if ($serie_name AND $value)
        {
            $index = $this->find_serie_name($serie_name);
            
            $value = (is_numeric($value)) ? (float)$value : $value;
                
            $this->opts['series'][$index]['data'][] = $value;
        }
        return $this;
    }
    
    private function find_serie_name($name)
    {
        $tot_indexes = count($this->opts['series']);
        
        if ($tot_indexes > 0)
        {
            foreach($this->opts['series'] as $index => $serie)
            {
                if (isset($serie['name']) AND 

                    strtolower($serie['name']) == strtolower($name))
                {
                    return $index;
                }
            }
        }
        
        $this->opts['series'][$tot_indexes]['name'] = $name;
        
        return $tot_indexes;
    }

     public function export_file($filename){
         $this->opts['exporting']['enabled'] = True;
         $this->opts['exporting']['enableImages'] = True;
         $this->opts['exporting']['filename'] = $filename;
         $this->opts['exporting']['type']=  'image/jpeg';
         return $this;
        }
    
    public function push_categorie($value, $axis = 'x')
    {
        if(trim($value)!= '') 
           $this->opts[$axis.'Axis']['categories'][] = $value;

        return $this;
    }

         public function push_xcategorie($value)
    {

          $this->opts['xAxis']['categories'] = $value;

        return $this;
    }    

    public function from_result($data = array())
    {
        if (! isset($this->opts['series']))
        {
            $this->opts['series'] = array();
        }
                
        foreach ($data['data'] as $row)
        {
            if (isset($data['x_labels'])) 
                $this->push_categorie($row->$data['x_labels'],'x');
            if (isset($data['y_labels'])) 
                $this->push_categorie($row->$data['y_labels'],'y');
            
            foreach ($data['series'] as $name => $value)
            {    
                // there is no options, juste assign name / value pair
                if (is_string($value))
                {
                    $text = (is_string($name)) ? $name : $value;
                    $dat  = $row->$value;
                }
                
                // options are passed
                else if (is_array($value))
                {
                    if (isset($value['name']))
                    {
                        $text = $value['name'];
                        unset($value['name']);
                    }
                    else
                    {
                        $text = $value['row'];
                    }
                    $dat = $row->{$value['row']};
                    unset($value['row']);
                    
                    $this->set_serie_options($value, $text);
                }
                
                $this->push_serie_data($dat, $text);
            }
        }
        return $this;
    }
    
    public function add($options = array(), $clear = true)
    {
        if (count($this->global_opts) <= 
            self::$chart_id AND ! empty($this->opts['series']))
        {
            if (is_string($options) AND trim($options) !== '')
            {
                $this->global_opts[$options] = $this->opts;
            }
            else
            {
                $this->global_opts[self::$chart_id] = 
                (count($options)> 0) ? $options : $this->opts;
            }
        }
        
        self::$chart_id++;    
        
        if ($clear === true) $this->clear();
                    
        return $this;
    }
    
    public function get($clear = true)
    {
        $this->add();
        
        foreach ($this->global_opts as $key => $opts)
        {
            $this->global_opts[$key] = $this->encode($opts);
        }    
        
        return $this->process_get($this->global_opts, $clear, 'json');
    }
    
    public function get_array($clear = true)
    {
        $this->add();
        
        return $this->process_get($this->global_opts, $clear, 'array');
    }
    
    public function encode($options)
    {
        $options = str_replace('\\', '', json_encode($options));
        return str_replace($this->replace_keys, $this->orig_keys, $options);
    }
    
    private function process_get($options, $clear, $type)
    {
        if (count($this->shared_opts) > 0)
        {
            $global = ($type == 'json') ? 
            $this->encode($this->shared_opts) : $this->shared_opts;
            
            $options = array('global' => $global, 'local' => $options);
        }
        
        if ($clear === true) $this->clear();
        
        return $options;
    }
    
    public function render()
    {
        $this->add();
        
        $i = 1; $d = 1; $divs = '';

        $embed  = '<script type="text/javascript">'."\n";
        $embed .= '$(function(){'."\n";
        
        foreach ($this->global_opts as $opts)
        {
            if (count($this->shared_opts) > 0 AND $i === 1)
      {
      $embed.='Highcharts.setOptions('.$this->encode($this->shared_opts).');'."\n";
      }

      if ($opts['chart']['renderTo'] == 'hc_chart')
      {
        $opts['chart']['renderTo'] .= '_'.$d;
        $d++;
      }
            
        $embed .= 'var '.$this->js_chart_name.'_'.$i.
                  ' = new Highcharts.Chart('.$this->encode($opts).');'."\n";
            $divs  .= '<div id="'.$opts['chart']['renderTo'].'"></div>'."\n";
            $i++;
        }
        
        $embed .= '});'."\n";
        $embed .= '</script>'."\n";
        $embed .= $divs;
        
        $this->clear();
                
        return $embed;
    }
    
    
    public function clear($shared = false)
    {
        $this->opts = array();
        $this->opts['series'] = array();
        $this->opts['chart']['renderTo'] = 'hc_chart';
        $this->serie_index = 0;
        
        if ($shared === true) $this->shared_opts = array();
        
        return $this;
    }
    
    private function encode_function($array = array())
    {
        if (is_string($array)) {
            $array = $this->delimit_function($array);
        }
        else {
            foreach($array as $key => $value) {
                if (is_array($value)) {
                    $this->encode_function($value);
                }
                else {
                    $array[$key] = $this->delimit_function($value);
                }                
            }
        }
        return $array;
    }
    
    private function delimit_function($string = '')
    {
        if(strpos($string, 'function(') !== false)
        {
          $this->orig_keys[] = $string;
          $string = '$$' . $string . '$$';
              $this->replace_keys[] = '"' . $string . '"';
        }
        return $string;
    }

}

?>